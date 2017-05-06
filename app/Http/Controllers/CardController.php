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
		$name = $req->guest_name;
		
		if ($name == null) {
			// 1. check empty name
			return response()->json([
					'success'=>'false',
					'errCode'=>'1',
					'errMsg'=>'empty guest name'
			]);
			
		} elseif (strlen($name) > 20) {
			// 2. check length > 20
			return response()->json([
					'success'=>'false',
					'errCode'=>'2',
					'errMsg'=>'guest name more than 20 character'
			]);	
		}

		// validate phone number
		$phone = $req -> phone_number;
		
		if ($phone == null) {
			// 1. check empty name
			return response()->json([
					'success'=>'false',
					'errCode'=>'3',
					'errMsg'=>'empty phone number'
			]);
				
		} elseif (strlen($phone) > 11 || strlen($phone) < 9 ) {
			// 2. check length
			return response()->json([
					'success'=>'false',
					'errCode'=>'4',
					'errMsg'=>'phone number must be 9 or 10 character'
			]);
		} elseif (!is_numeric($phone)) {
			// 3. check numeric phone number
			return response()->json([
					'success'=>'false',
					'errCode'=>'5',
					'errMsg'=>'phone number must be numeric'
			]);
		}
		
			// 4. check duplicate phone number
			$oldPhone = Card::Where ( 'phone_number', $phone )->get ()->toArray ();
			
			if (!$oldPhone == null) {
				return response()->json([
						'success'=>'false',
						'errCode'=>'6',
						'errMsg'=>'duplicate phone number'
				]);
			}

		// Create Card code 
		do {
			// Create code and check duplicate
			$code = $this->autoGenCardCode ();
			$Oldcode = Card::Where ( 'card_code', $code )->get ()->toArray ();
			
		} while (!$Oldcode == null);

		// Set card properties to insert to DB
		$card->guest_name = $name;
		$card->phone_number = $phone;
		$card->card_code = $code;
		
		$card->save ();
		
		// Return card code to response
		return response()->json([
						'success'=>'true',
						'cardCode'=> $code
				]);
		
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
