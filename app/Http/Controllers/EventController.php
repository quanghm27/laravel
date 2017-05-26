<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\bonus_manager;
use App\bonus_detail;
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
			
			foreach ( $dataArray as $item) {
				$bonusDetailArr[] = array (
					'bonus_id' => $bonusId,
					'product_code' => $item['productCode'],
					'bonus_point' => $item['bonusPoint']
				);
			}
			
			bonus_detail::insert($bonusDetailArr);
		}
		
		return response()->json('ok');
	}
}