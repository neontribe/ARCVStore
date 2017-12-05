<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
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
            "user_name" => $user->name,k
            "centre_name" => $user->centre()->name,
        ];
        return view('service.dashboard', $data);
    }
}
