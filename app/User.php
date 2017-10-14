<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Hash;

class User extends \Eloquent implements Authenticatable {
	use SoftDeletes, AuthenticableTrait;

	/**
	*
	* table name
	*
	*/	
	protected $table  = 'user';

	/**
	*
	*	fields to be set as date
	*
	*/
	protected $dates = ['deleted_at'];

	/**
	*
	* primary key
	*
	*/
	protected $primaryKey = 'id';

	/**
	*
	* created_at and updated_at status
	*
	*/
	public $timestamps = true;

	/**
	*
	* used for create method
	*
	*/  
	protected $fillable = [
		'lastname',
		'firstname',
		'middlename',
		'username',
		'password',
		'contactnumber',
		'email',
		'type',
		'status',
		'accesslevel'
	];

	// public function getFirstNameAttribute($value)
	// {
	// 	return ucwords($value);
	// }

	// public function getMiddleNameAttribute($value)
	// {
	// 	return ucwords($value);
	// }

	// public function getLastNameAttribute($value)
	// {
	// 	return ucwords($value);
	// }

	// public function setFirstNameAttribute($value)
	// {
	// 	$this->attribute['firstname'] = ucwords($value);
	// }

	// public function setMiddleNameAttribute($value)
	// {
	// 	$this->attribute['middlename'] = ucwords($value);
	// }

	// public function setLastNameAttribute($value)
	// {
	// 	$this->attribute['lastname'] =  ucwords($value);
	// }

	/**
	*
	* not shown when querying
	*
	*/  
	protected $hidden = ['password','remember_token'];

	/**
	*
	* validation rules
	*
	*/
	public static $rules = array(
		'Username' => 'required_with:password|min:4|max:20|unique:User,username',
		'Password' => 'required|min:8|max:50',
		'First name' => 'required|between:2,100|string',
		'Middle name' => 'min:2|max:50|string',
		'Last name' => 'required|min:2|max:50|string',
		'Contact number' => 'required|size:11|string',
		'Email' => 'required|email'
	);

	/**
	*
	* update rules
	*
	*/
	public static $updateRules = array(
		'Username' => 'min:4|max:20',
		'Password' => 'min:6|max:50',
		'First name' => 'min:2|max:100|string',
		'Middle name' => 'min:2|max:50|string',
		'Last name' => 'min:2|max:50|string',
		'Contact number' => 'size:11|string',
		'email' => 'email'
	);

	public function reservation()
	{
		return $this->hasOne('App\Reservation','user_id');
	}

	public function itemprofile()
	{
		return $this->belongsToMany('App\ItemProfile','Reservation','user_id','item_id');
	}

	public function scopeAdmin($query)
	{
		return $query->where('accesslevel','=',0);
	}

	public function scopeStaff($query)
	{
		return $query->whereIn('accesslevel',[0,1,2]);
	}

	/**
	*
	*	@param $username 
	*	@param $password
	*	@param $lastname
	*	@param $firstname
	*	@param $middlename
	*	@param $contactnumber
	*	@param $email
	*	@param $type 
	*		0 - labhead
	*		1 - assistant
	*		2 - staff
	*		3 - faculty
	*		4 - student
	*	@return collection of user 
	*
	*/
	public static function createRecord($username,$password,$lastname,$firstname,$middlename,$contactnumber,$email,$type)
	{
		$user = new User;
		$user->lastname = $lastname;
		$user->firstname = $firstname;
		$user->middlename = $middlename;
		$user->username = $username;
		$user->contactnumber = $contactnumber;
		$user->email = $email;
		$user->password = Hash::make($password);
		$user->type = $type;
		$user->status = '1';
		if($type == 'assistant')
		$user->accesslevel = '1';
		if($type == 'staff')
		$user->accesslevel = '2';
		if($type == 'faculty')
		$user->accesslevel = '3';
		if($type == 'student')
		$user->accesslevel = '4';
		$user->save();
		return $user;
	}

	/**
	*
	*	@param $id 
	*	@param $username 
	*	@param $lastname
	*	@param $firstname
	*	@param $middlename
	*	@param $contactnumber
	*	@param $email
	*	@param $type 
	*		0 - labhead
	*		1 - assistant
	*		2 - staff
	*		3 - faculty
	*		4 - student
	*	@return collection of user 
	*
	*/
	public static function updateRecord($id,$username,$lastname,$firstname,$middlename,$contactnumber,$email,$type)
	{

		$user = User::find($id);
		$user->username = $username;
		$user->lastname = $lastname;
		$user->firstname = $firstname;
		$user->middlename = $middlename;
		$user->contactnumber = $contactnumber;
		$user->email = $email;
		$user->type = $type;
		$user->save();
		return $user;
	}
}
