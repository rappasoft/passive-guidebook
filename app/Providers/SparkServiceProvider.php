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
            // https://spark.laravel.com/docs/spark-stripe/plans#determining-plan-eligibility

            // Check to make sure when upgrading from trial
            // Tier 1 only allows

            if ($plan->name == \App\Models\Plan::TIER_1_NAME) {
                // No bank integration
                // TODO

                // 1 savings account
                // TODO

                // 5 dividend stocks
                // TODO

                // 10 social casinos
                if ($billable->activeSocialCasinos()->count() > 10) {
                    throw ValidationException::withMessages([
                        'plan' => 'This plan only allows for 10 social casinos and you have ' . $billable->activeSocialCasinos()->count() . '. Please mark ' . ($billable->activeSocialCasinos()->count() - 10) . ' social casinos as unused to continue or subscribe to tier for unlimited everything.'
                    ]);
                }

                // No custom sources
                // TODO
            }

            // Tier 2 allows everything
        });
    }
}
