<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyAdmin
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
        if (Auth::user()->role != User::ROLE_ADMIN) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect("/");
            }
        }

        return $next($request);
    }
}
