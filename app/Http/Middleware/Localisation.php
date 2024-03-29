<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Illuminate\Http\Request;

class Localisation
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
        App::setLocale($request->cookie('locale', App::getLocale()));

        return $next($request);
    }
}
