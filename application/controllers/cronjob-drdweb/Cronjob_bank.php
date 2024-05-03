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
		$parmiter = '';
		$curl = curl_init();
		
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL =>"http://97.74.82.55:5000/messages?from=07/04/2024&to=07/04/2024",
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
				);
				$this->BankModel->insert_fun("tbl_whatsapp_message", $dt);
			}
		} else {
			echo "No messages found.\n";
		}
	}

	public function bank_sms_processing(){

		$result = $this->BankModel->select_query("select * from tbl_sms where status='0' and date='2024-04-29' limit 100");
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
				$status = 1;
			} else {
				$received_from = "Received from information not found";
				$status = 2;
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
	
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='1' limit 100");
		$result = $result->result();
		foreach($result as $row){

			$received_from 	= $row->received_from;
			$amount 		= $row->amount;
			$date 			= $row->date;

			$start_date = date('Y-m-d', strtotime($date . ' -2 day'));
			$end_date = date('Y-m-d', strtotime($date . ' -1 day'));

			$find_by = "";
			$chemist_id = "";
			$process_value = "";
			$process_name = "";
			$process_status = 0;
			if(!empty($received_from)){
				$result = $this->find_by_name($received_from);
				$chemist_id = $result["chemist_id"];
				$process_status = 1;
				$find_by = "Chemist name";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($chemist_id)){
				$newString = str_replace(' ', '%', $received_from);
				$result = $this->find_by_name($newString);
				$chemist_id = $result["chemist_id"];
				$process_status = $result["process_status"];
				$find_by = "Chemist name1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($chemist_id)){
				$splitValues = explode('@', $received_from);
				$before_at = $splitValues[0];
				$result = $this->find_by_name($before_at);
				$chemist_id = $result["chemist_id"];
				$process_status = $result["process_status"];
				$find_by = "Chemist remove @";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($chemist_id)){
				$newString = substr($received_from, 0, -1);
				$result = $this->find_by_title($newString);
				$chemist_id = $result["chemist_id"];
				$process_status = $result["process_status"];
				$find_by = "Chemist Table1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($chemist_id)){
				$newString = substr($received_from, 0, -2);
				$result = $this->find_by_title($newString);
				$chemist_id = $result["chemist_id"];
				$process_status = $result["process_status"];
				$find_by = "Chemist Table2";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($chemist_id)){
				$newString = substr($received_from, 0, -3);
				$result = $this->find_by_title($newString);
				$chemist_id = $result["chemist_id"];
				$process_status = $result["process_status"];
				$find_by = "Chemist Table3";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($chemist_id)){
				$newString = substr($received_from, 0, -4);
				$result = $this->find_by_title($newString);
				$chemist_id = $result["chemist_id"];
				$process_status = $result["process_status"];
				$find_by = "Chemist Table4";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($chemist_id)){
				$pattern = '/(\d{10})/';
				preg_match($pattern, $received_from, $matches);
				if (isset($matches[1])) {
					$result = $this->find_by_title($matches[1]);
					$chemist_id = $result["chemist_id"];
					$process_status = $result["process_status"];
					$find_by = "Chemist mobile";
					$process_value = $result["process_value"];
					$process_name = $result["process_name"];
				}
			}

			/************************************************* */
			$result = $this->find_by_invoice($amount,$start_date,$end_date);
			$process_invoice = $result["chemist_id"];

			/************************************************* */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'find_by'=>$find_by,
				'chemist_id'=>$chemist_id,
				'status'=>2,
				'process_status'=>$process_status,
				'process_name'=>$process_name,
				'process_value'=>$process_value,
				'process_invoice'=>$process_invoice,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			/************************************************* */
		}
	}

	function find_by_full_name($received_from){

		$jsonArray = array();

		$chemist_id = "";
		$process_name = $received_from;
		$process_value = "";
		$process_status = 0;

		echo "SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'";
		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` = '$received_from'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_status = 0;
			$process_value = $tt->string_value;
		}

		if(!empty($jsonArray)){
			$chemist_id = implode(',', $jsonArray);
		}

		$return["chemist_id"] = $chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;
		$return["process_status"] = $process_status;

		return $return;
	}

	function find_by_name($received_from){

		$jsonArray = array();

		$chemist_id = "";
		$process_name = $received_from;
		$process_value = "";
		$process_status = 0;

		echo "SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'";
		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_status = 0;
			$process_value = $tt->string_value;
		}

		if(!empty($jsonArray)){
			$chemist_id = implode(',', $jsonArray);
		}

		$return["chemist_id"] = $chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;
		$return["process_status"] = $process_status;

		return $return;
	}

	function find_by_title($received_from){

		$jsonArray = array();

		$chemist_id = "";
		$process_name = $received_from;
		$process_value = "";
		$process_status = 0;

		$received_from = str_replace(' ', '', $received_from);
		echo "SELECT * FROM `tbl_bank_chemist` WHERE `title` LIKE '%$received_from%'";
		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `title` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_status = 0;
			$process_value = $tt->string_value;
			//$chemist_id = $tt->chemist_id;
		}

		if(!empty($jsonArray)){
			$chemist_id = implode(',', $jsonArray);
		}

		$return["chemist_id"] = $chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;
		$return["process_status"] = $process_status;

		return $return;
	}

	function find_by_invoice($amount,$start_date,$end_date){

		$jsonArray = array();

		$chemist_id = "";
		
		$rr = $this->InvoiceModel->select_query("select * from tbl_invoice_new where amt='$amount' and (vdt BETWEEN '$start_date' and '$end_date')");
		$rr = $rr->result();
		foreach($rr as $tt){			
			$jsonArray[] = $tt->chemist_id.":-".$tt->gstvno;
		}

		if(!empty($jsonArray)){
			$chemist_id = implode(',', $jsonArray);
		}

		$return["chemist_id"] = $chemist_id;

		return $return;
	}
}