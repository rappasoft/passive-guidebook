<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsNotFree
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()->isFree()) {
            return $next($request);
        }

        return redirect('/dashboard');
    }
}
