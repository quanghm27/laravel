<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;

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
		} elseif (strlen ( $name ) > 20) {
			
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
}
