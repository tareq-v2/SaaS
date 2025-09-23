<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\DashboardPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => DashboardPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for role-based access
        Gate::define('super_admin', function (User $user) {
            return $user->isSuperAdmin();
        });

        Gate::define('admin', function (User $user) {
            return $user->isAdmin() || $user->isSuperAdmin();
        });

        Gate::define('user', function (User $user) {
            return true; // All authenticated users
        });
    }
}
