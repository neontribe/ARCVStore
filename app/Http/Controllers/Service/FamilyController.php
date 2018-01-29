<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Family;
use App\Http\Controllers\Controller;
use Auth;
use Log;

class FamilyController extends Controller
{
    /**
     * For now - the only update we can do is deactivate.
     * The rest of family related info is updated through a related Registration.
     *
     * @param Request $request (Family fields ['id', 'leaving_on', 'leaving_reason'])
     * @param Registration $registration (because permission to update comes through Registration)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function familyUpdate(Request $request, Registration $registration)
    {
        $user = $request->user();
        // Since a family might have more than one Registration we send it with the Request.
        $reg_family = $registration->family;
        $family = Registration::findOrFail($request->get('id'));

        // Make sure we have a family, a registartion that match.
        if ($family !== $reg_family) {
            Log::info($user->id . ' attempted to update a family without a matching registration.');
            return redirect()
                ->route('service.registration.edit', $registration->id)
                ->with('error', 'Family deactivation failed. Please try again.')
            ;
        }

        if (Gate::forUser($user)->denies('update_registration', $registration)) {
            Log::info('Family update ' . $registration->id . ' deactivate family ' . $family->id . ' by user ' . $user->id . ' failed.');
            return response('Unauthorized.', 401);
        }

        // Validate the request.
        $this->validate($request, Family::rules());

        // If the family is leaving box is ticked, we also need a reason...
        // Assign leaving_on to now
        $family->leaving_on = Carbon::now();
        $family->leaving_reason = $request->leaving_reason;
        $family->save();

        // Go back to registratio/ family search listing
        return redirect()
            ->route('service.registration.index')
            ->with('message', 'Family '. $family->id . ' de-activated.');
    }
}
