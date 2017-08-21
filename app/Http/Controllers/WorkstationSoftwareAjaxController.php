<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class WorkstationSoftwareAjaxController extends Controller {

	public function getSoftwareInstalled($id)
	{
		if(Request::ajax())
		{
			$pcsoftware = Pcsoftware::leftJoin('software','pc_software.software_id','=','software.id')
										->leftJoin('softwarelicense','pc_software.softwarelicense_id','=','softwarelicense.id')
										->where('pc_software.pc_id',$this->sanitizeString($id))
										->select('softwarename','key')
										->get();
			return json_encode($pcsoftware);
		}
	}

}
