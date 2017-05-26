<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bonus_manager extends Model
{
    //Cau hinh cho model
    protected $table = 'bonus_manager';
    
    protected $fillable = ['bonus_type','shop_id', 'start_date', 'end_date'];
    
    public $timestamps = false;
}
