<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bonus extends Model
{
    //Cau hinh cho model
    protected $table = 'bonus';
    
    protected $fillable = ['bonus_type','product_code','bonus_point', 'start_date', 'end_date'];
    
    public $timestamps = false;
}
