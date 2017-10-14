<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LaboratoryHeadMiddleware
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
        if( Auth::user()->accesslevel != 0 ) return redirect('dashboard');

        return $next($request);
    }
}
