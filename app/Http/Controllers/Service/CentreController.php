<?php

namespace App\Http\Controllers\Service;

use App\Centre;
use App\Family;
use App\Http\Controllers\Controller;
use App\Registration;
use Auth;
use Carbon\Carbon;
use DB;

class CentreController extends Controller
{
    /**
     * Displays a printable version of the families registered with the center.
     *
     * @param Centre $centre
     */
    public function printRegistrations(Centre $centre)
    {
        $registrations = $centre->registrations;

        // TODO Just passing the registrations and centre for now. Could optomise DB hits with eager load of stuff we need.
        return view('service.printables.families', [
                'sheet_title' => 'Printable Families Sheet',
                'sheet_header' => 'Families Collection Sheet',
                'centre' => $centre,
                'registrations' => $registrations,
            ]
        );

    }
}
