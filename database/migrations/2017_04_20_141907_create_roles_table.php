<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
    	schema::create('roles',function(Blueprint $table)
    	{
    		$table -> increments('id');
    		$table -> integer('user_id');
    		$table -> char('role', 20);
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
    	schema::drop('roles');
    }
}
