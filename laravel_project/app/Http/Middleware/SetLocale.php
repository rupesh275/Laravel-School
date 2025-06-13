<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  \$request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  \$next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request \$request, Closure \$next)
    {
        // Logic to determine locale:
        // 1. From authenticated user's preference (e.g., user->locale)
        // 2. From session
        // 3. From browser language (Accept-Language header)
        // 4. Default from config

        // Example:
        // if (Auth::check() && Auth::user()->locale) {
        //     App::setLocale(Auth::user()->locale);
        // } elseif (Session::has('locale')) {
        //    App::setLocale(Session::get('locale'));
        // } else {
        //     // You might want to parse \$request->server('HTTP_ACCEPT_LANGUAGE') here
        //     App::setLocale(config('app.locale')); // Default locale
        // }

        // For now, just set to default, actual logic will be more complex based on CI project
        App::setLocale(config('app.locale'));


        return \$next(\$request);
    }
}
