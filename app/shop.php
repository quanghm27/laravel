<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class shop extends Model
{
    //Cau hinh cho model
    protected $table = 'shop';
    
    protected $fillable = ['name'];
    
    public $timestamps = false;
}
