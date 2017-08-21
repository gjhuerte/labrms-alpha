<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class FacultyMiddleware
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
        if(Auth::user()->accesslevel != 3 ) return redirect('dashboard');

        return $next($request);
    }
}
