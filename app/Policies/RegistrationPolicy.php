<?php

namespace App\Policies;

use App\Registration;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegistrationPolicy
{
    use HandlesAuthorization;

    public function updateDiary(User $user, Registration $registration)
    {
        return ($user->role == "FoodMattersUser");
    }

    public function updateChart(User $user, Registration $registration)
    {
        return ($user->role == "FoodMattersUser");
    }
}
