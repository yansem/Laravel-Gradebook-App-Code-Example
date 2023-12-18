<?php

namespace App\Providers;

use App\Models\User;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //todo для этого можно завести таблицу с правами
        Gate::define('show-references', function (User $user) {
            return $user->role_id !== 3;
        });
        Gate::define('edit-references', function (User $user) {
            return $user->role_id === 1;
        });
        Gate::define('edit-lessons', function (User $user) {
            return $user->role_id !== 3;
        });
        Gate::define('edit-users', function (User $user) {
            return $user->role_id === 1;
        });
        Gate::define('show-students', function (User $user) {
            return $user->role_id === 1;
        });
        Gate::define('show-teachers', function (User $user) {
            return $user->role_id !== 3;
        });
        Gate::define('show-roles', function (User $user) {
            return $user->role_id === 1;
        });
        Gate::define('edit-settings', function (User $user) {
            return $user->role_id === 1;
        });
    }
}
