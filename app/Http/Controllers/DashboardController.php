<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Validator;
use DB;
use Auth;
use Session;
use App\Ticket;
use App\TicketView;
use App\User;
use Mail;
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
    			$reservation = $this->sanitizeString(Input::get("reservation"));

                if( Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2 )
                {
        			if(strcmp($reservation , 'All') == 0)
        			{

                        $start = Carbon::now()->startOfDay();
                        $end = Carbon::parse(Reservation::thirdWorkingDay(Carbon::now()))->endOfDay();

    					return json_encode([ 
    						'data' => Reservation::with('user')
                                                    ->where(function($query) use ($start,$end ) {

                                                        $query->whereBetween('timein',[ $start , $end ])
                                                        ->orWhereBetween('timein',[ $start , $end ]);
                                                    })
                                                    // ->where(function($query){
                                                    //     $query->orWhere('approval','=',0)
                                                    //     ->orWhere('approval','=',1);
                                                    // })
                                                    ->unclaimed()
                                                    ->withInfo()
    												->orderBy('created_at','desc')
    												->get() 
    					]);
        			}
                }

                $date = Carbon::now()->subDays(1)->format('Y-m-d H:i');

				return json_encode([ 
					'data' => Reservation::with('user')
                                            ->where('timein','>', $date )
                                            ->withInfo()
											->user(Auth::user()->id)
											->orderBy('created_at','desc')
											->get() 
				]);
    		}	


    		if(Input::has('ticket'))
    		{
    			$ticket = $this->sanitizeString(Input::get("ticket"));

                if( Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2 )
                {
        			if(strcmp($ticket,'All') == 0)
        			{

                        $start = Carbon::now()->startOfDay();
                        $end = Carbon::now()->endOfDay();

    					return json_encode(
    						TicketView::whereBetween('date',[ $start , $end ])
                                        ->orWhere(function($query){
                                            $query->where('status','=','Open')
                                                    ->where('tickettype' ,'=' , 'Complaint');
                                        })
                                        ->orderBy('id','desc')
    							         ->get()
    					);
        			}
                }

                return json_encode(
                    TicketView::self()
                        ->orderBy('date','desc')
                        ->get()
                );
    		}
    	}

        if(Input::has('lentitems'))
        {
            $lentitems = $this->sanitizeString(Input::get("lentitems"));

            if( Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 || Auth::user()->accesslevel == 2 )
            {
                if(strcmp($lentitems,'All') == 0)
                {

                    return json_encode([
                    
                        'data' => []
                        
                    ]);
                }
            }

            return json_encode([
            
                'data' => []
            
            ]);
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
