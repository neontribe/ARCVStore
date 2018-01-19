<?php

namespace App\Providers;

use App\Registration;
use App\User;
use Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Authorisations

        // When a registration is requested
        Gate::define('view-registration', function (User $user, Registration $registration) {
            // Check the registration is for a centre relevant to the user.
            return $user->isRelevantCentre($registration->centre);
        });

        // When a registration is updated
        Gate::define('update-registration', function (User $user, Registration $registration) {
            // Check the registration is for a centre relevant to the user.
            return $user->isRelevantCentre($registration->centre);
        });

        // When a registration is printed individually
        Gate::define('print-registration', function (User $user, Registration $registration) {
            return $user->isRelevantCentre($registration->centre);
        });
    }
}
