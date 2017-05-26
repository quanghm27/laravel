<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bonus_detail extends Model
{
    //Cau hinh cho model
    protected $table = 'bonus_detail';
    
    protected $fillable = ['bonus_id','product_code', 'bonus_point'];
    
    public $timestamps = false;
}
