<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class MaintenanceActivityAjaxController extends Controller {

	public function getAllMaintenanceActivity()
	{

		return json_encode(['data'=>MaintenanceActivity::all()]);
	}

	public function getPreventiveMaintenanceActivity()
	{
		return json_encode(MaintenanceActivity::where('type','preventive')->select('problem')->get());
	}

	public function getCorrectiveMaintenanceActivity()
	{
		return json_encode(MaintenanceActivity::where('type','corrective')->select('problem')->get());
	}
}
