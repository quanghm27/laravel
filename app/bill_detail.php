<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bill_detail extends Model
{
	//
	protected $table = 'bill_detail';

	protected $fillable = ['bill_id','product_id','product_quantity'];

	public $timestamps = false;
}
