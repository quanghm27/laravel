<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    //
    protected $table = 'product';
    
    protected $fillable = ['name','description','price','quantity'];
    
    public $timestamps = false;
}
