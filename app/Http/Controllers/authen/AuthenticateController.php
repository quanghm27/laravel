<?php

namespace App\Http\Controllers\authen;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use App\User;
use function App\Http\Controllers\createResMs;

class AuthenticateController extends Controller {
	/**
	 * constructor
	 */
	public function __construct() {
		// Apply the jwt.auth middleware to all methods in this controller
		// except for the authenticate method. We don't want to prevent
		// the user from retrieving their token if they don't already have it
		$this->middleware ( 'jwt.auth', [ 
				'except' => [ 
						'login' 
				] 
		] );
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//
		echo 'eh';
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request        	
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request        	
	 * @param int $id        	
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}
	
	/**
	 * check login function
	 *
	 * @param $request Request
	 *        	from client
	 */
	public function login(Request $request) {
		
		// $user -> email = $request -> email;
		// $user -> password = $request -> password;
		
		// $user = User::findOrFail($request -> email);
		
		// return response()->json($user);
		$credentials = $request->only ( 'email', 'password' );
		
		
		
		// verify the credentials and create a token for the user
		if (! $token = JWTAuth::attempt ( $credentials )) {
			
			
			$jsonString = createResMsLogin ( INVALID_CREDENTIALS_CODE, INVALID_CREDENTIALS_MSG, null );
		} else {
			$userId = User::Where('email', '=', $request->email)->value('id');
			$userName = User::Where('email', '=', $request->email)->value('name');
			
			$data = array(
					'shopId' => $userId,
					'shopName'=> $userName
			);
			$jsonString = createResMsLogin ( SUCCESS_CODE, SUCCESS_MSG, $data );
		}
		
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
	
	public function register() {
	}
}

// Success case
define ( 'SUCCESS_CODE', '0' );
define ( 'SUCCESS_MSG', 'Success' );

// Error case
define ( 'INVALID_CREDENTIALS_CODE', '10' );
define ( 'INVALID_CREDENTIALS_MSG', 'Invalid credentials' );

function createResMsLogin($status, $message, $data) {
	$json = (array (
			'status' => $status,
			'message' => $message,
			'data' => $data
	));

	$jsonString = json_encode ( $json );

	return $jsonString;
}
