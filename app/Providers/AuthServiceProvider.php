<?php

namespace App\Providers;

use App\Models\SocialCasino;
use App\Models\User;
use App\Policies\ScheduledTaskPolicy;
use App\Policies\SocialCasinoPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTask;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        MonitoredScheduledTask::class => ScheduledTaskPolicy::class,
        SocialCasino::class => SocialCasinoPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user) {
            return $user->isSuperAdmin() ? true : null;
        });

        Gate::define('viewPulse', function (User $user) {
            return $user->isSuperAdmin();
        });
    }
}
