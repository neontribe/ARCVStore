<?php

namespace App\Http\Controllers\Service;

use Carbon\Carbon;
use DB;
use App\Family;
use App\Carer;
use App\Child;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewRegistrationRequest;
use App\Registration;
use Auth;
use Log;

class RegistrationController extends Controller
{
    /**
     * Returns the registration page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $data = [
            "user_name" => $user->name,
            "centre_name" => ($user->centre) ? $user->centre->name : null,
        ];
        return view('service.registration', $data);
    }

    /**
     * Stores an incoming Registration.
     *
     * @param StoreNewRegistrationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreNewRegistrationRequest $request)
    {
        // Duplicate families are fine at this point.
        $family = new Family(['rvid' => Family::generateRVID()]);

        // Create Carers
        // TODO: Alter request to pre-join the array?
        $carers = array_map(
            function ($carer) {
                return new Carer(['name' => $carer]);
            },
            array_merge(
                (array)$request->get('carer'),
                (array)$request->get('carers')
            )
        );

        // Create Children
        $children = array_map(
            function ($child) {
                return new Child(['dob' => $child]);
            },
            (array)$request->get('children')
        );

        // Create Registration
        $registration = new Registration([
            'cc_reference' => $request->get('cc_reference'),
            'consented_on' => Carbon::now(),
            'eligibility' => $request->get('eligibility'),
            // diary and chart are not saved right now.
        ]);

        // Try to transact, so we can roll it back
        try {
            DB::transaction(function () use ($registration, $family, $carers, $children) {
                $family->save();
                $family->carers()->saveMany($carers);
                $family->children()->saveMany($children);
                $registration->family()->associate($family);
                $registration->centre()->associate(Auth::user()->centre);
                $registration->save();
            });
        // Oops! Log that
        } catch (\Exception $e) {
            Log::error('Bad transaction for '. __CLASS__ .'@'. __METHOD__ .' by service user '.Auth::id());
            return redirect()->route('service.registration')->withErrors('message', 'Registration failed.');
        }
        // Or return the success
        Log::info('Registration '.$registration->id.' stored by service user '.Auth::id());
        return redirect()->route('service.registration')->with('message', 'Registration saved.');
    }
}
