<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Spark\Plan;
use Spark\Spark;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Spark::billable(User::class)->resolve(function (Request $request) {
            return $request->user();
        });

        Spark::billable(User::class)->authorize(function (User $billable, Request $request) {
            return $request->user() &&
                   $request->user()->id == $billable->id;
        });

        Spark::billable(User::class)->checkPlanEligibility(function (User $billable, Plan $plan) {
            //             if ($billable->socialCasinos()->count() > 5 && $plan->name == \App\Models\Plan::TIER_1_NAME) {
            //                 throw ValidationException::withMessages([
            //                     'plan' => 'You must upgrade plans to add more Social Casinos.'
            //                 ]);
            //             }

            // TODO
        });
    }
}
