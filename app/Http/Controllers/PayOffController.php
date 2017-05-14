<?php

namespace App\Http\Controllers;

use App\Card;
use App\product;
use Illuminate\Http\Request;

class PayOffController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//
		return view ( 'admin\payOff.index' );
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
		// $product = product::findOrFail($request -> productId);
		// $user = user::findOrFail($request -> userId);
		
		// $payOff = new giao_dich();
		// $payOff -> user_id = $user->id;
		// $payOff -> product_id = $product->id;
		// $payOff -> save();
		
		// $user -> points += 100;
		// $user -> save();
		// ;
		// $product -> quantity -= $request -> quantity;
		// $product -> save();
		$card = new Card ();
		$product = new product ();
		
		$card = Card::Where ( 'card_code', $request->cardCode )->get ()->toArray ();
		
		if ($card == null) {
			$jsonString = createResMs ( "1", "Card not exist", null );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
	}
	/**
	 * Method to payOff
	 *
	 * @param Request $request        	
	 * @return unknown
	 */
	public function postPayOff(Request $request) {
		
		// Check exist Card
		if (! Card::Where ( 'card_code', '=', $request->cardCode )->exists ()) {
			$code = array (
					'cardCode' => $request->cardCode 
			);
			$jsonString = createResMs ( ERR_NOT_EXIST_CARD_CODE, ERR_NOT_EXIST_CARD_MSG, $code );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		// Check exist products
		$productArr = $request ['products'];
		$notExistIdArr = array ();
		
		foreach ( $productArr as $item ) {
			// Querry to check whether Id is in DB.
			if (! product::Where ( 'id', '=', $item ['productId'] )->exists ()) {
				// Store Id that not exist to an array.
				$data = new \stdClass ();
				$data->productId = $item ['productId'];
				array_push ( $notExistIdArr, $data );
			}
		}
		
		if ($notExistIdArr != null) {
			// Throw error when error product Id not null.
			$jsonString = createResMs ( ERR_NOT_EXIST_PRODUCT_CODE, ERR_NOT_EXIST_PRODUCT_MSG, $notExistIdArr );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		$jsonString = createResMs(SUCCESS_CODE, SUCCESS_MSG, null);
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
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
}
// Success case
define ( 'SUCCESS_CODE', '0' );
define ( 'SUCCESS_MSG', 'Success' );

// Error case for create card
// Guest name
define ( 'ERR_REQUIRED_NAME_CODE', '1' );
define ( 'ERR_REQUIRED_NAME_MSG', 'Empty guest name' );
define ( 'ERR_LENGTH_NAME_CODE', '2' );
define ( 'ERR_LENGTH_NAME_MSG', 'Guest name must less than 20 character' );
// Phone number
define ( 'ERR_REQUIRED_PHONE_CODE', '3' );
define ( 'ERR_REQUIRED_PHONE_MSG', 'Empty phone number' );
define ( 'ERR_NUMERIC_PHONE_CODE', '4' );
define ( 'ERR_NUMERIC_PHONE_MSG', 'Phone number must be numeric' );
define ( 'ERR_LENGTH_PHONE_CODE', '5' );
define ( 'ERR_LENGTH_PHONE_MSG', 'Phone number must be 9 or 10 character' );
define ( 'ERR_DUPLICATE_PHONE_CODE', '6' );
define ( 'ERR_DUPLICATE_PHONE_MSG', 'Duplicate phone number' );

// Error case for pay off
define ( 'ERR_NOT_EXIST_CARD_CODE', '7' );
define ( 'ERR_NOT_EXIST_CARD_MSG', 'Card not exist' );

define ( 'ERR_NOT_EXIST_PRODUCT_CODE', '8' );
define ( 'ERR_NOT_EXIST_PRODUCT_MSG', 'Product not exist' );

// header json

define ('JSON' , ' "Content-Type", "application/json" ' );

function createResMs($status, $message, $data) {
	$json = (array (
			'status' => $status,
			'message' => $message,
			'data' => $data
	));

	$jsonString = json_encode ( $json );

	return $jsonString;
}