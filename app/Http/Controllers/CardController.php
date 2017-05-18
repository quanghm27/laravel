<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;
use App\Card_manager;

class CardController extends Controller {
	
	/**
	 * Create new guest card
	 *
	 * @param Request $req        	
	 * @return string
	 */
	public function createCard(Request $req) {
		$card = new Card ();
		
		// validate guest name
		$name = $req->guestName;
		
		if ($name == null) {
			
			// 1. check empty name
			$jsonString = createResMs ( ERR_REQUIRED_NAME_CODE, ERR_REQUIRED_NAME_MSG, null );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		} elseif (strlen ( $name ) > 225) {
			
			// 2. check length > 20
			$jsonString = createResMs ( ERR_LENGTH_NAME_CODE, ERR_LENGTH_NAME_MSG, null );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		// validate phone number
		$phone = $req->phoneNumber;
		
		if ($phone == null) {
			
			// 1. check empty name
			$jsonString = createResMs ( ERR_REQUIRED_PHONE_CODE, ERR_REQUIRED_PHONE_MSG, null );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		} elseif (! is_numeric ( $phone )) {
			// 2. check numeric phone number
			$jsonString = createResMs ( ERR_NUMERIC_PHONE_CODE, ERR_NUMERIC_PHONE_MSG, null );
			
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		} elseif (strlen ( $phone ) > 11 || strlen ( $phone ) < 9) {
			// 3. check length
			$jsonString = createResMs ( ERR_LENGTH_PHONE_CODE, ERR_LENGTH_PHONE_MSG, null );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		// 4. check duplicate phone number
		$oldPhone = Card::Where ( 'phone_number', $phone )->get ()->toArray ();
		
		if (! $oldPhone == null) {
			$jsonString = createResMs ( ERR_DUPLICATE_PHONE_CODE, ERR_DUPLICATE_PHONE_MSG, null );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		// Create Card code
		do {
			// Create code and check duplicate
			$code = $this->autoGenCardCode ();
			$Oldcode = Card::Where ( 'card_code', $code )->get ()->toArray ();
		} while ( ! $Oldcode == null );
		
		// Set card properties to insert to DB
		$card->guest_name = $name;
		$card->phone_number = $phone;
		$card->card_code = $code;
		
		$card->save ();
		
		// store shop insert card
		$cardManager = new Card_manager ();
		
		$cardId = $card->id;
		$shopId = $req->shopId;
		
		$cardManager->shop_id = $shopId;
		$cardManager->card_id = $cardId;
		$cardManager->ins_date = date ( 'Y-m-d' );
		
		$cardManager->save ();
		
		// Return card code to response
		$code = array (
				'cardCode' => $code 
		);
		
		$jsonString = createResMs ( SUCCESS_CODE, SUCCESS_MSG, $code );
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
	
	/**
	 * Auto generate code for new card
	 *
	 * @return string Card code
	 */
	private function autoGenCardCode() {
		$head = 'CARD';
		$tail = rand ( 100, 999 );
		$cardCode = $head . $tail;
		
		return $cardCode;
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getCards(Request $req) {
		
		$shop_id = $req->shopId;
		// Get all card in DB
		$dataArray = [];
		$card = Card_manager::Where('shop_id' , '=', $shop_id)
									->join ( 'card', 'card_manager.card_id', '=', 'card.id' )
									->select ( 'card.*' )
									->get ();
		
		foreach ( $card as $item ) {
			$dataArray [] = array (
					'cardId' => $item->id,
					'cardCode' => $item->card_code,
					'guestName' => $item->guest_name,
					'phoneNumber' => $item->phone_number,
					'points' => $item->points 
			);
		}
		
		$jsonString = createResMs ( SUCCESS_CODE, SUCCESS_MSG, $dataArray );
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		// Them mot shop
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
		$card = Card::findOrFail ( $id );
		
		if ($card != null) {
			$data = array (
				'cardCode' => $card->card_code 
			);
		}
		
		$card->delete ();
		
		$jsonString = createResMs ( SUCCESS_CODE, SUCCESS_MSG, $data );
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
}

// Success case
define ( 'SUCCESS_CODE', '0' );
define ( 'SUCCESS_MSG', 'Success' );

// Error case for create card
// Guest name
define ( 'ERR_LENGTH_NAME_CODE', '2' );
define ( 'ERR_LENGTH_NAME_MSG', 'Guest name must less than 20 character' );
// Phone number
define ( 'ERR_NUMERIC_PHONE_CODE', '4' );
define ( 'ERR_NUMERIC_PHONE_MSG', 'Phone number must be numeric' );
define ( 'ERR_LENGTH_PHONE_CODE', '5' );
define ( 'ERR_LENGTH_PHONE_MSG', 'Phone number must be 9 or 10 character' );
define ( 'ERR_DUPLICATE_PHONE_CODE', '6' );
define ( 'ERR_DUPLICATE_PHONE_MSG', 'Duplicate phone number' );
function createResMs($status, $message, $data) {
	$json = (array (
			'status' => $status,
			'message' => $message,
			'data' => $data 
	));
	
	$jsonString = json_encode ( $json );
	
	return $jsonString;
}