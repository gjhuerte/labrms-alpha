<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App\Room;
use App\RoomSoftware;
use App\Software;
use App\SoftwareLicense;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class SoftwareController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		if(Request::ajax())
		{
			return json_encode(['data'=>Software::with('roomsoftware.room')
													->get()
							]);
		}

		return view('software.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('software.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$name = $this->sanitizeString(Input::get('name'));
		$company = $this->sanitizeString(Input::get('company'));
		$licensetype = $this->sanitizeString(Input::get('licensetype'));
		$softwaretype = $this->sanitizeString(Input::get('softwaretype'));
		$minrequirement = $this->sanitizeString(Input::get('minrequirement'));
		$maxrequirement = $this->sanitizeString(Input::get('maxrequirement'));

		$validator = Validator::make([
				'Software Name' => $name,
				'Software Type' => $softwaretype,
				'License Type' => $licensetype,
				'company' => $company,
				'Minimum System Requirement' => $minrequirement,
				'Recommended System Requirement' => $maxrequirement,
		],Software::$rules);

		if($validator->fails())
		{
			return redirect('software/create')
				->withInput()
				->withErrors($validator);
		}

		$software = new Software;
		$software->softwarename = $name;
		$software->company = $company;
		$software->licensetype = $licensetype;
		$software->softwaretype = $softwaretype;
		$software->minsysreq = $minrequirement;
		$software->maxsysreq = $maxrequirement;
		$software->save();

		Session::flash('success-message','Software listed');
		return redirect('software');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return view('software.edit')
			->with('software',Software::find($id));
	}

	public function assign($id)
	{

		$room = Room::lists('name','id');
		return view('software.assign')
			->with('room',compact('room'))
			->with('software',Software::find($id));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$id = $this->sanitizeString($id);
		$name = $this->sanitizeString(Input::get('name'));
		$company = $this->sanitizeString(Input::get('company'));
		$licensetype = $this->sanitizeString(Input::get('licensetype'));
		$softwaretype = $this->sanitizeString(Input::get('softwaretype'));
		$licensekey = $this->sanitizeString(Input::get('licensekey'));
		$multiple = $this->sanitizeString(Input::get('multiple'));
		$minrequirement = $this->sanitizeString(Input::get('minrequirement'));
		$maxrequirement = $this->sanitizeString(Input::get('maxrequirement'));

		if($multiple == "on")
		{
			$multiple = 1;
		}

		$validator = Validator::make([
				'Software Name' => $name,
				'Software Type' => $softwaretype,
				'License Type' => $licensetype,
				'company' => $company,
				'Minimum System Requirement' => $minrequirement,
				'Recommended System Requirement' => $maxrequirement,
			],Software::$rules);

		$validator = Validator::make([
			'Product Key' => 'licensekey'
		],SoftwareLicense::$updateRules);

		if($validator->fails())
		{
			return redirect("software/$id/edit")
				->withInput()
				->withErrors($validator);
		}

		$software = Software::find($id);
		$software->softwarename = $name;
		$software->company = $company;
		$software->licensetype = $licensetype;
		$software->softwaretype = $softwaretype;
		$software->minsysreq = $minrequirement;
		$software->maxsysreq = $maxrequirement;
		$software->save();
		
		Session::flash('success-message','Software updated');
		return redirect('software');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Request::ajax())
		{
			try{
				$software = Software::find($id);
				$roomsoftware = $software->room()->detach();

				foreach($software->softwarelicense as $license){
					$license->delete();
				}

				$software->forcedelete();
				return json_encode('success');
			}catch (Exception $e){}
		}
		Session::flash('success-message','Software deleted');
		return redirect('software');
	}

	public function restore($id){
		$software = Software::onlyTrashed()->where('id',$id)->first();
		$software->restore();
		Session::flash('success-message','Software restored');
		return redirect('software/view/restore');
	}

	public function assignSoftwareToRoom()
	{
		if(Request::ajax()){
			$id = $this->sanitizeString(Input::get('id'));
			$room = Input::get('room');
			foreach($room as $room)
			{
				try{
					$room = $this->sanitizeString($room);
					$room = Room::where('name','=',$room)->first();
					$roomsoftware = new RoomSoftware;
					$roomsoftware->software_id = $id;
					$roomsoftware->room_id = $room->id;
					$roomsoftware->save();
				} catch(Exception $e) {
					return json_encode('error');
				}
			}

			return json_encode('success');
		}

		return redirect('software');
	}

	public function removeSoftwareFromRoom($id,$room)
	{
		if(Request::ajax())
		{
			try{

				$roomsoftware = RoomSoftware::where('software_id','=',$id)
											->where('room_id','=',$room)
											->delete();
				return json_encode('success');

			} catch (Exception $e) { return json_encode('error'); }

		}


		$roomsoftware = RoomSoftware::where('software_id','=',$id)->where('room_id','=',$room)->first();
		$roomsoftware->delete();

		Session::flash('success-message','Software removed from room');
		return redirect('software');
	}

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
