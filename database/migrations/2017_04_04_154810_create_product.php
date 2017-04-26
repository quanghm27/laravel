<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	// Tao table product
    	schema::create('product',function(Blueprint $table)
    	{
    		$table -> increments('id');
    		$table -> char('name', 20);
    		$table -> char('description', 200);
    		$table -> integer('price');
    		$table -> integer('quantity');
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
    	Schema::drop('product');
    }
}
