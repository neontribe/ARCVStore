<?php

namespace App\Http\Controllers\Service;

use DB;
use Carbon\Carbon;
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
     */
    public function store(StoreNewRegistrationRequest $request)
    {
        // Duplicate families are fine at this point.
        // Make a family;
        $family = new Family(['rvid' => Family::generateRVID()]);

        // Create carers collection
        // TODO: Alter request to pre-join the array?
        $carers = array_merge(
            (array)$request->get('carer'),
            (array)$request->get('carers')
        );

        $carer_models = array_map(
            function ($carer) {
                return new Carer(['name' => $carer]);
            },
            $carers
        );

        // Add Children
        $kids_models = array_map(
            function ($kid) {
                return new Child(['dob' => $kid]);
            },
            $request->get('kids')
        );

        $registration = new Registration([
            'cc_reference' => $request->get('cc_reference'),
            'consented_on' => Carbon::now(),
            'eligibility' => $request->get('eligibility'),
            // diary and chart are not saved right now.
        ]);

        try {
            DB::beginTransaction(function () use ($family, $registration, $carer_models, $kids_models) {
                $family->save();
                $family->carers()->saveMany($carer_models);
                $family->children()->saveMany($kids_models);
                $registration->family()->associate($family);
                $registration->centre()->associate(Auth::user()->centre);
                $registration->save();
            });
        } catch (\Exception $e) {
            DB::rollback();
        }
        Log::info('Registration '.$registration->id.' stored by service user '.Auth::id());
        return redirect()->route('service.registration')->with('message', 'Registration saved.');
    }
}
