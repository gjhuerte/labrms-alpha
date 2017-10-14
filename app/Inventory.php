<?php
 
namespace App;

use Carbon\Carbon;
use DB;
use App\Ticket;
use App\ItemProfile;
use Illuminate\Database\Eloquent\Model;

class Inventory extends \Eloquent
{

  /**
  *
  * table name
  *
  */	
  protected $table = 'inventory';

  /**
  *
  * used for create method
  *
  */  
  public $fillable = [
    'itemtype',
    'brand',
    'model',
    'details',
    'warranty',
    'unit',
    'quantity',
    'profileditems'
  ];

  /**
  *
  * created_at and updated_at status
  *
  */
  public $timestamps = true;

  /**
  *
  * The attribute that used as primary key.
  *
  */
  protected $primaryKey = 'id';

  /**
  *
  * validation rules
  *
  */
  public static $rules = array(
  	'Item Type' => 'required|exists:Itemtype,id',
  	'Brand' => 'min:2|max:100',
  	'Model' => 'min:2|max:100',
  	'Details' => 'min:5|max:1000',
  	'Warranty' => 'min:5|max:100',
  	'Unit' => 'required',
  	'Quantity' => 'required|numeric',
  	'Profiled Items' => 'numeric'

  );

  /**
  *
  * update rules
  *
  */
	public static $updateRules = array(
		'Item Type' => 'required|min:5|max:100',
		'Brand' => 'min:2|max:100',
		'Model' => 'min:2|max:100',
		'Details' => 'min:5|max:1000',
		'Warranty' => 'min:5|max:100',
		'Unit' => 'numeric',
		'Quantity' => 'numeric',
		'Profiled Items' => 'numeric'
	);

  public function itemprofile()
  {
    return $this->hasMany('App\Itemprofile','inventory_id','id');
  }

  public function getBrandAttribute($value)
  {
    return ucwords($value);
  }

  public function getModelAttribute($value)
  {
    return ucwords($value);
  }

  public function getWarrantyAttribute($value)
  {
    return ucwords($value);
  }

  public function scopeType($query,$id)
  {
    return $query->where('itemtype_id','=',$id);
  }

  public function scopeBrand($query,$brand)
  {
    return $query->where('brand','=',$brand);
  }

  public function scopeModel($query,$model)
  {
    return $query->where('model','=',$model);
  }

  public function itemtype()
  {
    return $this->belongsTo('App\Itemtype','itemtype_id','id');
  }

  /**
  *
  * @param $_inventory array of inventory information
  * @param $_receipt array of receipt details
  *
  */
  public static function createRecord($_inventory,$_receipt)
  {

    /*
    |--------------------------------------------------------------------------
    |
    |   Instantiate inventory
    |
    |--------------------------------------------------------------------------
    |
    */
    $inventory;

    /*
    |--------------------------------------------------------------------------
    |
    |   Get the record
    |
    |--------------------------------------------------------------------------
    |
    */
    $inventory = Inventory::brand($_inventory['brand'])
          ->model($_inventory['model'])
          ->type($_inventory['itemtype'])
          ->first();


    /*
    |--------------------------------------------------------------------------
    |
    |   Checks if inventory details exists
    |   1 - Update Quantity
    |   2 - Create new record
    |
    |--------------------------------------------------------------------------
    |
    */
    if($inventory) 
    {

      /*
      |--------------------------------------------------------------------------
      |
      |   Update quantity
      |
      |--------------------------------------------------------------------------
      |
      */
      $inventory->quantity = $inventory->quantity + $_inventory['quantity'];
      $inventory->save();
    }
    else
    {

      /*  
      |--------------------------------------------------------------------------
      |
      |   Create new inventory
      |
      |--------------------------------------------------------------------------
      |
      */
      $inventory = new Inventory;
      $inventory->brand = $_inventory['brand'];
      $inventory->itemtype_id = $_inventory['itemtype'];
      $inventory->model = $_inventory['model'];
      $inventory->quantity = $_inventory['quantity'];
      $inventory->unit = $_inventory['unit'];
      $inventory->warranty = $_inventory['warranty'];
      $inventory->details = $_inventory['details'];
      $inventory->profileditems = 0;
      $inventory->save();
    }
    

    /*
    |--------------------------------------------------------------------------
    |
    |   Create a receipt
    |
    |--------------------------------------------------------------------------
    |
    */
    $receipt = new Receipt;
    $receipt->number = $_receipt['number'];
    $receipt->pono = $_receipt['ponumber'];
    $receipt->podate = Carbon::parse($_receipt['podate'])->toDateString();
    $receipt->invoiceno = $_receipt['invoicenumber'];
    $receipt->invoicedate = Carbon::parse($_receipt['invoicedate'])->toDateString();
    $receipt->fundcode = $_receipt['fundcode'];
    $receipt->inventory_id = $inventory->id;
    $receipt->save();

    return $inventory;

  }

  /**
  *
  * increment profiled items
  * @param $inventory_id accepts id
  * validate before using this function
  *
  */
  public static function addProfiled($inventory_id)
  {
		$inventory = Inventory::find($inventory_id);
		$inventory->profileditems = $inventory->profileditems + 1;
		$inventory->save();
  }


  /**
  *
  * decrement profiled items
  * decreases quantity
  * @param $inventory_id accepts id
  * validate before using this function
  *
  */
  public static function removeProfiled($inventory_id)
  {
		$inventory = Inventory::find($inventory_id);
		$inventory->quantity = $inventory->quantity - 1;
		$inventory->profileditems = $inventory->profileditems - 1;
		$inventory->save();
  }


  /**
  *
  * calls remove profiled
  * @param $inventory_id accepts id
  * validate before using this function
  *
  */
  public static function condemn($id)
  {
    DB::transaction(function() use($id){
  		$itemprofile = ItemProfile::findOrFail($id);

      /*
      |--------------------------------------------------------------------------
      |
      |   Calls removeProfiled function
      |
      |--------------------------------------------------------------------------
      |
      */
  		Inventory::removeProfiled($itemprofile->inventory_id);
      Ticket::condemnTicket($itemprofile->propertynumber);
  		$itemprofile->delete();
    });
  }

}
