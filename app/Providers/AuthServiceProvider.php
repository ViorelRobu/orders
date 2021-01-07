<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('administrator', function ($user) {
            $role = $user->role->role_id;
            return $role == 1 ? true : false;
        });

        Gate::define('planificare', function ($user) {
            $role = $user->role->role_id;
            return ($role == 2 || $role == 1) ? true : false;
            return true;
        });

        Gate::define('productie', function ($user) {
            $role = $user->role->role_id;
            return ($role == 3 || $role == 1 || $role == 2) ? true : false;
        });

        Gate::define('sef_schimb', function ($user) {
            $role = $user->role->role_id;
            return ($role == 4 || $role == 3 || $role == 2 || $role == 1) ? true : false;
        });
    }
}
