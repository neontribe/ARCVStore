<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Family;
use App\Carer;
use App\Child;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewRegistrationRequest;
use App\Http\Requests\StoreUpdateRegistrationRequest;
use App\Registration;
use Auth;
use Log;

class RegistrationController extends Controller
{
    /**
     * List all the Registrations (search-ably)
     *
     * This is a con. It only lists the registrations available to a User's CC's Sponsor
     * This means a User can see the Registrations in his 'neighbor' CCs under a Sponsor
     *
     * Also, the view contains the search functionality.
     */
    public function index(Request $request)
    {
        // Masthead bit
        $user = Auth::user();
        $data = [
            "user_name" => $user->name,
            "centre_name" => ($user->centre) ? $user->centre->name : null,
        ];

        // Slightly roundabout method...
        $neighbor_centre_ids = $user
            ->centre
            ->sponsor
            ->centres
            ->pluck('id')
            ->toArray();

        $family_name = $request->get('family_name');

        // Horrid: get array of families where first carer for family is like family name.
        $q = collect(
            DB::select(
                DB::raw("
              SELECT t1.pri_carer_id, t2.name, t2.family_id 
              FROM (
                SELECT 
                  min(id) 
                  AS pri_carer_id 
                  FROM carers 
                  GROUP BY family_id
                ) 
                AS t1
              INNER JOIN 
                carers 
                AS t2 
                ON t1.pri_carer_id = t2.id
              WHERE name like :match"),
                ["match" => '%'.$family_name.'%']
            )
        );

        Searchy::search('carers')->fields('name')->query($family_name)->getQuery();

        $filtered_family_ids = $q->pluck('family_id');

        $q = Registration::query();
        if (!empty($neighbor_centre_ids)) {
            $q = $q->whereIn('centre_id', $neighbor_centre_ids);
        }
        if (!empty($filtered_family_ids)) {
            $q = $q->whereIn('family_id', $filtered_family_ids);
        }

        $registrations = $q->simplePaginate(15);

        $data = array_merge(
            $data,
            [
                'registrations' => $registrations,
            ]
        );
        return view('service.index_registration', $data);
    }

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
     * @param integer $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function print($id)
    {
        $user = Auth::user();

        $registration = Registration::with([
            'family' => function ($q) {
                $q->with('children', 'carers');
            }
        ])->findOrFail($id);

        $carers = $registration->family->carers->all();

        return view(
            'service.printables.family',
            [
                'user_name' => $user->name,
                'centre_name' => ($user->centre) ? $user->centre->name : null,
                'sheet_title' => 'Printable Family Sheet',
                'sheet_header' => 'Family Collection Sheet',
                'family' => $registration->family,
                'pri_carer' => array_shift($carers),
                // Remove the primary carer from collection
                'sec_carers' => $carers,
                'children' => $registration->family->children,
            ]
        );
    }

    /**
     * Stores an incoming Registration.
     *
     * @param StoreNewRegistrationRequest $request
     * @throws \Throwable $e
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
                $month_of_birth = Carbon::createFromFormat('Y-m', $child)->startOfMonth();
                return new Child([
                        'born' => $month_of_birth->isPast(),
                        'dob' => $month_of_birth->toDateTimeString(),
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

    public function update(StoreUpdateRegistrationRequest $request)
    {
        // TODO: add validation on the request like store has.

        // Create New Carers
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

        // Create New Children
        $children = array_map(
            function ($child) {
                // Note: Carbon uses different time formats than laravel validation
                // Also, format() uses the current day of month if unspecified, so we startOfMonth() it
                $month_of_birth = Carbon::createFromFormat('Y-m', $child)->startOfMonth();
                return new Child([
                    'born' => $month_of_birth->isPast(),
                    'dob' => $month_of_birth->toDateTimeString(),
                ]);
            },
            (array)$request->get('children')
        );

        // Fetch Registration and Family
        $registration = Registration::where('id', $request->get('registration'))->first();
        $family = $registration->family;

        // update registration
        $registration->cc_reference = $request->get('cc_reference');

        // Try to transact, so we can roll it back
        try {
            DB::transaction(function () use ($registration, $family, $carers, $children) {
                // delete the old carer's and children. messy.
                $family->carers()->delete();
                $family->children()->delete();
                // save the new ones!
                $family->carers()->saveMany($carers);
                $family->children()->saveMany($children);
                // save changes to registration.
                $registration->save();
                // no changes to centre, or family objects directly.
            });
        } catch (\Exception $e) {
            // Oops! Log that
            Log::error('Bad transaction for ' . __CLASS__ . '@' . __METHOD__ . ' by service user ' . Auth::id());
            Log::error($e->getTraceAsString());
            // Throw it back to the user
            return redirect()->route('service.registration.edit')->withErrors('Registration update failed.');
        }
        // Or return the success
        Log::info('Registration ' . $registration->id . ' updated by service user ' . Auth::id());
        // and go back to edit page for the changed registration
        return redirect()
            ->route('service.registration.edit', ['id' => $registration->id])
            ->with('message', 'Registration updated.');
    }
}
