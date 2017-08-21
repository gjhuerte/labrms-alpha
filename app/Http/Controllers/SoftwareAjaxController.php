<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\Software;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class SoftwareAjaxController extends Controller {

	public function getAllSoftwareName()
	{
		if(Request::ajax())
		{
			$software = Software::select('id','softwarename as name')->get();
			return json_encode($software);
		}
	}

	public function getAllSoftwareTypes()
	{
		if(Request::ajax()){
			return json_encode(Software::$types);
		}
	}

	public function getAllLicenseTypes()
	{
		if(Request::ajax())
		{
			return json_encode([
				'Proprietary license',
				'GNU General Public License',
				'End User License Agreement (EULA)',
				'Workstation licenses',
				'Concurrent use license',
				'Site licenses',
				'Perpetual licenses',
				'Non-perpetual licenses',
				'License with Maintenance'
			]);
		}
	}

}
