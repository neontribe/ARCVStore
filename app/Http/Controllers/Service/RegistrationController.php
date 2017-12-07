<?php

namespace App\Http\Controllers\Service;


use App\Http\Controllers\Controller;
use Auth;

class RegistrationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [
            "user_name" => $user->name,
            "centre_name" => ($user->centre) ? $user->centre->name : null,
        ];
        return view('service.registration', $data);
    }
}
