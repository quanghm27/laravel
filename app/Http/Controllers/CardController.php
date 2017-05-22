<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Card;
use App\Card_manager;
use Illuminate\Support\Facades\DB;
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
		} elseif (strlen ( $phone ) > 10 || strlen ( $phone ) < 9) {
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
		$cardId = $card->id;
		
		// store shop insert card
		$cardManager = new Card_manager ();
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
		$dataArray = [ ];
		$card = Card_manager::Where ( 'shop_id', '=', $shop_id )
							->join ( 'card', 'card_manager.card_id', '=', 'card.id' )
							->select ( 'card.*' )->get ();
		
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
	 * 
	 * @param Request $req
	 */
	public function searchCards(Request $req){
		$shop_id = $req->shopId;
		
		$keyWord = $req->keyWord;
		
		$dataArray = [ ];
		
		$data = DB::table('card')
					->join ( 'card_manager', 'card_manager.card_id', '=', 'card.id' )
					->where('card_code', 'like', '%'.$keyWord.'%')
					->Where ( 'shop_id', '=', $shop_id )
					->select('card.*')
					->get();
		
		foreach ( $data as $item ) {
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
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request        	
	 * @param int $id        	
	 * @return \Illuminate\Http\Response
	 */
	public function updateCard(Request $request) {
		
		//
		$cardId = $request->cardId;
		
		// $card = Card::Where('id', '=', $cardId)->first();
		
		// Get guest name
		$guestName = $request->guestName;
		
		if ($guestName == null) {
			
			// 1. check empty name
			$jsonString = createResMs ( ERR_REQUIRED_NAME_CODE, ERR_REQUIRED_NAME_MSG, null );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		} elseif (strlen ( $guestName ) > 225) {
			
			// 2. check length > 20
			$jsonString = createResMs ( ERR_LENGTH_NAME_CODE, ERR_LENGTH_NAME_MSG, null );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		// Get phone number
		$phone = $request->phoneNumber;
		
		if ($phone == null) {
			
			// 1. check empty name
			$jsonString = createResMs ( ERR_REQUIRED_PHONE_CODE, ERR_REQUIRED_PHONE_MSG, null );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		} elseif (! is_numeric ( $phone )) {
			// 2. check numeric phone number
			$jsonString = createResMs ( ERR_NUMERIC_PHONE_CODE, ERR_NUMERIC_PHONE_MSG, null );
			
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		} elseif (strlen ( $phone ) > 10 || strlen ( $phone ) < 9) {
			// 3. check length
			$jsonString = createResMs ( ERR_LENGTH_PHONE_CODE, ERR_LENGTH_PHONE_MSG, null );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		// 4. check duplicate phone number
		$oldPhone = DB::table ( 'card' )
								->where ( 'id', '<>', $cardId )
								->where ( 'phone_number', $phone )
								->first ();
		
		if (! $oldPhone == null) {
			$jsonString = createResMs ( ERR_DUPLICATE_PHONE_CODE, ERR_DUPLICATE_PHONE_MSG, null );
			return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		
		// save card updated
		DB::table ( 'card' )->where ( 'id', $cardId )->update ( [ 
				'phone_number' => $phone,
				'guest_name' => $guestName 
		] );
		
		// return JSON
		$jsonString = createResMs ( SUCCESS_CODE, SUCCESS_MSG, null );
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request) {
		//
		$cardId = $request->cardId;
		
		$card = Card::findOrFail ( $cardId );
		
		$card_manager = Card_manager::Where ( 'card_id', $cardId )->firstOrFail ();
		
		$card->delete ();
		$card_manager->delete ();
		
		$jsonString = createResMs ( SUCCESS_CODE, SUCCESS_MSG, null );
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
}
// Success case
define ( 'SUCCESS_CODE', '0' );
define ( 'SUCCESS_MSG', 'Success' );
// Error case for create card
define ( 'ERR_REQUIRED_NAME_CODE', '1' );
define ( 'ERR_REQUIRED_NAME_MSG', 'Guest name is empty' );
// Guest name
define ( 'ERR_LENGTH_NAME_CODE', '2' );
define ( 'ERR_LENGTH_NAME_MSG', 'Guest name must less than 225 character' );
// Phone number
define ( 'ERR_REQUIRED_PHONE_CODE', '3' );
define ( 'ERR_REQUIRED_PHONE_MSG', 'Phone number is empty' );
define ( 'ERR_NUMERIC_PHONE_CODE', '4' );
define ( 'ERR_NUMERIC_PHONE_MSG', 'Phone number must be numeric' );
define ( 'ERR_LENGTH_PHONE_CODE', '5' );
define ( 'ERR_LENGTH_PHONE_MSG', 'Phone number must be 9 or 10 character' );
define ( 'ERR_DUPLICATE_PHONE_CODE', '6' );
define ( 'ERR_DUPLICATE_PHONE_MSG', 'Duplicate phone number' );
// Card id not exist (case update)
define ( 'ERR_CARD_ID_NOT_EXIST_CODE', '7' );
define ( 'ERR_CARD_ID_NOT_EXIST_MSG', 'Card id not exist' );
function createResMs($status, $message, $data) {
	$json = (array (
			'status' => $status,
			'message' => $message,
			'data' => $data 
	));
	
	$jsonString = json_encode ( $json );
	
	return $jsonString;
}