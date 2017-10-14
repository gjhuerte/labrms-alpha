<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Session;
use Auth;
use Validator;
use App\User;
use App\Reservation;
use App\TicketView;
use Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class SessionsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('pagenotfound');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('login');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(Request::ajax()){
			$username = $this->sanitizeString(Input::get('username'));
			$password = $this->sanitizeString(Input::get('password'));
 		
			$user = array(	
				'username' => $username,
				'password' => $password
	 		);

			if(Auth::attempt($user))
			{
	 			return 'success';
	 		}else{
	 			return 'error';
	 		}
		}

		$username = $this->sanitizeString(Input::get('username'));
		$password = $this->sanitizeString(Input::get('password'));

 		$user = User::where('username','=',$username)->first();

 		if(count($user) == 0)
 		{
			Session::flash('error-message','Invalid login credentials');
			return redirect('login');
 		}

 		if($user->status == '0')
 		{

			Session::flash('error-message','Account Inactive. Contact the administrator to activate your account');
			return redirect('login');

 		}
 		
		$user = array(	
			'username' => $username,
			'password' => $password
 		);

		if(Auth::attempt($user))
		{
			return redirect('dashboard');
		}

		Session::flash('error-message','Invalid login credentials');
		return redirect('login');

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		$person = Auth::user();
		$reservation = Reservation::withInfo()->user(Auth::user()->id)->get()->count();
		$approved = Reservation::withInfo()->approved()->user(Auth::user()->id)->get()->count();
		$disapproved = Reservation::withInfo()->disapproved()->user(Auth::user()->id)->get()->count();
		$claimed = Reservation::where('status','=','claimed')->user(Auth::user()->id)->get()->count();
		$tickets = TicketView::self()->get()->count();
		$assigned = TicketView::staff(Auth::user()->id)->tickettype('Complaint')->count();
		$complaints = TicketView::self()->tickettype('Complaint')->count();
		return view('user.index')
			->with('person',$person)
			->with('reservation',$reservation)
			->with('tickets',$tickets)
			->with('approved',$approved)
			->with('disapproved',$disapproved)
			->with('complaints',$complaints)
			->with('assigned',$assigned)
			->with('claimed',$claimed);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit()
	{
		return view('user.edit');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$password = $this->sanitizeString(Input::get('password'));
		$newpassword = $this->sanitizeString(Input::get('newpassword'));

		$user = User::find(Auth::user()->id);

		$validator = Validator::make(
				[
					'Current Password'=>$password,
					'New Password'=>$newpassword
				],
				[
					'Current Password'=>'required|min:8|max:50',
					'New Password'=>'required|min:8|max:50'
				]
			);

		if( $validator->fails() )
		{
			return redirect()->back()
				->withInput()
				->withErrors($validator);
		}

		//verifies if password inputted is the same as the users password
		if(Hash::check($password,Auth::user()->password))
		{

			//verifies if current password is the same as the new password
			if(Hash::check($newpassword,Auth::user()->password)){
				Session::flash('error-message','Your New Password must not be the same as your Old Password');
				return redirect()->back()
					->withInput()
					->withErrors($validator);
			}else{

				$user->password = Hash::make($newpassword);
				$user->save();
			}
		}else{

			Session::flash('error-message','Incorrect Password');
			return redirect()->back()
				->withInput();
		}

		Session::flash('success-message','Password updated');
		return redirect()->back();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		//remove everything from session
		Session::flush();
		//remove everything from auth
		Auth::logout();
		return redirect('login');
	}

	public function getResetForm(){
		return view('user.reset');
	}

	public function reset(){
		
	}

}
