<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class giao_dich extends Model
{
	//
	protected $table = 'giao_dich';
	
	protected $fillable = ['user_id','product_id'];

	public $timestamps = false;
}
