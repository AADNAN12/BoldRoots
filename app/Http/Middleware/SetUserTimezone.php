<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetUserTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Si le fuseau horaire est déjà dans la session, on l'utilise
        if (session()->has('user_timezone')) {
            date_default_timezone_set(session('user_timezone'));
        } else {
            // Par défaut, on utilise le fuseau horaire de l'application
            $timezone = config('app.timezone', 'UTC');
            session(['user_timezone' => $timezone]);
            date_default_timezone_set($timezone);
        }

        return $next($request);
    }
}
