<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\SupplyHistory;
use App\Supply;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class SupplyAjaxController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function getMouseBrandList()
	{
		if(Request::ajax())
		{
			$mouse = $this->sanitizeString(Input::get('term'));
			return json_encode(
			Supply::whereHas('itemtype',function($query){
									$query->where('name','=','Mouse');
							})
							->where('brand','like','%'.$mouse.'%')
							->pluck('brand')
			);
		}
	}


}
