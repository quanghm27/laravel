<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card_manager extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $table = 'card_manager';

	protected $fillable = [
			'shop_id', 'card_id', 'ins_date'
	];

	public  $timestamps = false;
}
