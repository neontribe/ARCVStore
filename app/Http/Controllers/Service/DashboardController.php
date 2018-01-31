<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Auth;

class DashboardController extends Controller
{
    /**
     * Index the Dashboard options
     */
    public function index()
    {
        $user = Auth::user();
        $centre = $user->centre;
        $pref_collection = ($centre->print_pref === 'collection');

        $data = [
            "user_name" => $user->name,
            "centre_name" => $centre ? $centre->name : null,
            "centre_id" => $centre ? $centre->id : null,
            "print_button_text" => $pref_collection ? 'Print collection sheet' : 'Print all family sheets',
        ];
        return view('service.dashboard', $data);
    }
}
