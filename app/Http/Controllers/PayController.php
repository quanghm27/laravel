<?php

namespace App\Http\Controllers;

use App\Card;
use App\product;
use Illuminate\Http\Request;
use App\User;
use App\bill;
use App\bill_detail;

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
		$mode = $request->mode;
		
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
		$invalidQuantity = array ();
		
		foreach ( $productArr as $item ) {
			// Querry to check whether Id is in DB.
			if (! product::Where ( 'code', '=', $item ['productCode'] )->exists ()) {
				// Store Id that not exist to an array.
				$data = new \stdClass ();
				$data->productCode = $item ['productCode'];
				array_push ( $notExistIdArr, $data );
			}
			
			// check quantity in database
			$quantity = product::Where ( 'code', '=', $item ['productCode'] )->value ( 'quantity' );
			
			if ($quantity < $item ['quantity']) {
				$data = new \stdClass ();
				$data->productCode = $item ['productCode'];
				array_push ( $invalidQuantity, $data );
			}
		}
		
		if ($notExistIdArr != null) {
			// Throw error when error product Id not null.
			$jsonString = createResMs ( ERR_NOT_EXIST_PRODUCT_CODE, ERR_NOT_EXIST_PRODUCT_MSG, $notExistIdArr );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		if ($invalidQuantity != null) {
			// Throw error when error product Id not null.
			$jsonString = createResMs ( ERR_LESS_QUANTITY_PRODUCT_CODE, ERR_LESS_QUANTITY_PRODUCT_MSG, $invalidQuantity );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		// TO-DO: calcutate total price
		$total = calculate ( $productArr );
		
		// insert bill (with shop id, card of user and total price)
		$billId = insertOrder ( $userId, $cardCode, $total );
		
		// insert bill detail (product and quantity of each product)
		insertOrderDetail ( $productArr, $billId );
		
		// add point to member card.
		addPoints($cardCode, $total, $mode);
		
		$jsonString = createResMs ( SUCCESS_CODE, SUCCESS_MSG, null );
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
	
	public function checkCardExist(Request $request) {
		
		// get card code from request
		$cardCode = $request->cardCode;
		
		// Check exist Card
		if (! Card::Where ( 'card_code', '=', $request->cardCode )->exists ()) {
			$code = array (
				'cardCode' => $request->cardCode 
			);
			
			$jsonString = createResMs ( ERR_NOT_EXIST_CARD_CODE, ERR_NOT_EXIST_CARD_MSG, $code );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		$jsonString = createResMs (SUCCESS_CODE , SUCCESS_MSG , null );
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
}

	
/**
 * Calculate total price
 * 
 * @param array $productArr
 * @return $total
 */
function calculate($productArr) {
	$total = 0;
	// loop all element of products list1
	foreach ( $productArr as $item ) {
		
		// Querry to check price
		$price = product::Where ( 'code', '=', $item ['productCode'] )->value ( 'price' );
		
		$total += $price * $item ['quantity'];
	}
	
	return $total;
}

/**
 * insert bill to DB
 * 
 * @param int $userId
 * @param string $cardCode
 * @param int $total
 * @return bill id
 */
function insertOrder($userId, $cardCode, $total) {
	$user = User::findOrfail ( $userId );
	
	$bill = new bill ();
	
	$bill->shop_id = $user->id;
	$bill->card_code = $cardCode;
	$bill->total = $total;
	$bill->ins_date = date('Y-m-d H:i:s');
	
	$bill->save ();
	return $bill->id;
}

/**
 * insert bill detail to DB
 * 
 * @param unknown $productArr
 * @param unknown $billId
 */
function insertOrderDetail($productArr, $billId) {
	
	// convert data from input to DB
	$bill_detail_array = array ();
	$product = new product();
	
	foreach ( $productArr as $item ) {
		$bill_detail_array [] = array (
				'bill_id' => $billId,
				'product_id' => $item ['productCode'],
				'product_quantity' => $item ['quantity'] 
		);
		
		// decrease number of product
	
		$product = product::Where ( 'code', '=', $item ['productCode'] )->get()->first();
		$product->quantity -= $item ['quantity'];
		$product-> save();
	}
	// insert batch
	bill_detail::insert ( $bill_detail_array );
}
/**
 * Add point to member card when pay
 *
 * 
 * @param int $total
 */
function addPoints($cardCode, $total, $mode) {
	
	$points = ceil($total/BASE_POINT);
	
	// Bonus point in birthday of shop
	if ($mode == 1) {
		$points *= BIRTHDAY_BONUS;
	}
	
	// Bonus point when Total value more than gold points
	if ($total > GOLD_POINT_VALUE) {
		
		// Bonus 10 percent
		$points *= GOLD_PERCENT_VALUE;
	}
	
	$card = card::Where ( 'card_code', '=', $cardCode )->get()->first();
	$card -> points += $points;
	$card-> save();
}

function mode($modeId) {
	
}
/**
 * Create response message
 * 
 * @param unknown $status
 * @param unknown $message
 * @param unknown $data
 * @return string
 */
function createResMs($status, $message, $data) {
	$json = (array (
			'status' => $status,
			'message' => $message,
			'data' => $data
	));

	$jsonString = json_encode ( $json );

	return $jsonString;
}

/**
 * Constant value
 */

// value for points of member card
define('BASE_POINT', 10000);
define('GOLD_POINT_VALUE', 1000000);
define('GOLD_PERCENT_VALUE', 1.1);
define('BIRTHDAY_BONUS', 1.5);


// Success case
define ( 'SUCCESS_CODE', '0' );
define ( 'SUCCESS_MSG', 'Success' );

// Error case for pay off
define ( 'ERR_NOT_EXIST_CARD_CODE', '7' );
define ( 'ERR_NOT_EXIST_CARD_MSG', 'Card not exist' );

define ( 'ERR_NOT_EXIST_PRODUCT_CODE', '8' );
define ( 'ERR_NOT_EXIST_PRODUCT_MSG', 'Product not exist' );

define ( 'ERR_LESS_QUANTITY_PRODUCT_CODE', '9' );
define ( 'ERR_LESS_QUANTITY_PRODUCT_MSG', 'Quantity out of stock' );

