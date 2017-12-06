<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RouteAutoAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // check request for a user param; use #1 if there isn't one.
        $user_id = ($request->query('user')) ? $request->query('user') : 1;

        // Attempt login, with remember me cookie.
        $user = Auth::loginUsingId($user_id, true);

        if ($user) {
            return $next($request);
        } else {
            throw new Exception('Error logging in');
        }
    }
}
