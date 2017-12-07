<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewRegistrationRequest;
use Illuminate\Http\Request;
use Auth;

class RegistrationController extends Controller
{
    /**
     * Returns the registration page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $data = [
            "user_name" => $user->name,
            "centre_name" => ($user->centre) ? $user->centre->name : null,
        ];
        return view('service.registration', $data);
    }

    /**
     * Stores an incoming Registration.
     *
     * @param Request $request
     */
    public function store(StoreNewRegistrationRequest $request)
    {
    }
}
