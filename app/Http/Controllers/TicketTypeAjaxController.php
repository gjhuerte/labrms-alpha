<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class TicketTypeAjaxController extends Controller {

	public function getAllTicketTypes()
	{
		return json_encode(Tickettype::select('type')->get()->sortBy('type'));
	}

}
