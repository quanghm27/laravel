<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\user;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
    	Model::unguard();
    	
    	DB::table('user')->delete();
    	
    	$users = array(
    			['name' => 'MinhQuang','email' => 'quanghm27@gmail.com', 'password' => Hash::make('test00rn')],
    			['name' => 'LaravelShop','email' => 'laravel@gmail.com', 'password' => Hash::make('test00rn')],
    			['name' => 'shop12345','email' => 'my_shop@gmail.com', 'password' => Hash::make('test00rn')],
    			['name' => 'QuangHM','email' => 'test_mail@gmail.com', 'password' => Hash::make('test00rn')],
    	);
    	
    	// Loop through each user above and create the record for them in the database
    	foreach ($users as $user)
    	{
    		User::create($user);
    	}
    	
    	Model::reguard();
    }
}
