<?php

namespace App\Policies;

use App\Registration;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegistrationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the User can view ANY registration
     * @param User $user
     * @param Registration $registration
     * @return bool
     */
    public function viewAny(User $user, Registration $registration)
    {

    }

    /**
     * Determine if the User can post an update to ANY registration
     * @param User $user
     * @param Registration $registration
     * @return bool
     */
    public function update(User $user, Registration $registration)
    {

    }

    /**
     * Determine if the User can update the diary and chart on a registration
     * @param User $user
     * @param Registration $registration
     * @return bool
     */
    public function updateDiaryAndChart(User $user, Registration $registration)
    {

    }


}
