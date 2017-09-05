<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Validator;
use DB;
use Auth;
use Session;
use App\Ticket;
use App\Reservation;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class DashboardController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
    	if(Request::ajax())
    	{
    		if(Input::has('reservation'))
    		{
				return json_encode([ 
					'data' => Reservation::withInfo()
											->orderBy('created_at','desc')
											->get() 
					]);
    		}	
    	}

        if(Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2)
        {
            return view('dashboard.admin.index');
        } else
        {
            return view('dashboard.user.index');
        }
	}


}
