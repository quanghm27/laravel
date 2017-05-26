<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\bonus_manager;
use App\bonus_detail;
use Illuminate\Support\Facades\DB;
class EventController extends Controller {
	
	public function createEvent (Request $req) {
		
		$shopId = $req->shopId;
		$eventType = $req->eventType;
		$dataArray = $req['dataArray'];
		
		$startDate = \DateTime::createFromFormat('Y-m-d', $req->startDate);
		$endDate = \DateTime::createFromFormat('Y-m-d', $req->endDate);
		
		// insert event
		$bonus = new bonus_manager();
		$bonus->bonus_type = $eventType;
		$bonus->shop_id = $shopId;
		$bonus->start_date = $startDate;
		$bonus->end_date = $endDate;
		
		$bonus->save();
		$bonusId = $bonus->id;
		
		// check event type to insert detail
		if ($eventType === '1') {
			
			// create new array 		
			$bonusDetailArr = array();
			
			$notExistIdArr = checkProduct($dataArray);
			
			if ($notExistIdArr != null) {
				// Throw error when error product Id not null.
				$jsonString = createResMs ( ERR_NOT_EXIST_PRODUCT_CODE, ERR_NOT_EXIST_PRODUCT_MSG, $notExistIdArr );
				return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
			}
			
			// product exist, insert to database
			foreach ( $dataArray as $item) {
				$bonusDetailArr[] = array (
					'bonus_id' => $bonusId,
					'product_code' => $item['productCode'],
					'bonus_point' => $item['bonusPoint']
				);
			}
			
			bonus_detail::insert($bonusDetailArr);
		}
		
		$jsonString = createResMs ( SUCCESS_CODE, SUCCESS_MSG, null );
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
	
	public function getEvents(Request $req){
		$shopId = $req->shopId;
		
		$events = bonus_manager::select('*')
								->where('shop_id', $shopId)
								->orderBy('start_date','desc')
								->get()
								->toArray();
		
		$jsonString = createResMs ( SUCCESS_CODE, SUCCESS_MSG, $events );
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
		
	}
	
	public function getEventDetail(Request $req){
		$shopId = $req->shopId;
		$eventId = $req->bonusId;
		
		$event = DB::table('bonus_manager')
						->where('id',$eventId)
						->first();
		
		$eventStartDate = $event->start_date;
		$eventEndDate = $event->end_date;
		
		$data = DB::table('bonus_manager')
							->join('bonus_detail','bonus_manager.id','=','bonus_detail.bonus_id')
							->where('id',$eventId)
							->select('bonus_detail.product_code','bonus_detail.bonus_point')
							->get();
		
		$eventData = array();
		foreach ($data as $item) {
			$eventData[] = array(
				'productCode' =>$item->product_code,
				'bonusPoint' =>$item->bonus_point
			);
		}
		
		$jsonData = array(
				'startDate' => $eventStartDate,
				'endDate' => $eventEndDate,
				'eventData' => $eventData
		);
	
		$jsonString = createResMs ( SUCCESS_CODE, SUCCESS_MSG, $jsonData );
		return response ( $jsonString )->header ( 'Content-Type', 'application/json' );
	
	}
}

function checkProduct($productArr) {
	
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
	
	return $notExistIdArr;
}


function createResMs($status, $message, $data) {
	$json = (array (
			'status' => $status,
			'message' => $message,
			'data' => $data
	));

	$jsonString = json_encode ( $json );

	return $jsonString;
}

// Success case
define ( 'SUCCESS_CODE', '0' );
define ( 'SUCCESS_MSG', 'Success' );
define ( 'ERR_NOT_EXIST_PRODUCT_CODE', '8' );
define ( 'ERR_NOT_EXIST_PRODUCT_MSG', 'Product not exist' );