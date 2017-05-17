<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\bill;

class BillController extends Controller {
	/**
	 * 
	 * @param Request $req
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getBills(Request $req) {
		$shopId = $req->userId;
		// get shop bills from database
		$bills = bill::select ('*' )->where ( 'shop_id', $shopId )->get ()->toArray ();
		
		if (empty ( $bills )) {
			$jsonString = createResMsBill ( STATUS_EMPTY_CODE, STATUS_EMPTY_MSG, null );
		} else {
			$dataArray = createBillsArray($bills);
			$jsonString = createResMsBill ( STATUS_EXIST_CODE, STATUS_EXIST_MSG, $dataArray );
		}
		
		return response( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
	
}


/**
 * Constant value
 */

// value for points of member card
define ( 'STATUS_EMPTY_CODE', '11' );
define ( 'STATUS_EXIST_CODE', '12' );
define ( 'STATUS_EMPTY_MSG', 'Not exist bills' );
define ( 'STATUS_EXIST_MSG', 'Exist bills' );

function createBillsArray($bills){
	
	$dataArray = array();
	
	// convert array from DB to json array
	foreach ($bills as $item) {
		$dataArray[] = array(
				'cardCode'=>$item['card_code'],
				'total'=>$item['total'],
				'date'=>$item['ins_date']
		); 
	}
	
	return $dataArray;
}
/**
 *
 * @param unknown $status
 * @param unknown $message
 * @param unknown $data
 * @return string
 */
function createResMsBill($status, $message, $data) {
	$json = (array (
			'status' => $status,
			'message' => $message,
			'data' => $data
	));

	return json_encode ( $json );
}
