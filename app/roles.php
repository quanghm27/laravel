<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    //
	protected $table = 'roles';
	
	protected $fillable = ['role','user_id'];
	
	public $timestamps = false;
	
	public function users() {
	
		return $this->belongsToMany('App\user');
	}
}
