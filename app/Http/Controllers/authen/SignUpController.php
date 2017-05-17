<?php

namespace App\Http\Controllers\authen;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SignUpController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function postCreate(Request $req)
    {
    		$user = new User();
    		
    		$user->name = $req->name;
    		$user->email = $req->email;
    		$user->password = bcrypt($req->password);
        
    		$oldEmail = User::Where('email','=', $req->email)->exists();
    		
    		if ( $oldEmail ) {
    			$jsonString = createResMsLogin ( ERROR_EMAIL_EXIST_CODE, ERROR_EMAIL_EXIST_MSG, null );
    			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
    		}
    		
         	$user->save();
         	
         	$jsonString = createResMsLogin ( SUCCESS_CODE, SUCCESS_MSG, null );
         	return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
    }
}

// Success case
define ( 'SUCCESS_CODE', '0' );
define ( 'SUCCESS_MSG', 'Success' );

define ( 'ERROR_EMAIL_EXIST_CODE', '1' );
define ( 'ERROR_EMAIL_EXIST_MSG', 'Email is existed' );

function createResMsLogin($status, $message, $data) {
	$json = (array (
			'status' => $status,
			'message' => $message,
			'data' => $data
	));

	$jsonString = json_encode ( $json );

	return $jsonString;
}