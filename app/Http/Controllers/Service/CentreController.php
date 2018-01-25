<?php

namespace App\Http\Controllers\Service;

use App\Centre;
use App\Family;
use App\Http\Controllers\Controller;
use App\Registration;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\View\View;

class CentreController extends Controller
{
    /**
     * Displays a printable version of the families registered with the center.
     *
     * @param Centre $centre
     *
     */
    public function printRegistrations(Centre $centre)
    {
        $registrations = $centre->registrations;

        $reg_chunks = $registrations->chunk(20);
        // TODO Just passing the registrations and centre for now. Could optimise DB hits with eager load of stuff we need.
        return view(
            'service.printables.families',
            [
                'sheet_title' => 'Printable Register',
                'sheet_header' => 'Register',
                'centre' => $centre,
                'reg_chunks' => $reg_chunks,
            ]
        );
    }
}
