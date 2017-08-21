<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LaboratoryAssistantMiddleware
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
        if( ! (Auth::user()->accesslevel >= 0 Auth::user()->accesslevel <= 1) ) return redirect('dashboard');

        return $next($request);
    }
}
