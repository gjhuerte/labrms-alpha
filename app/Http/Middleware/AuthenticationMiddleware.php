<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Auth;
use Illuminate\Support\Facades\Request;

class AuthenticationMiddleware
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
        if (Auth::guest())
        {
            if (Request::ajax())
            {
                return Response::make('Unauthorized', 401);
            }
            else
            {
                Session::flash('error-message','You need to login before accessing this page!');
                return redirect('login');
            }
        }
        
        if(Auth::user()->status == 0 ){
            Session::flash('error-message','This account is not activated!');
            return redirect('logout');
        }

        return $next($request);
    }
}
