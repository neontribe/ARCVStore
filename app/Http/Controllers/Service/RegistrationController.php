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
    public function create()
    {
        $user = Auth::user();
        $data = [
            "user_name" => $user->name,
            "centre_name" => ($user->centre) ? $user->centre->name : null,
        ];
        return view('service.create_registration', $data);
    }

    /**
     * Show the Registration / Family edit form
     *
     * @param integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        // Get User and Centre;
        // TODO: turn this into a masthead view composer on the app service provider.
        $user = Auth::user();
        $data = [
            'user_name' => $user->name,
            'centre_name' => ($user->centre) ? $user->centre->name : null,
        ];

        // Get the registration, with deep eager-loaded Family (with Children and Carers)
        $registration = Registration::with([
            'family' => function ($q) {
                $q->with('children', 'carers');
            }
        ])->findOrFail($id);

        // Grab carers copy for shift)ing without altering family->carers
        $carers = $registration->family->carers->all();

        return view('service.edit_registration', array_merge(
            $data,
            [
                'registration' => $registration,
                'family' => $registration->family,
                'pri_carer' => array_shift($carers),
                'sec_carers' => $carers,
                'children' => $registration->family->children,
            ]
        ));
    }

    /**
     * Displays a printable version of the Registration.
     *
     * @param App\Registration $registration
     */
    public function print(Registration $registration)
    {
        $user = Auth::user();

        // Get the registration, with deep eager-loaded Family (with Children and Carers)
        $family = $registration->family->with('children', 'carers')->first();
        $carers = $family->carers;

        return view('service.printables.family', [
                'user_name' => $user->name,
                'centre_name' => ($user->centre) ? $user->centre->name : null,
                'sheet_title' => 'Printable Family Sheet',
                'sheet_header' => 'Family Collection Sheet',
                'family' => $family,
                'pri_carer' => $carers->first(),
                // Remove the primary carer from collection
                'sec_carers' => $carers->forget(0),
                'children' => $family->children,
            ]
        );

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
                // Note: Carbon uses different time formats than laravel validation
                // Also, format() uses the current day of month if unspecified, so we startOfMonth() it
                return new Child([
                        'dob' => Carbon::createFromFormat('Y-m', $child)
                            ->startOfMonth()
                            ->format('Y-m-d'),
                    ]);
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
        } catch (\Exception $e) {
            // Oops! Log that
            Log::error('Bad transaction for ' . __CLASS__ . '@' . __METHOD__ . ' by service user ' . Auth::id());
            Log::error($e->getTraceAsString());
            // Throw it back to the user
            return redirect()->route('service.registration.create')->withErrors('Registration failed.');
        }
        // Or return the success
        Log::info('Registration ' . $registration->id . ' created by service user ' . Auth::id());
        // and go to the edit page for the new registration
        return redirect()
            ->route('service.registration.edit', ['id' => $registration->id])
            ->with('message', 'Registration created.');
    }

    public function update($id)
    {
        //
    }
}
