<?php

namespace App\Http\Controllers;

use App\Card;
use App\product;
use Illuminate\Http\Request;
use App\User;
use App\bill;

class PayController extends Controller {
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
		
		$userId = $request->userId;
		$cardCode = $request->cardCode;
		$productArr = $request ['products'];
		
		// Check exist Card
		if (! Card::Where ( 'card_code', '=', $request->cardCode )->exists ()) {
			$code = array (
					'cardCode' => $request->cardCode 
			);
			$jsonString = createResMs ( ERR_NOT_EXIST_CARD_CODE, ERR_NOT_EXIST_CARD_MSG, $code );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		// Check exist products
		$notExistIdArr = array ();
		
		foreach ( $productArr as $item ) {
			// Querry to check whether Id is in DB.
			if (! product::Where ( 'code', '=', $item ['productCode'] )->exists ()) {
				// Store Id that not exist to an array.
				$data = new \stdClass ();
				$data->productCode = $item ['productCode'];
				array_push ( $notExistIdArr, $data );
			}
		}
		
		if ($notExistIdArr != null) {
			// Throw error when error product Id not null.
			$jsonString = createResMs ( ERR_NOT_EXIST_PRODUCT_CODE, ERR_NOT_EXIST_PRODUCT_MSG, $notExistIdArr );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		//TO-DO: calcutate and insert bill
		$total = calculate($productArr);
		insertOrder ($userId,$cardCode, $total);
		
		
		$jsonString = createResMs(SUCCESS_CODE, SUCCESS_MSG, null);
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
	
}
// Success case
define ( 'SUCCESS_CODE', '0' );
define ( 'SUCCESS_MSG', 'Success' );


// Error case for pay off
define ( 'ERR_NOT_EXIST_CARD_CODE', '7' );
define ( 'ERR_NOT_EXIST_CARD_MSG', 'Card not exist' );

define ( 'ERR_NOT_EXIST_PRODUCT_CODE', '8' );
define ( 'ERR_NOT_EXIST_PRODUCT_MSG', 'Product not exist' );

function createResMs($status, $message, $data) {
	$json = (array (
			'status' => $status,
			'message' => $message,
			'data' => $data
	));

	$jsonString = json_encode ( $json );

	return $jsonString;
}

function calculate ($productArr) {
	
	$total = 0;
	// loop all element of products list1
	foreach ( $productArr as $item ) {
		
		// Querry to check price
		$price =  product::Where ( 'code', '=', $item ['productCode'] )->value('price');
		
		$total += $price * $item['quantity'];
	}
	
	return $total;
}

function  insertOrder($userId, $cardCode, $total) {
	$user = User::findOrfail($userId);
	
	$bill = new bill();
	
	$bill->shop_id = $user->id;
	$bill->card_code = $cardCode;
	$bill->total =  $total;
	
	$bill->save();
}

function mode($modeId) {
	
}