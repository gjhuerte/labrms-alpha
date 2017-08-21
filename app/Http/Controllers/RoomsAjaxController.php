<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class RoomsAjaxController extends Controller {

	public function getRoomName($id)
	{
		$room = Room::find($id);
		return json_encode($room->name);
	}

}
