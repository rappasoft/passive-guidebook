<?php

namespace App\Providers;

use App\Models\SocialCasino;
use App\Models\User;
use App\Policies\FaqPolicy;
use App\Policies\LibraryPolicy;
use App\Policies\NavigationPolicy;
use App\Policies\PostPolicy;
use App\Policies\PostStatusPolicy;
use App\Policies\ScheduledTaskPolicy;
use App\Policies\SocialCasinoPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use LaraZeus\Sky\Models\{Faq, Post, PostStatus, Tag, Library, Navigation};
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTask;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // System
        MonitoredScheduledTask::class => ScheduledTaskPolicy::class,
        MonitoredScheduledTaskLogItem::class => ScheduledTaskPolicy::class,
        User::class => UserPolicy::class,

        // Passive
        SocialCasino::class => SocialCasinoPolicy::class,

        // CMS
        Faq::class => FaqPolicy::Class,
        Post::class => PostPolicy::Class,
        PostStatus::class => PostStatusPolicy::Class,
        Tag::class => TagPolicy::Class,
        Library::class => LibraryPolicy::Class,
        Navigation::class => NavigationPolicy::Class,
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
