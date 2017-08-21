<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;

class DashboardController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)
        {
            return view('dashboard.admin.index');
        } else
        {
            return view('dashboard.user.index');
        }
	}


}
