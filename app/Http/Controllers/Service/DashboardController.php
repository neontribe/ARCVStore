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
        // When we have Auth on
        $user = Auth::user();
        print_r($user);
        $data = [
            "user_name" => $user->name,
            "centre_name" => $user->centre->name,
        ];
        return view('service.dashboard', $data);
    }
}
