<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\User;
// When we have auth on.
// use Auth;

class DashboardController extends Controller
{
    /**
     * Index the Dashboard options
     */
    public function index()
    {
        // When we have Auth on
        // $user = Auth::user();
        $user = User::find(1);
        $data = [
            "user_name" => $user->name,
            "centre_name" => $user->center()->name,
        ];
        return view('service.dashboard', $data);
    }
}
