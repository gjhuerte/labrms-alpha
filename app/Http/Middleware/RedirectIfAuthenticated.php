<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;

class RedirectIfAuthenticated
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
        if (Auth::guard($guard)->check()) {
        
            if(Auth::user()->status == 0 ){
                Session::flash('error-message','This account is not activated!');
                return redirect('logout');
            }
            
            return redirect('/');
        }

        // if (Auth::guest())
        // {
        //     if ($request->ajax())
        //     {
        //         return Response::make('Unauthorized', 401);
        //     }
        //     else
        //     {
        //         Session::flash('error-message','You need to login before accessing this page!');
        //         return redirect('login');
        //     }
        // }

        return $next($request);
    }
}
