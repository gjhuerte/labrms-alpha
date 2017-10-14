<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LaboratoryUsersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(! (Auth::user()->accesslevel >= 3 && Auth::user()->accesslevel <= 4) ) return redirect('dashboard');

        return $next($request);
    }
}
