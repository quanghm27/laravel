<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bill extends Model
{
	//
	protected $table = 'bill';

	protected $fillable = ['shop_id','card_code','total'];

	public $timestamps = false;
}
