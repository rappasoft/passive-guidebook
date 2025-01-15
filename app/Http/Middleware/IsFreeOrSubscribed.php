<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spark\GuessesBillableTypes;
use Spark\Spark;

class IsFreeOrSubscribed
{
    use GuessesBillableTypes;

    /**
     * Verify the incoming request's user has a subscription.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next, ?string $billableType = null, ?string $plan = null)
    {
        if ($request->user()->isFree()) {
            return $next($request);
        }

        $billableType = $billableType ?: $this->guessBillableType();

        if ($this->subscribed($request, $billableType, $plan)) {
            return $next($request);
        }

        $redirect = $this->redirect($billableType);

        if ($request->header('X-Inertia')) {
            return Inertia::location($redirect);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response('Subscription Required.', 402);
        }

        return redirect($redirect);
    }

    /**
     * Determine if the given user is subscribed to the given plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @param  string  $plan
     * @param  bool  $defaultSubscription
     * @return bool
     */
    protected function subscribed($request, $type, $plan)
    {
        if (! $billable = Spark::resolveBillable($type, $request)) {
            return false;
        }

        $subscription = $billable->subscription();

        if ($plan && (! $subscription || $subscription->stripe_price != $plan)) {
            return false;
        }

        return ($subscription && $subscription->active()) ||
            $billable->onGenericTrial();
    }

    /**
     * Get the redirect location.
     *
     * @return string
     */
    protected function redirect(string $billableType)
    {
        $redirect = '/'.config('spark.path');

        if ($billableType != 'user') {
            $redirect .= '/'.$billableType;
        }

        return $redirect;
    }
}
