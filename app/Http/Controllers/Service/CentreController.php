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
use Excel;

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

        // TODO Just passing the registrations and centre for now.
        // Could optimise DB hits with eager load of stuff we need.
        return view(
            'service.printables.families',
            [
                'sheet_title' => 'Printable Register',
                'sheet_header' => 'Register',
                'centre' => $centre,
                'registrations' => $registrations,
            ]
        );
    }

    /**
     * Exports a summary of registrations from the User's relevant Centres.
     *
     */
    public function exportRegistrationsSummary()
    {
        // Get User
        $user = Auth::user();

        // Get now()
        $now = Carbon::now();

        // Get centres
        $centres = $user->relevantCentres();

        // get registrations and order them
        $registrations = Registration::whereIn('centre_id', $centres->pluck('id')->all())
            ->with(['centre','family.children','family.carers'])
            ->orderBy('centre_id', 'asc')
            ->get();

        $rows = [];
        $headers = [
            "RVID",
            "Centre",
            "Primary Carer",
            "Food Chart Received",
            "Food Diary Received",
            "Entitlement",
            "Eligible Children"
        ];

        foreach ($registrations as $registration) {

            /*
            $row = [
                "RVID" => $registration->family->rvid,
                "Centre" => $registration->centre->name,
                "Primary Carer" => $registration->family->carers->first()->name,
                "Food Chart Received" => $registration->fm_chart_on,
                "Food Diary Received" => $registration->fm_diary_on,
                "Entitlement" => $registration->family->entitlement,
                "Eligible Children" => 0,
            ];
            */

            $kids = [];
            $due_date = "";

            foreach ($registration->family->children as $index => $child) {
                $status = $child->getStatus();
                $kid_dob_key = 'Child ' . $index . ' DoB';

                switch ($status['eligibility']) {
                    case 'Pregnancy':
                        $due_date = $child->dob;
                        break;
                    case 'Eligible':
                        $kids[$kid_dob_key] = $child->dob;
                        $row["Eligible Children"] = $row["Eligible Children"] + 1;
                        break;
                    case "Ineligible":
                        $kids[$kid_dob_key] = $child->dob;
                        break;
                }
            }
            $row=array_merge($row, $kids);
            $row["Due date"] = $due_date;



            $rows[] = $row;
        }

        die (print_r($rows));

        Excel::create(
            'RegSummary_' . $now->format('YmdHis'),
            function ($excel) use ($user, $rows, $headers) {
                $excel->setTitle('Registration Summary');
                $excel->setDescription('Summary of Registrations from Centres available to '. $user->name);
                $excel->setManager($user->name);
                $excel->setCompany(env('APP_URL'));
                $excel->setCreator(env('APP_NAME'));
                $excel->setKeywords([]);
                $excel->sheet(
                    'Registrations',
                    function ($sheet) use ($rows, $headers) {
                        $sheet->setOrientation('landscape');
                        $sheet->row(1, array($headers));
                        $sheet->fromArray($rows,null,'A2', false, false);
                    }
                );
            }
        )->download('csv');

    }
}
