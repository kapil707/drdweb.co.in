<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CronjobBank extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
		$this->load->model("model-bank/BankStatmentModel");
		$this->load->model("model-bank/BankWhatsAppModel");
		$this->load->model("model-bank/BankInvoiceModel");		
	}

	public function get_whatsapp_or_insert(){
		echo "get_whatsapp_or_insert";
		$this->BankWhatsAppModel->get_whatsapp_or_insert();
	}

	public function whatsapp_find_upi_amount(){
		echo "whatsapp_find_upi_amount";
		$this->BankWhatsAppModel->whatsapp_find_upi_amount();
	}

	public function whatsapp_insert_in_process(){
		echo "whatsapp_insert_in_process";
		$this->BankWhatsAppModel->whatsapp_insert_in_process();
	}

	public function whatsapp_update_upi(){
		echo "whatsapp_update_upi";
		$this->BankWhatsAppModel->whatsapp_update_upi();
	}

	public function whatsapp_update_reply_message(){
		echo "whatsapp_update_reply_message";
		$this->BankWhatsAppModel->whatsapp_update_reply_message();
	}

	public function get_invoice_find_user(){
		echo "get_invoice_find_user";
		$this->BankInvoiceModel->get_invoice_find_user();
	}

	public function testing(){
		$text = "+91-9899762072 507920298106 FROM MEHAK MEDICOS AND DEPARTMENTAL STORE 9300966180 CITI0000 9052 TRANS FER TO DR KARB0000547";
		preg_match("/FROM\s+(.+?)\s+CITI/", $text, $matches);
		if (!empty($matches) && empty($received_from)){
			echo $received_from = trim($matches[1]);
			//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 2;
			echo "<br>2</br>";
		}
	}

	public function bank_main(){
		//$this->get_invoice();
		$check_sms = $this->BankModel->select_row("tbl_sms", array('status' => 0));
		if (!empty($check_sms)) {
			$this->get_sms();
		}else{
			$check_statment = $this->BankModel->select_row("tbl_statment", array('status' => 0));
			if (!empty($check_statment)) {
				$this->BankStatmentModel->get_statment();
			}else{
				$check_processing = $this->BankModel->select_row("tbl_bank_processing", array('process_status' => 0));
				if (!empty($check_processing)) {
					$this->bank_processing();
				}
			}
		}
	}	
	
	public function bank_processing(){
		
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where process_status='0' limit 25");
		$result = $result->result();
		foreach($result as $row){

			echo $received_from 	= $row->received_from;

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
			echo $find_by;

			/************************************************* */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'process_status'=>1,				
				'from_value'=>$process_value,
				'from_value_find'=>$process_name,
				'find_chemist'=>$find_chemist_id,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			/************************************************* */
		}
	}
	
	public function get_invoice(){
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='1' limit 1");
		$result = $result->result();
		foreach($result as $row){
			
			$amount 		= $row->amount;
			$date 			= $row->date;

			$start_date = date('Y-m-d', strtotime($date . ' -2 day'));
			$end_date = date('Y-m-d', strtotime($date));
			
			$chemist = str_replace("/",",",$row->chemist);

			/************************************************* */
			$result = $this->find_by_invoice($amount,$start_date,$end_date,$chemist);
			$invoice = $result["find_invoice_chemist_id"];
		
			if(!empty($chemist)){
				if(empty($invoice)){
					$result = $this->find_by_invoice_amount($amount,$start_date,$end_date,$chemist);
					$invoice = $result["find_invoice_chemist_id"];
				}
			}

			/************************************************* */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>2,				
				'invoice'=>$invoice,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			/************************************************* */
		}
	}
	
	public function get_sms(){

		//echo " get_sms";
		$date = date('Y-m-d');

		$result = $this->BankModel->select_query("select * from tbl_sms where status='0' limit 25");
		$result = $result->result();
		foreach($result as $row){
			echo $sms_text = $message_body = $row->message_body;
			$message_body = str_replace(",","",$row->message_body);
			
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

			// Regex pattern to extract time
			$pattern = "/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/";

			// Extracting time using preg_match
			if (preg_match($pattern, $message_body, $matches)) {
				$gettime = $matches[0];
			} else {
				$gettime = "Time not found.";
			}

			$pattern = '/received from (\S+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$received_from = $matches[1];
			} else {
				$received_from = "Received from information not found";
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
			echo "<br>".$received_from."<br>";
			$statment_id = $row->id;
			
			$row_new = $this->BankModel->select_query("select id from tbl_bank_processing where upi_no='$upi_no'");
			$row_new = $row_new->row();

			$amount = str_replace([",", ".00"], "", $amount);
			$amount = trim($amount);
			
			if(empty($row_new->id) && $received_from!="Remitter" && $received_from != "Received from information not found"){
				$dt = array(
					'status'=>1,
					'amount'=>$amount,
					'date'=>$getdate,
					'time'=>$gettime,
					'received_from'=>$received_from,
					'upi_no'=>$upi_no,
					'orderid'=>$orderid,
					'statment_id'=>$statment_id,
					'from_sms'=>1,
					'sms_text'=>$sms_text,
				);
				$this->BankModel->insert_fun("tbl_bank_processing", $dt);
			}

			/****************************************************** */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>'1',
				'amount'=>$amount,
				'upi_no'=>$upi_no,
			);
			$this->BankModel->edit_fun("tbl_sms", $dt,$where);
		}
	}
	
	public function get_whatsapp(){
		echo " get_whatsapp ";

		$result = $this->BankModel->select_query("SELECT id,upi_no,amount,orderid from tbl_bank_processing where process_status=1 limit 25");
		$result = $result->result();
		foreach($result as $row) {

			$upi_no = trim($row->upi_no);
			$orderid= trim($row->orderid);
			$amount = $row->amount;

			$row1 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE REPLACE(`vision_text`, ' ', '') LIKE '%$upi_no%' or REPLACE(`vision_text`, ' ', '') LIKE '%$orderid%'");
			$row1 = $row1->row();
			if(empty($row1)){
				$row1 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE `vision_text` LIKE '%$upi_no%' or `vision_text` LIKE '%$orderid%'");
				$row1 = $row1->row();
			}

			if(empty($row1)){
				$last_four_digits = substr($upi_no, -4);
				$row1 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE (REPLACE(`vision_text`, ' ', '') LIKE '%XX$last_four_digits%' and REPLACE(`vision_text`, ',', '') LIKE '%$amount%')");
				$row1 = $row1->row();
			}
			
			$whatsapp_id = 0;
			$whatsapp_body = $whatsapp_image = $whatsapp_body2 = "";
			if(!empty($row1)){
				$whatsapp_id = $row1->id;
				$whatsapp_body = $row1->body;
				$whatsapp_image = $row1->screenshot_image;
				$whatsapp_body2 = $row1->vision_text;

				$from_number = $row1->from_number;
				$timestamp = date('Y-m-d H:i:s', $row1->timestamp);

				if(empty($whatsapp_body)){
					$row2 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body!='' LIMIT 0, 25");
					$row2 = $row2->row();
					$whatsapp_body = $row2->body;
				}
			}

			if(empty($row1)){
				$row2 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE REPLACE(`body`, ' ', '') LIKE '%$upi_no%'");
				$row2 = $row2->row();

				if(!empty($row2)){
					$whatsapp_id = $row2->id;
					$whatsapp_body = "";
					$whatsapp_image = $row2->screenshot_image;
					$whatsapp_body2 = $row2->body;

					$from_number = $row2->from_number;
					$timestamp = date('Y-m-d H:i:s', $row2->timestamp);

					if(empty($whatsapp_body)){
						$row2 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body!='' and id!='$whatsapp_id' LIMIT 0, 25");
						$row2 = $row2->row();
						$whatsapp_body = $row2->body;
					}
				}
			}

			$whatsapp_body  = str_replace(',', '', $whatsapp_body);
			$whatsapp_body2 = str_replace(',', '', $whatsapp_body2);
			echo "<br>";

			$where = array(
				'id' => $row->id,
			);
			$dt = array(
				'process_status'=>2,
				'whatsapp_message_id'=>$whatsapp_id,
				'find_whatsapp_chemist'=>$whatsapp_id,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}
	/***************************************************************************************************/
	function find_by_full_name($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";
		
		/*$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` = '$received_from'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
		}*/

		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` = '$received_from'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$find_chemist_id = trim($tt->chemist_id);
			$process_value = $tt->string_value;
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

		/*$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
		}*/

		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$find_chemist_id = trim($tt->chemist_id);
			$process_value = $tt->string_value;
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
		
		/*$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `title` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
			//$chemist_id = $tt->chemist_id;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
		}*/

		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `title` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$find_chemist_id = trim($tt->chemist_id);
			$process_value = $tt->string_value;
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

		$rr = $this->db->query("SELECT * FROM `tbl_chemist` WHERE `telephone` like '%$received_from%' ");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->altercode;
			$process_value = $tt->telephone;
			//$chemist_id = $tt->chemist_id;
		}

		if(empty($process_value)){
			$rr = $this->db->query("SELECT * FROM `tbl_chemist` WHERE `telephone1` like '%$received_from%' ");
			$rr = $rr->result();
			foreach($rr as $tt){
				$jsonArray[] = $tt->altercode;
				$process_value = $tt->telephone1;
				//$chemist_id = $tt->chemist_id;
			}
		}

		if(empty($process_value)){
			$rr = $this->db->query("SELECT * FROM `tbl_chemist` WHERE `mobile` like '%$received_from%' ");
			$rr = $rr->result();
			foreach($rr as $tt){
				$jsonArray[] = $tt->altercode;
				$process_value = $tt->mobile;
				//$chemist_id = $tt->chemist_id;
			}
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
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

		//$rr = $this->InvoiceModel->select_query("select * from tbl_invoice_new where amt='$amount' and (vdt BETWEEN '$start_date' and '$end_date') $where");
		//echo "select * from tbl_invoice_new where amt='$amount' and (vdt BETWEEN '$start_date' and '$end_date') $where";
		$rr = $this->db->query("select * from tbl_invoice_new where amt='$amount' and (vdt BETWEEN '$start_date' and '$end_date') $where");
		$rr = $rr->result();
		foreach($rr as $tt){			
			$jsonArray[] = $tt->chemist_id.":-".$tt->gstvno." Amt.".$tt->amt;
		}

		$find_invoice_chemist_id = "";
		if(!empty($jsonArray)){
			$find_invoice_chemist_id = implode('||', $jsonArray);
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
		$resultArray = [];
		//$rr = $this->InvoiceModel->select_query("select * from tbl_invoice_new where (vdt BETWEEN '$start_date' and '$end_date') $where");
		$rr = $this->db->query("select * from tbl_invoice_new where (vdt BETWEEN '$start_date' and '$end_date') $where");
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
		$find_invoice_chemist_id = implode('||', $jsonArray);

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
	
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='1' and done_status=0 limit 1");
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