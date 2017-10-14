<?php

namespace App;

use App\Inventory;
use App\RoomInventory;
use App\Room;
use App\ItemType;
use App\Pc;
use App\Ticket;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ItemProfile extends \Eloquent{
	use SoftDeletes;

	/**
	*
	* table name
	*
	*/	
	protected $table = 'itemprofile';

	/**
	*
	* primary key
	*
	*/
	protected $primaryKey = 'id';

	/**
	*
	*	fields to be set as date
	*
	*/
	protected $dates = ['deleted_at'];

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
	public $fillable = [
		'property_number',
		'serialid',
		'location',
		'datereceived',
		'status'
	];

	/**
	*
	* validation rules
	*
	*/
	public static $rules = array(
		'Property Number' => 'required|min:5|max:100|unique:itemprofile,propertynumber',
		'Serial Number' => 'required|min:5|max:100|unique:itemprofile,serialnumber',
		'Location' =>'required',
		'Date Received' =>'required|date',
		'Status' =>'required|min:5|max:50'

	);

	/**
	*
	* update rules
	*
	*/
	public static $updateRules = array(
		'Property Number' => 'min:5|max:100',
		'Serial Number' => 'min:5|max:100',
		'Location' =>'',
		'Date Received' =>'date',
		'Status' =>'min:5|max:50'

	);

	public function itemtype()
	{
		return $this->hasManyThrough('App\ItemType','App\Inventory','id','id');
	}

	/*
	*
	*	Foreign key referencing inventory table
	*
	*/
	public function inventory()
	{
		return $this->belongsTo('App\Inventory','inventory_id','id');
	}

	/*
	*
	*	Foreign key referencing roominventory table
	*
	*/
	public function roominventory()
	{
		return $this->hasOne('App\RoomInventory','item_id','id');
	}

	/*
	*
	*	Foreign key referencing receipt table
	*
	*/
	public function receipt()
	{
		return $this->belongsTo('App\Receipt','receipt_id','id');
	}

	/*
	*
	*	Foreign key referencing room table
	*
	*/
	public function room()
	{
		return $this->belongsToMany('App\Room','roominventory','item_id','room_id');
	}

	/*
	*
	*	Foreign key referencing ticket table
	*
	*/
	public function itemticket()
	{
		return $this->hasMany('App\ItemTicket','item_id','id');
	}

	/*
	*
	*	Foreign key referencing ticket table
	*
	*/
	public function ticket()
	{
		return $this->belongsToMany('App\Ticket','item_ticket','item_id','ticket_id');
	}

	public function scopeLocation($query,$location)
	{
		return $query->where('location','=',$location);	
	}

	/*
	*
	*	Limit the scope by propertynumber
	*	usage: ItemProfile::propertyNumber($propertynumber)->get()
	*
	*/
	public function scopePropertyNumber($query,$propertynumber)
	{
		return $query->where('propertynumber','=',$propertynumber);
	}

	public static function assignToRoom($item,$room)
	{

		$itemprofile = Itemprofile::find($item);
		$author = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
		$details = "$itemprofile->propertynumber assigned to $room->name by $author";
		$tickettype = 'Transfer';
		$ticketname = 'Transfer';
		/*
		|--------------------------------------------------------------------------
		|
		| 	Save
		|	1 - If existing update
		|	2 - If not create new record
		|
		|--------------------------------------------------------------------------
		|
		*/			

		$itemprofile->location = $room->name;
		if($itemprofile->deployment == null)
		{
			$itemprofile->deployment = Carbon::now();
			$ticketname = 'Deployment';
		}
		$itemprofile->save();

		if(count($itemprofile->room) > 0)
		{
			$itemprofile->room()->sync([ 'room_id'=>$room->id ]);
		}		
		else
		{
			$roominventory = new RoomInventory;
			$roominventory->room_id = $room->id;
			$roominventory->item_id = $item;
			$roominventory->save();
		}

		Ticket::generateEquipmentTicket($item,$tickettype,$ticketname,$details,$author,Auth::user()->id,null,'Closed');


	}

	/**
	*
	*	@param $propertynumber
	*	@param $serialnumber
	*	@param $location name
	*	@param $datereceived
	*	@param $inventory_id referencing inventory table
	*	@param $receipt_id referencing receipt table
	*
	*/
	public static function createRecord($propertynumber,$serialnumber,$location,$datereceived,$inventory_id,$receipt_id)
	{
		/**
		*
		*	Pass the parameters to transaction / stored procedure 
		*
		*/
		DB::transaction(function() use ($propertynumber,$serialnumber,$location,$datereceived,$inventory_id,$receipt_id)
		{
			/**
			*
			*	Create a record on item profile table
			*	@param $propertynumber
			*	@param $serialnumber
			*	@param $location name
			*	@param $datereceived
			*	@param $inventory_id referencing inventory table
			*	@param $receipt_id referencing receipt table
			*
			*/
			$itemprofile = ItemProfile::createItemProfile(
						$propertynumber,
						$serialnumber,
						$location,
						$datereceived,
						$inventory_id,
						$receipt_id
					);

			/*
			|--------------------------------------------------------------------------
			|
			| 	Create initial ticket
			|
			|--------------------------------------------------------------------------
			|
			*/
			ItemProfile::createProfilingTicket($itemprofile->id,$datereceived);
		    
			/*
			|--------------------------------------------------------------------------
			|
			| 	Set the location of the item
			|
			|--------------------------------------------------------------------------
			|
			*/
		    RoomInventory::createRecord($location,$itemprofile->id);

			/*
			|--------------------------------------------------------------------------
			|
			| 	Add 1 to profiled items count
			|	Used to check if how many items are not yet profiled
			|	Located in inventory table
			|
			|--------------------------------------------------------------------------
			|
			*/
		    Inventory::addProfiled($inventory_id);
		});
	}

	/*
	*
	*	Create a profiling ticket
	*	Send item id from create record to this
	*
	*/
	public static function createProfilingTicket($item_id,$datereceived)
	{
		$fullname = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname; 
		$datereceived = Carbon::parse($datereceived)->toDateString();
		$details = "Equipment profiled on ".$datereceived. " by ". $fullname . ". ";
		$tickettype = 'receive';
		$ticketname = 'Equipment Profiling';
		$staffassigned = Auth::user()->id;
		$ticket_id = null;
		$status = 'Closed';

		/*
		|--------------------------------------------------------------------------
		|
		| 	Calls the function generate equipment ticket
		|
		|--------------------------------------------------------------------------
		|
		*/
		Ticket::generateEquipmentTicket($item_id,$tickettype,$ticketname,$details,$fullname,$staffassigned,$ticket_id,$status);
	}

	/**
	*
	*	@param $propertynumber
	*	@param $serialnumber
	*	@param $location name
	*	@param $datereceived
	*	@param $inventory_id referencing inventory table
	*	@param $receipt_id referencing receipt table
	*
	*/
	public static function createItemProfile($propertynumber,$serialnumber,$location,$datereceived,$inventory_id,$receipt_id)
	{
		$datereceived = Carbon::parse($datereceived)->toDateString();
		$itemprofile = new ItemProfile;
		$itemprofile->propertynumber = $propertynumber;
		$itemprofile->serialnumber = $serialnumber;
		$itemprofile->location = $location;
		$itemprofile->datereceived = $datereceived;
		$itemprofile->status = 'working';
		$itemprofile->inventory_id = $inventory_id;
		$itemprofile->receipt_id = $receipt_id;
		$itemprofile->profiled_by = Auth::user()->firstname . " " . Auth::user()->middlename . " " .Auth::user()->lastname;
		$itemprofile->save();	

		/*
		|--------------------------------------------------------------------------
		| return collection of profiled item
		|--------------------------------------------------------------------------
		|
		*/
		return $itemprofile;
	}

	/**
	*
	*	@param $item accepts item type name
	*	@return returns the list of propertynumber of type
	*
	*/
	public static function getUnassignedPropertyNumber($item)
	{
		/*
		*
		*	Initialize item profile
		*
		*/
		$itemprofile;

		/**
		*
		*	queries all the itemtypes 
		*	select only the top 
		*	@return id
		*
		*/
		$itemtype = ItemType::type($item)->select('id')->first();

		/**
		*
		*	after selecting the itemtype where the item belongs
		*	pluck all the id on the inventory
		*	where the item type belongs
		*
		*/
		$id = Inventory::where('itemtype_id','=',$itemtype->id)->select('id')->pluck('id');
		//switch case items
		switch( $item ){

			/*
			|--------------------------------------------------------------------------
			| System Unit
			|--------------------------------------------------------------------------
			|
			*/
			case 'System Unit':
			$itemprofile = ItemProfile::getListOfItems($id,'systemunit_id');
			break;


			/*
			|--------------------------------------------------------------------------
			| Monitor
			|--------------------------------------------------------------------------
			|
			*/
			case 'Display':
			$itemprofile = ItemProfile::getListOfItems($id,'monitor_id');
			break;

			/*
			|--------------------------------------------------------------------------
			| AVR
			|--------------------------------------------------------------------------
			|
			*/
			case 'AVR':
			$itemprofile = ItemProfile::getListOfItems($id,'avr_id');
			break;

			/*
			|--------------------------------------------------------------------------
			| Keyboard
			|--------------------------------------------------------------------------
			|
			*/
			case $item == 'Keyboard':
			$itemprofile = ItemProfile::getListOfItems($id,'keyboard_id');
			break;
		}

		/*
		*
		*	return collection of item profile
		*
		*/
		return json_encode($itemprofile);
	}

	/**
	*
	*	@param $id accepts item profile id
	*	@param $name filter the pluck returned
	*	@return collection of $name from $id found
	*
	*/
	public static function getListOfItems($id,$name){
	$itemprofile = ItemProfile::whereIn('inventory_id',$id)
	              ->whereNotIn('id',Pc::select($name)->pluck($name))
	              ->select('propertynumber')
	              ->get();
	return $itemprofile;
	}

	/**
	*
	*	@return query for unassembled item 	
	*
	*/
	public function scopeUnassembled($query)
	{
		return $query->whereNotIn('id',Pc::whereNotNull('systemunit_id')->pluck('systemunit_id'))
					->whereNotIn('id',Pc::whereNotNull('monitor_id')->pluck('monitor_id'))
					->whereNotIn('id',Pc::whereNotNull('keyboard_id')->pluck('keyboard_id'))
					->whereNotIn('id',Pc::whereNotNull('avr_id')->pluck('avr_id'));
	}

	/**
	*
	*	@param itemprofile id
	*	@return itemprofile information
	*
	*/
	public static function setItemStatus($id,$status)
	{
		$itemprofile = ItemProfile::find($id);
		$itemprofile->status = $status;
		$itemprofile->save();
		return $itemprofile;
	}

	/**
	*
	*	@param item id
	*	@param room id
	*
	*/
	public static function setLocation($_item,$_room)
	{
		try
		{
			/*
			*	get the item profile
			*	assign to $item variable
			*/
			$item = ItemProfile::find($_item);

			/*
			*	set item location
			*	location is the room name
			*/
			$item->location  = $_room;

			/*
			*	get the room information
			*	link room and item
			*/
			$room = Room::location($_room)->first();
			$item->room()->sync([$room->id]);

			/*
			*
			*	create a transfer ticket
			*
			*/
			$details = "Items location has been set to $_room";
			$staffassigned = Auth::user()->id;
			$author = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
			Ticket::generateEquipmentTicket(
						$item->id,
						'Transfer',
						'Set Item Location',
						$details,
						$author,
						$staffassigned,
						null,
						'Closed'
					);
			$item->save();

		} 
		catch(Exception $e)
		{

			/*
			*	if no room inventory found
			*	create room inventory
			*	room inventory links item and room
			*/
			RoomInventory::createRecord($room,$item);
		}
	}

	public function getIDFromPropertyNumber($propertynumber)
	{
		return ItemProfile::propertyNumber($propertynumber)->pluck('id');
	}

}
