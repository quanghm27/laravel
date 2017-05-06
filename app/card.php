<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $table = 'Card';
	
	protected $fillable = [
			'guest_name', 'phone_number', 'points','card_code'
	];

	public  $timestamps = false;	
}
