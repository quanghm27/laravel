<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::create('user',function(Blueprint $table)
        {
        	$table -> increments('id');
        	$table->string('name');
        	$table->char('email',30)->unique();
        	$table->string('password');
        	$table -> integer('points');
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
    	schema::drop('user');
    }
}

