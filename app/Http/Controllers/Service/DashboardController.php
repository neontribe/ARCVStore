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
        $data = [
            "user_name" => $user->name,
            "centre_name" => ($user->centre) ? $user->centre->name : null,
            "centre_id" => ($user->centre->id) ? $user->centre->id : null,
        ];
        return view('service.dashboard', $data);
    }
}
