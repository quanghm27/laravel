<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiaoDichTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	// Tao table shop
    	schema::create('giao_dich',function(Blueprint $table)
    	{
    		$table -> increments('id');
    		$table -> integer('user_id');
    		$table -> integer('product_id');
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        schema::drop('giao_dich');
    }
}
