<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Spark\Events\SubscriptionCreated;
use Spark\Events\SubscriptionUpdated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        /*
         * This event is dispatched when a subscription becomes active. The event’s public properties include $billable and $subscription.
         */
        Event::listen(function (SubscriptionCreated $event) {

        });

        /*
         * This event is dispatched when a subscription is changed. Possible changes include plan changes, quantity changes, pausing a subscription, or resuming a subscription. The event’s public properties include $billable and $subscription.
         */
        Event::listen(function (SubscriptionUpdated $event) {
            // TODO: How to disable things when a sub is downgraded from 1 to 2
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
