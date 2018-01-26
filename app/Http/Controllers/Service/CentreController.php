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

        // set blank rows for laravel-excel
        $rows = [];

        // Looks like larevel-excel can't auto-generate headers
        // by collating all the row keys and normalising.
        // So we have to do it by hand.

        // create base headers
        $headers = [
        ];

        // Per registration...
        foreach ($registrations as $reg) {
            $row = [
                "RVID" => $reg->family->rvid,
                "Centre" => $reg->centre->name,
                "Primary Carer" => $reg->family->carers->first()->name,
                "Food Chart Received" => (!is_null($reg->fm_chart_on)) ? $reg->fm_chart_on->format('d/m/Y') : null,
                "Food Diary Received" => (!is_null($reg->fm_diary_on)) ? $reg->fm_diary_on->format('d/m/Y') : null,
                "Entitlement" => $reg->family->entitlement,
            ];

            // Per child dependent things
            $kids = [];
            $due_date = null;
            $eligible = 0;
            foreach ($reg->family->children as $index => $child) {
                // make a 'Child X DoB' key
                $child_index = $index + 1;
                $status = $child->getStatus();
                $dob_header = 'Child ' . (string)$child_index . ' DoB';

                // Arrange kids by eligibility
                switch ($status['eligibility']) {
                    case 'Pregnancy':
                        $due_date = $child->dob->format('d/m/Y');
                        break;
                    case 'Eligible':
                        $kids[$dob_header] = $child->dob->format('m/Y');
                        $eligible += 1;
                        break;
                    case "Ineligible":
                        $kids[$dob_header] = $child->dob->format('m/Y');
                        break;
                }
            }
            // Add count oif eligible kids
            $row["Eligible Children"] = $eligible;

            // Add our kids back in
            $row=array_merge($row, $kids);

            // TODO: What happens for families with two concurrent pregnancies?
            $row["Due Date"] = $due_date;
            $row["Leaving Date"] = null;

            // update the headers if necessary
            if (count($headers) < count($row)) {
                $headers = array_keys($row);
            }
            // stack new row onto the array
            $rows[] = $row;
        }

        // en-sparsen the rows with empty fields for unused header.
        foreach ($rows as $index => $row) {
            $sparse_row = [];
            foreach ($headers as $header) {
                $sparse_row[$header] = (array_key_exists($header, $row)) ? $row[$header] : null;
            }
            // Key/value order matters to laravel-excel
            $rows[$index] = $sparse_row;
        }

        /**
         * TODO: write an OO system for formatting things better.
         * Ideally we'd have formatting for
         * - rows with a leaving date showing grey
         * - ineligible children showing grey
         * - children with changes in near future showing red.
         */

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
                        $sheet->row(1, $headers);
                        $sheet->cells('A1', function ($cells) {
                            $cells->setBackground('#6495ED')
                                ->setFontWeight('bold');
                        });
                        $letters = range('A', 'Z');
                        $sheet->cells('B1:' . $letters[count($headers)-1] .'1', function ($cells) {
                            $cells->setBackground('#9ACD32')
                                ->setFontWeight('bold');
                        });
                        $sheet->fromArray($rows, null, 'A2', false, false);
                    }
                );
            }
        )->download('csv');

        // avoid xls till we have all the formatting.
        //)->download('xls');
    }
}
