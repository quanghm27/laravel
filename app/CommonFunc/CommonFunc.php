<?php

	function createResMs($status, $message, $data) {
		$json = (array (
				'status' => $status,
				'message' => $message,
				'data' => $data 
		));
		
		$jsonString = json_encode ( $json );
		
		return $jsonString;
	}
	
	
?>