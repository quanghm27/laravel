<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\bill;
use Illuminate\Support\Facades\DB;
class BillController extends Controller {
	/**
	 *
	 * @param Request $req
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getBills(Request $req) {
		$shopId = $req->shopId;

		if  ($shopId == null) {
			$jsonString = createResMsBill ( STATUS_EMPTY_PARAM_CODE, STATUS_EMPTY_PARAM_MSG, null );
			return response( $jsonString )->header ( 'Content-Type', 'application/json' );
		}
		// get shop bills from database
		$bills = bill::select ('*' )->where ( 'shop_id', $shopId )
									->orderBy('ins_date', 'desc')
									->get ()
									->toArray ();

		if (empty ( $bills )) {
			$jsonString = createResMsBill ( STATUS_EMPTY_BILLS_CODE, STATUS_EMPTY__BILLS_MSG, null );
		} else {
			$dataArray = createBillsArray($bills);
			$jsonString = createResMsBill ( STATUS_EXIST_CODE, STATUS_EXIST_MSG, $dataArray );
		}

		return response( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
	
	/**
	 * 
	 */
	public function getBillComplete(Request $req) {
		
		$shopId = $req->shopId;
		
		$bill = DB::table('bill')
					->where('shop_id',$shopId)
					->orderBy('ins_date', 'desc')
					->first();
		
		$billId = $bill->ID;
		$total = $bill->total;
		$date = $bill->ins_date;
		
		$billDetail = DB::table('bill_detail')
					->join('product', 'bill_detail.product_id', '=', 'product.code')
					->where('bill_id', $billId)
					->select('product.name', 'product.price', 'product.description','bill_detail.product_quantity')
					->get();
		
		$resData = array (
			'billDate' => $date,
			'billTotal' => $total,
			'billData' => createBillDetailArray($billDetail)
		);
		
		$jsonString = createResMsBill ( SUCCESS_CODE, SUCCESS_MSG, $resData );
		return response( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
	
	public function getBillDetail(Request $req){
		
		$billId = $req->billId;
		
		$bill = DB::table('bill')
		->where('ID',$billId)
		->first();
		
		$total = $bill->total;
		$date = $bill->ins_date;
		
		$billDetail = DB::table('bill_detail')
			->join('product', 'bill_detail.product_id', '=', 'product.code')
			->where('bill_id', $billId)
			->select('product.name', 'product.price', 'product.description','bill_detail.product_quantity')
			->get();
		
		$resData = array (
				'billDate' => $date,
				'billTotal' => $total,
				'billData' => createBillDetailArray($billDetail)
		);
		
		$jsonString = createResMsBill ( SUCCESS_CODE, SUCCESS_MSG, $resData );
		return response( $jsonString )->header ( 'Content-Type', 'application/json' );
	}
}


/**
 * Constant value
 */

// message and code for case OK
define ( 'SUCCESS_CODE', '0' );
define ( 'SUCCESS_MSG', 'Success' );

// value for points of member card
define ( 'STATUS_EMPTY_BILLS_CODE', '11' );
define ( 'STATUS_EXIST_CODE', '12' );
define ( 'STATUS_EMPTY_PARAM_CODE', '13' );

define ( 'STATUS_EMPTY__BILLS_MSG', 'Not exist bills' );
define ( 'STATUS_EXIST_MSG', 'Exist bills' );
define ( 'STATUS_EMPTY_PARAM_MSG', 'Empty shop id' );
/**
 * 
 * @param unknown $bills
 * @return unknown[][]
 */
function createBillsArray($bills){

	$dataArray = array();
	
	$endArr = array();
	
	// convert array from DB to json array
	foreach ($bills as $item)  {
		
		$dataArray[] = array(
				'date'=>date('y-m-d', strtotime($item['ins_date'])),
				'infor'=> (object) array (
						'billId' => $item['ID'],
						'cardCode'=>$item['card_code'],
						'total'=>$item['total'],
				)
		);
	}
	
	return $dataArray;
}

/**
 * 
 * @param unknown $bills
 * @return unknown[][]
 */
function createBillDetailArray($billDetail){

	$dataArray = array();

	// convert array from DB to json array
	foreach ($billDetail as $item) {
		$dataArray[] = array(
				'productName'=>$item->name,
				'productQuantity'=>$item->product_quantity,
				'productPrice'=>$item->price
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
