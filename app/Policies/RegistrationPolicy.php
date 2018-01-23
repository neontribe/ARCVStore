<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegistrationPolicy
{
    use HandlesAuthorization;

    // These are permissions that we need to check a user for.
    public function updateDiary(User $user)
    {
        return ($user->role == "foodmatters_user");
    }

    public function updateChart(User $user)
    {
        return ($user->role == "foodmatters_user");
    }
}
