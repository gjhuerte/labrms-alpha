<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ReservationItems;
use App\ReservedItemsView;
use Carbon\Carbon;

class ReservationItemsView extends Model
{
	/**
	*
	*	table name
	*
	*/
    protected $table = 'reservationitems_v';

	/**
	*
	*	The attribute that used as primary key.
	*
	*/
	// protected $primaryKey = 'id';

	/**
	*
	*	created_at and updated_at status
	*
	*/
	public $timestamps = false;

	/**
	*
	*	@param itemtype accepts item type name
	*	@return query
	*
	*/
	public function scopeFilter($query,$itemtype)
	{		

		/*
		|--------------------------------------------------------------------------
		|
		| 	get item type id
		|
		|--------------------------------------------------------------------------
		|
		*/
		$itemtype_id = ItemType::type($itemtype)->pluck('id');
		
		/*
		|--------------------------------------------------------------------------
		|
		| 	fiter item type
		|
		|--------------------------------------------------------------------------
		|
		*/
		$query = $query->where('name','=',$itemtype);
		
		/*
		|--------------------------------------------------------------------------
		|
		| 	get the filter from reservation items
		|
		|--------------------------------------------------------------------------
		|
		*/
		$reservationitems = ReservationItems::where('itemtype_id',$itemtype_id)->get();

		
		/*
		|--------------------------------------------------------------------------
		|
		| 	instantiate reservation items
		|
		|--------------------------------------------------------------------------
		|
		*/
		$item_excluded = [];
		$item_included = [];
		
		/*
		|--------------------------------------------------------------------------
		|
		| 	add to excluded or included
		|
		|--------------------------------------------------------------------------
		|
		*/
		foreach($reservationitems as $reservationitems)
		{
			if(isset($reservationitems->included))
			{
				try
				{
					if($reservationitems->included != "")
					{
						/*
						|--------------------------------------------------------------------------
						|
						| 	separate each item
						|
						|--------------------------------------------------------------------------
						|
						*/
						$items = explode(',',$reservationitems->included);

						/*
						|--------------------------------------------------------------------------
						|
						| 	check if has content
						|
						|--------------------------------------------------------------------------
						|
						*/
						if(count($items) > 0 && $items != "")
						{
							foreach($items as $item)
							{
								/*
								|--------------------------------------------------------------------------
								|
								| 	add to array list
								|
								|--------------------------------------------------------------------------
								|
								*/
								array_push($item_included,$item);
							}
						}
					}
				}
				catch(Exception $e)
				{

				}
			}

			if(isset($reservationitems->excluded))
			{
				try
				{	
					if($reservationitems->excluded != "")
					{

						/*	
						|--------------------------------------------------------------------------
						|
						| 	separate each item
						|
						|--------------------------------------------------------------------------
						|
						*/
						$items = explode(',',$reservationitems->excluded);

						/*
						|--------------------------------------------------------------------------
						|
						| 	check if has content
						|
						|--------------------------------------------------------------------------
						|
						*/
						if(count($items) > 0)
						{
							foreach($items as $item)
							{
								/*
								|--------------------------------------------------------------------------
								|
								| 	add to array list
								|
								|--------------------------------------------------------------------------
								|
								*/
								array_push($item_excluded,$item);
							}
						}
					}
				}
				catch(Exception $e)
				{

				}
			}
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if array has content
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(count($item_excluded) > 0)
		{		

			/*
			|--------------------------------------------------------------------------
			|
			| 	check excluded item type
			|
			|--------------------------------------------------------------------------
			|
			*/
			$query = $query->whereNotIn('propertynumber',$item_excluded);
		} 

		/*
		|--------------------------------------------------------------------------
		|
		| 	check if array has content
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(count($item_included) > 0)
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	check included item type
			|
			|--------------------------------------------------------------------------
			|
			*/
			$query = $query->whereIn('propertynumber',$item_included);
		} 

		return $query;
	}

	/**
	*
	*	check if item is reserved
	*	@return unreserved items
	*
	*/
	public function scopeUnreserved($query,$date,$timein,$timeout)
	{	
		$reserveditems = ReservedItemsView::reserved($date,$timein,$timeout)
									->pluck('propertynumber');
		return $query->whereNotIn('propertynumber',$reserveditems);
	}

}
