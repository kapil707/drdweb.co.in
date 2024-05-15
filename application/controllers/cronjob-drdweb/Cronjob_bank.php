<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_bank extends CI_Controller 
{
	public function __construct(){

		parent::__construct();

		$this->load->model("model-drdweb/BankModel");
		$this->load->model("model-drdweb/InvoiceModel");
	}
	
	public function get_whatsapp_message()
	{
		$start_date = "14/05/2024";//date('d/m/Y');
		$end_date 	= "14/05/2024";//date('d/m/Y');
		

		$sender_name_place = "Online%20Details";

		$parmiter = '';
		$curl = curl_init();
		
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL =>"http://172.105.50.148:5000/messages?from=$start_date&to=$end_date&sender_name_place=$sender_name_place",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 0,
				CURLOPT_TIMEOUT => 300,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_POSTFIELDS => $parmiter,
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
					'Authorization: Bearer THIRTEENWOLVESWENTHUNTINGBUT10CAMEBACK'
				),
			)
		);

		$response = curl_exec($curl);
		//print_r($response);
		curl_close($curl);

		$data = json_decode($response, true); // Convert JSON string to associative array

		if (isset($data['messages'])) {
			foreach ($data['messages'] as $message) {
				$body = isset($message['body']) ? $message['body'] : "Body not found";

				$date = isset($message['date']) ? $message['date'] : "Date not found";

				$extracted_text = isset($message['extracted_text']) ? $message['extracted_text'] : "extracted_text not found";

				$vision_text = isset($message['vision_text']) ? $message['vision_text'] : "vision_text not found";

				$from_number = isset($message['from_number']) ? $message['from_number'] : "Date not found";
				
				$id = isset($message['id']) ? $message['from_number'] : "id not found";

				$screenshot_image = isset($message['screenshot_image']) ? $message['screenshot_image'] : "screenshot_image not found";

				$timestamp = isset($message['timestamp']) ? $message['timestamp'] : "timestamp not found";

				$body = utf8_encode($body);

				$dt = array(
					'body' => $body,
					'date' => $date,
					'extracted_text' => $extracted_text,
					'from_number' => $from_number,
					'message_id' => $id,
					'screenshot_image' => $screenshot_image,
					'timestamp' => $timestamp,
					'vision_text' => $vision_text,
				);
				$this->BankModel->insert_fun("tbl_whatsapp_message", $dt);
			}
		} else {
			echo "No messages found.\n";
		}
	}

	public function bank_sms_processing(){

		$date = date('Y-m-d');

		$result = $this->BankModel->select_query("select * from tbl_sms where status='0' and date='$date' limit 100");
		$result = $result->result();
		foreach($result as $row){
			$message_body = $row->message_body;
			
			$pattern = '/INR (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$amount = $matches[1];
			} else {
				$amount = "Amount not found";
			}

			$pattern = '/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/';
			if (preg_match($pattern, $message_body, $matches)) {
				$getdate = $matches[1];
			} else {
				$getdate = "Date not found";
			}

			$pattern = '/received from (\S+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$received_from = $matches[1];
				$status = 0;
			} else {
				$received_from = "Received from information not found";
				$status = 1;
			}

			$pattern = '/UPI Ref No\. (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$upi_no = $matches[1];
			} else {
				$upi_no = "UPI reference number not found";
			}

			$pattern = '/OrderId (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$orderid = $matches[1];
			} else {
				$orderid = "orderid not found";
			}
			
			$_id = $row->id;
			
			$type = "SMS";
			$dt = array(
				'status'=>$status,
				'amount'=>$amount,
				'date'=>$getdate,
				'received_from'=>$received_from,
				'upi_no'=>$upi_no,
				'orderid'=>$orderid,
				'type'=>$type,
				'_id'=>$_id,
			);
			$this->BankModel->insert_fun("tbl_bank_processing", $dt);

			/****************************************************** */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>'1',
			);
			$this->BankModel->edit_fun("tbl_sms", $dt,$where);
		}
	}
	public function bank_processing(){

		$this->bank_sms_processing();
	
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='0' limit 100");
		$result = $result->result();
		foreach($result as $row){

			$received_from 	= $row->received_from;
			$amount 		= $row->amount;
			$date 			= $row->date;

			$start_date = date('Y-m-d', strtotime($date . ' -2 day'));
			$end_date = date('Y-m-d', strtotime($date));

			$find_by = "";
			$find_chemist_id = "";
			$process_value = "";
			$process_name = "";

			if(!empty($received_from)){
				$result = $this->find_by_full_name($received_from);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name-done";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$result = $this->find_by_name($received_from);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = str_replace(' ', '%', $received_from);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			/************************************************* */
			if(empty($find_chemist_id)){
				$splitValues = explode('@', $received_from);
				$before_at = $splitValues[0];
				$result = $this->find_by_name($before_at);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $received_from);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -1);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $received_from);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -2);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 2";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $received_from);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -3);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 3";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $received_from);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -4);
				$result = $this->find_by_name($before_at);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 4";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			/************************************************* */
			if(empty($find_chemist_id)){
				$newString = substr($received_from, 0, -1);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($received_from, 0, -2);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table2";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($received_from, 0, -3);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table3";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($received_from, 0, -4);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table4";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			/************************************************* */
			if(empty($find_chemist_id)){
				$pattern = '/(\d{10})/';
				preg_match($pattern, $received_from, $matches);
				if (isset($matches[1])) {
					$result = $this->find_by_title($matches[1]);
					$find_chemist_id = $result["find_chemist_id"];
					$find_by = "Chemist mobile";
					$process_value = $result["process_value"];
					$process_name = $result["process_name"];
				}
			}

			if(empty($find_chemist_id)){
				$pattern = '/(\d{10})/';
				preg_match($pattern, $received_from, $matches);
				if (isset($matches[1])) {
					$result = $this->find_by_acm_tbl($matches[1]);
					$find_chemist_id = $result["find_chemist_id"];
					$find_by = "Acm mobile";
					$process_value = $result["process_value"];
					$process_name = $result["process_name"];
				}
			}

			$find_chemist_id = str_replace("/",",",$find_chemist_id);

			/************************************************* */
			$result = $this->find_by_invoice($amount,$start_date,$end_date,$find_chemist_id);
			$find_invoice_chemist_id = $result["find_invoice_chemist_id"];

			if(!empty($find_chemist_id)){
				if(empty($find_invoice_chemist_id)){
					$result = $this->find_by_invoice_amount($amount,$start_date,$end_date,$find_chemist_id);
					$find_invoice_chemist_id = $result["find_invoice_chemist_id"];
				}
			}

			/************************************************* */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'find_by'=>$find_by,
				'status'=>1,
				'process_name'=>$process_name,
				'process_value'=>$process_value,
				'find_chemist_id'=>$find_chemist_id,
				'find_invoice_chemist_id'=>$find_invoice_chemist_id,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			/************************************************* */
		}
	}

	function find_by_full_name($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";
		
		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` = '$received_from'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode(',', $jsonArray);
		}

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}

	function find_by_name($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";

		echo "SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'";
		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode(',', $jsonArray);
		}

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}

	function find_by_title($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";

		$received_from = str_replace(' ', '', $received_from);
		echo "SELECT * FROM `tbl_bank_chemist` WHERE `title` LIKE '%$received_from%'";
		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `title` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
			//$chemist_id = $tt->chemist_id;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode(',', $jsonArray);
		}

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}

	function find_by_acm_tbl($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";

		$rr = $this->db->query("SELECT * FROM `tbl_acm` WHERE `mobile` = '$received_from'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->altercode;
			$process_value = $tt->mobile;
			//$chemist_id = $tt->chemist_id;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode(',', $jsonArray);
		}

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}

	function find_by_invoice($amount,$start_date,$end_date,$find_chemist_id){

		$jsonArray = array();

		$where = "";
		if(!empty($find_chemist_id)){
			$fruits_array = explode(",", $find_chemist_id);
			foreach($fruits_array as $rows){
				$find_chemist_id = "'$rows',";
			}
			$find_chemist_id = substr($find_chemist_id, 0, -1);
			$where = " and chemist_id in ($find_chemist_id)";
		}

		$rr = $this->InvoiceModel->select_query("select * from tbl_invoice_new where amt='$amount' and (vdt BETWEEN '$start_date' and '$end_date') $where");
		$rr = $rr->result();
		foreach($rr as $tt){			
			$jsonArray[] = $tt->chemist_id.":-".$tt->gstvno." Amt.".$tt->amt;
		}

		$find_invoice_chemist_id = "";
		if(!empty($jsonArray)){
			$find_invoice_chemist_id = implode(',', $jsonArray);
		}

		$return["find_invoice_chemist_id"] = $find_invoice_chemist_id;

		return $return;
	}

	function find_by_invoice_amount($amount,$start_date,$end_date,$find_chemist_id){

		$jsonArray = array();

		$where = "";
		if(!empty($find_chemist_id)){
			$fruits_array = explode(",", $find_chemist_id);
			foreach($fruits_array as $rows){
				$find_chemist_id = "'$rows',";
			}
			$find_chemist_id = substr($find_chemist_id, 0, -1);
			$where = " and chemist_id in ($find_chemist_id)";
		}

		$find_invoice_chemist_id = "";
		/*$rr = $this->InvoiceModel->select_query("select sum(amt) as total from tbl_invoice_new where (vdt BETWEEN '$start_date' and '$end_date') $where");
		$rr = $rr->row();
		if(!empty($rr)){
			if(round($rr->total)==round($amount)){

				$rr = $this->InvoiceModel->select_query("select * from tbl_invoice_new where (vdt BETWEEN '$start_date' and '$end_date') $where");
				$rr = $rr->result();
				foreach($rr as $tt){			
					$jsonArray[] = $tt->chemist_id.":-".$tt->gstvno." Amt.".$tt->amt;
				}

				if(!empty($jsonArray)){
					$find_invoice_chemist_id = implode(',', $jsonArray);
				}
			}
		}

		/*******yha kisi 2 yha 3 invoice ke total ko check karta ha */
		/*if(empty($find_invoice_chemist_id)){
			$total = 0;
			$work = 0;
			$rr = $this->InvoiceModel->select_query("select * from tbl_invoice_new where (vdt BETWEEN '$start_date' and '$end_date') $where");
			$rr = $rr->result();
			foreach($rr as $tt){
				$total = round($tt->amt) + $total;
				$jsonArray[] = $tt->chemist_id.":-".$tt->gstvno." Amt-x.".$tt->amt;
				if(round($total)==round($amount)){
					$work = 1;
					break;
				}
			}
			if(!empty($jsonArray) && $work==1){
				$find_invoice_chemist_id = implode(',', $jsonArray);
			}
		}*/
		$resultArray = [];
		$rr = $this->InvoiceModel->select_query("select * from tbl_invoice_new where (vdt BETWEEN '$start_date' and '$end_date') $where");
		$rr = $rr->result();
		foreach($rr as $tt){
			$resultArray[] = [
				'chemist_id' => $tt->chemist_id,
				'gstvno' => $tt->gstvno,
				'amount' => $tt->amt
			];
		}

		$targetValue = $amount;
		$found = false;
		$selectedValues = [];

		for ($i = 0; $i < count($resultArray); $i++) {
			for ($j = $i + 1; $j < count($resultArray); $j++) {
				if ($resultArray[$i]['amount'] + $resultArray[$j]['amount'] == $targetValue) {
					$selectedValues[] = [$resultArray[$i], $resultArray[$j]];
					$found = true;
					break 2; // Exit both loops
				}
			}
		}

		
		if ($found) {
			for ($i = 0; $i < count($selectedValues[0]); $i++) {
				$rt = $selectedValues[0][$i];
				$jsonArray[] = $rt['chemist_id'].":-".$rt['gstvno']." Amt-x.".$rt['amount'];
			}
		}
		$find_invoice_chemist_id = implode(',', $jsonArray);

		$return["find_invoice_chemist_id"] = $find_invoice_chemist_id;

		return $return;
	}

	public function test($chemist_id,$amount,$dt1,$dt2){

		$resultArray = [];
		$rr = $this->InvoiceModel->select_query("SELECT * FROM `tbl_invoice_new` WHERE (vdt BETWEEN '$dt1' and '$dt2') and `chemist_id`='$chemist_id'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$resultArray[] = [
				'chemist_id' => $tt->chemist_id,
				'gstvno' => $tt->gstvno,
				'amount' => $tt->amt
			];
		}
		echo "<pre>";
		print_r($resultArray);

		$targetValue = $amount;
		$found = false;
		$selectedValues = [];

		for ($i = 0; $i < count($resultArray); $i++) {
			for ($j = $i + 1; $j < count($resultArray); $j++) {
				if ($resultArray[$i]['amount'] + $resultArray[$j]['amount'] == $targetValue) {
					$selectedValues[] = [$resultArray[$i], $resultArray[$j]];
					$found = true;
					break 2; // Exit both loops
				}
			}
		}

		if ($found) {
			print_r($selectedValues);
		} else {
			echo "No two values found with the total of $targetValue";
		}
	}

	public function bank_processing_done(){
	
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='1' and done_status=0 limit 100");
		$result = $result->result();
		foreach($result as $row){
			$find_chemist_id2 = "";
			$find_chemist_id_array = explode(",", $row->find_chemist_id);
			$find_chemist_id_array = array_unique($find_chemist_id_array);
			foreach($find_chemist_id_array as $rows){
				$find_chemist_id2 = $rows;
			}

			$find_invoice_chemist_id2 = "";
			$find_invoice_chemist_id_array = explode(",", $row->find_invoice_chemist_id);
			foreach($find_invoice_chemist_id_array as $rows){
				$arr = explode(":-",$rows);
				$find_invoice_chemist_id2 = $arr[0];
			}
			
			$done_status = "2";
			$done_chemist_id = "";
			if((strtolower($find_chemist_id2)==strtolower($find_invoice_chemist_id2)) && (!empty($find_invoice_chemist_id2) && !empty($find_chemist_id2))){
				$done_status = "1";
				$done_chemist_id = $find_chemist_id2;
			}

			$where = array(
				'id' => $row->id,
			);
			$dt = array(
				'done_chemist_id'=>$done_chemist_id,
				'done_status'=>$done_status,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}
}