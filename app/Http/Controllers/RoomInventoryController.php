<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use App;
use App\Room;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class RoomInventoryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$rooms = Room::all();
		$ticket_count = 0;
		$item = 'None';
		$query = '';

		if($rooms->pluck('name') !== null)
		{
			$query = new App\TicketView;
			$_room = '';

			if(Input::has('notif'))
			{
				$_room = [ $this->sanitizeString(Input::get('notif')) ];
			}
			else
			{
				$_room = $rooms->pluck('name')->first();
			}

			$roominventory = App\RoomInventoryView::where('room','=',$_room)->pluck('item');

			$query = $query->whereIn('link',$roominventory)
					->tickettype('Complaint')
					->first();

			if(isset($query->link))
			{
				$item = $query->link;
			}
			else
			{
				$item = 'None';
			}

			$ticket_count = count($query);

			if(Request::ajax())
			{
				return json_encode([ 'ticket_link' => $item , 'ticket_count' => $ticket_count ]);
			}

		}

		return view('inventory.room.index')
			->with('rooms',$rooms)
			->with('ticket_count',$ticket_count)
			->with('ticket_link',$item);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Request::ajax())
		{
			$id = $this->sanitizeString($id);
			return json_encode(App\RoomInventoryView::where('room','=',$id)->get());
		}	
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
