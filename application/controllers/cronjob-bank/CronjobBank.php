<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CronjobBank extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function testing(){
		$text = "UPI CREDIT REFERENCE 506067805978 FROM 9999041975@ YESCRED M S SWAMIJI MEDICOS PAID VIA CRED";
		preg_match("/FROM\s+([\d]+)@\s*([\w]+)/", $text, $matches);
		if (!empty($matches) && empty($received_from)){
			echo $received_from = trim($matches[1])."@".trim($matches[2]);
			$received_from = str_replace("'", "", $received_from);
			$received_from = str_replace(" ", "", $received_from);
			$received_from = str_replace("\n", "", $received_from);
			//$from_value = "<b>find3: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
			$statment_type = 9;
			echo "<br>9</br>";
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
				$this->get_statment();
			}else{
				$check_processing = $this->BankModel->select_row("tbl_bank_processing", array('process_status' => 0));
				if (!empty($check_processing)) {
					$this->bank_processing();
				}
			}
		}
	}

	public function insert_whatsapp(){
		echo "insert_whatsapp";

		$start_date = date('d-m-Y', strtotime('-1 day'));
		$end_date = date('d-m-Y');

		$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
		$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);

		$start_date = $start_date->format('d/m/Y');
		$end_date 	= $end_date->format('d/m/Y');

		$sender_name_place = "Online%20Details";

		//Created a GET API
		$url = "http://192.46.214.43:5000/get_messages_by_status?start_date=$start_date&end_date=$end_date&group=$sender_name_place&status=true";

		$parmiter = '';
		$curl = curl_init();
		
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL =>$url,
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

		$data1 = json_decode($response, true);

		if (isset($data1['messages'])) {
			foreach ($data1['messages'] as $message) {
				$message_id = isset($message['message_id']) ? $message['message_id'] : "Body not found";
				$body = isset($message['body']) ? $message['body'] : "Body not found";
				$date = isset($message['date']) ? $message['date'] : "Date not found";
				$extracted_text = isset($message['extracted_text']) ? $message['extracted_text'] : "extracted_text not found";
				$from_number = isset($message['from_number']) ? $message['from_number'] : "Date not found";
				$ist_timestamp = isset($message['ist_timestamp']) ? $message['ist_timestamp'] : "timestamp not found";
				$screenshot_image = isset($message['screenshot_image']) ? $message['screenshot_image'] : "screenshot_image not found";
				$sender_name_place = isset($message['sender_name_place']) ? $message['sender_name_place'] : "sender_name_place not found";
				$timestamp = isset($message['timestamp']) ? $message['timestamp'] : "timestamp not found";
				$vision_text = isset($message['vision_text']) ? $message['vision_text'] : "vision_text not found";				

				//$extracted_text = str_replace("\n", "<br>", $extracted_text);
				//$vision_text = str_replace("\n", "<br>", $vision_text);

				//$date = date('Y-m-d H:i:s', strtotime($date));
				// Convert date
				$date = new DateTime($date);
				// Format as "YYYY-MM-DD"
				$date = $date->format("Y-m-d");

				$dt = array(
					'message_id' => $message_id,
					'body' => $body,
					'date' => $date,
					'extracted_text' => $extracted_text,
					'from_number' => $from_number,
					'ist_timestamp' => $ist_timestamp,
					'screenshot_image' => $screenshot_image,
					'sender_name_place' => $sender_name_place,
					'timestamp' => $timestamp,
					'vision_text' => $vision_text,
				);

				if (!empty($message_id)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->BankModel->select_row("tbl_whatsapp_message", array('message_id' => $message_id));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('message_id' => $message_id);
						$this->BankModel->edit_fun("tbl_whatsapp_message", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->BankModel->insert_fun("tbl_whatsapp_message", $dt);
					}
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

	public function get_statment(){

		//echo " get_statment";
		$result = $this->BankModel->select_query("select * from tbl_statment where status='0' limit 25");
		$result = $result->result();
		foreach($result as $row){
		
			echo $row->id."----<br>";
			$amount = $row->amount;
			$date = $row->date;
			echo $text = $statment_text = $row->narrative;
			$text = str_replace(array("\r", "\n"), '', $text);
			$upi_no = $orderid = $row->customer_reference;
			$received_from = "";

			/**********************************************/
			$text = preg_replace("/KKBKH\d+/", "", $text);
			$text = preg_replace("/KK\s*BKH\d+/", "", $text);
			$text = preg_replace("/KKB\s*KH\d+/", "", $text);
			$text = preg_replace("/KKBK\s*H\d+/", "", $text);
			$text = preg_replace("/KKBKH\s*\d+/", "", $text);

			$text = preg_replace("/9300966180/", '', $text);
			$text = preg_replace("/\s*9300966180/", '', $text);
			$text = preg_replace("/9\s*300966180/", '', $text);
			$text = preg_replace("/93\s*00966180/", '', $text);
			$text = preg_replace("/930\s*0966180/", '', $text);
			$text = preg_replace("/9300\s*966180/", '', $text);
			$text = preg_replace("/93009\s*66180/", '', $text);
			$text = preg_replace("/930096\s*6180/", '', $text);
			$text = preg_replace("/9300966\s*180/", '', $text);
			$text = preg_replace("/93009661\s*80/", '', $text);
			$text = preg_replace("/930096618\s*0/", '', $text);

			$text = preg_replace("/N2632432758889/", '', $text);
			$text = preg_replace("/N\s*2632432758889/", '', $text);
			$text = preg_replace("/N2\s*632432758889/", '', $text);
			$text = preg_replace("/N26\s*32432758889/", '', $text);
			$text = preg_replace("/N263\s*2432758889/", '', $text);
			$text = preg_replace("/N2632\s*432758889/", '', $text);
			$text = preg_replace("/N26324\s*32758889/", '', $text);
			$text = preg_replace("/N263243\s*2758889/", '', $text);
			$text = preg_replace("/N2632432\s*758889/", '', $text);
			$text = preg_replace("/N26324327\s*58889/", '', $text);
			$text = preg_replace("/N263243275\s*8889/", '', $text);
			$text = preg_replace("/N2632432758\s*889/", '', $text);
			$text = preg_replace("/N26324327588\s*89/", '', $text);
			$text = preg_replace("/N263243275888\s*9/", '', $text);
			$text = preg_replace("/N2632432758889\s*/", '', $text);

			$text = preg_replace("/AXOMB2639/", '', $text);
			$text = preg_replace("/A\s*XOMB2639/", '', $text);
			$text = preg_replace("/AX\s*OMB2639/", '', $text);
			$text = preg_replace("/AXO\s*MB2639/", '', $text);
			$text = preg_replace("/AXOM\s*B2639/", '', $text);
			$text = preg_replace("/AXOMB\s*2639/", '', $text);
			$text = preg_replace("/AXOMB2\s*639/", '', $text);
			$text = preg_replace("/AXOMB26\s*39/", '', $text);
			$text = preg_replace("/AXOMB263\s*9/", '', $text);
			$text = preg_replace("/AXOMB2639\s*/", '', $text);
			/**********************************************/
			$text = preg_replace('/\s+\d+TXN\s+REF NO/', ' REF NO', $text);
			$text = preg_replace('/\s+\d+\s+REF NO/', ' REF NO', $text);
			$text = preg_replace('/AX.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/N00.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/PUNBY.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/PUNBH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/INDBN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/INDBH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/IDFBH.*?REF NO/', ' REF NO', $text); 
			$text = preg_replace('/ICIN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/YES.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/POD.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/TXN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/FOR.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/CNRBH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/N 06.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/N06.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/SBIN.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/BKIDN.*?REF NO/', ' REF NO', $text);

			$text = preg_replace('/HDFCH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/H DFCH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/HD FCH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/HDF CH.*?REF NO/', ' REF NO', $text);
			$text = preg_replace('/HDFC H.*?REF NO/', ' REF NO', $text);

			$text = preg_replace('/SB2.*?-UPI/', ' UPI', $text);
			echo "<br>".$text;

			

			preg_match("/FROM\s+(.+?)\s+REF/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 1;
				echo "<br>1</br>";
			}

			preg_match("/FROM\s+(.+?)\s+CITI/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 2;
				echo "<br>2</br>";
			}
			
			preg_match("/FROM\s+(.+?)\s*+PAYMENT/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 3;
				echo "<br>3</br>";
			}

			preg_match("/FROM\s+(.+?)\s+SENT/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 4;
				echo "<br>4</br>";
			}

			preg_match("/FROM\s+(.+?)\s+UPI/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 5;
				echo "<br>5</br>";
			}

			preg_match("/FROM\s+(.+?)\s+PAY/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
				$statment_type = 6;
				echo "<br>6</br>";
			}
			
			preg_match("/FROM\s+(\d+)@\s+(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 7;
				echo "<br>7</br>";
			}
			
			preg_match("/FROM\s+(\d+)\s+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find2: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 8;
				echo "<br>8</br>";
			}

			//preg_match("/FROM\s+(\w+)\d+@\s*(\w+)/", $text, $matches);
			preg_match("/FROM\s+([\d]+)@\s*([\w]+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find3: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 9;
				echo "<br>9</br>";
			}

			preg_match("/FROM\s+([^\s@]+)\s+@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find4: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 10;
				echo "<br>10</br>";
			}

			preg_match("/FROM\s+([^\@]+)@\s*(\w+)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1])."@".trim($matches[2]);
				$received_from = str_replace("'", "", $received_from);
				$received_from = str_replace(" ", "", $received_from);
				$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find5: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 11;
				echo "<br>11</br>";
			}

			preg_match("/FROM\s+(.*?)\s+PUNBQ/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 12;
				echo "<br>12</br>";
			}

			preg_match("/FROM\s+([\w\s]+)\s+[A-Z0-9]+\s+REF NO/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 13;
				echo "<br>13</br>";
			}

			preg_match("/FROM\s+(.*?)\s+CITI0000/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 14;
				echo "<br>14</br>";
			}

			preg_match("/FROM\s+(.*)/", $text, $matches);
			if (!empty($matches) && empty($received_from)){
				$received_from = trim($matches[1]);
				//$received_from = str_replace("'", "", $received_from);
				//$received_from = str_replace(" ", "", $received_from);
				//$received_from = str_replace("\n", "", $received_from);
				//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
				$statment_type = 15;
				echo "<br>15</br>";
			}

			echo $received_from."<br>";
			//die();

			$amount = str_replace([",", ".00"], "", $amount);
			$amount = trim($amount);

			$statment_id = $row->id;
			if(!empty($received_from)){
				$row_new = $this->BankModel->select_query("select id,status,received_from from tbl_bank_processing where upi_no='$upi_no'");
				$row_new = $row_new->row();
				
				if(empty($row_new->id)){
					$dt = array(
						'status'=>2,
						'amount'=>$amount,
						'date'=>$date,
						'received_from'=>$received_from,
						'upi_no'=>$upi_no,
						'orderid'=>$orderid,
						'statment_id'=>$statment_id,
						'from_statment'=>1,
						'statment_type'=>$statment_type,
						'statment_text'=>$statment_text,						
					);
					$this->BankModel->insert_fun("tbl_bank_processing", $dt);
				}else{
					$where = array('upi_no'=>$upi_no);
					$status = 2;
					/*$type = $row_new->type;
					if($type=="SMS")
					{
						$type = "SMS/Statment";
					}*/
					// if(strtolower($row_new->received_from)==strtolower($received_from)){
					// 	$status = $row_new->status;
					// }
					$dt = array(
						'status'=>2,
						'received_from'=>$received_from,
						'statment_id'=>$statment_id,
						'from_statment'=>1,
						'statment_type'=>$statment_type,
						'statment_text'=>$text,
					);
					$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
				}
			}
			/****************************************************** */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>'1',
			);
			$this->BankModel->edit_fun("tbl_statment", $dt,$where);
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

	public function whatsapp_find_upi_amount(){

		//$result = $this->BankModel->select_query("SELECT id,vision_text FROM `tbl_whatsapp_message` where id='31452'");
		$result = $this->BankModel->select_query("SELECT id,vision_text FROM `tbl_whatsapp_message` where upi_no='' and vision_text!='' ORDER BY RAND() limit 100");
		$result = $result->result();
		foreach($result as $row) {

			$text = trim($row->vision_text);

			$upi_no = "";
			$amount = "0.0";
			//********amount********** */
			// Regular Expression to extract amount.
			preg_match('/[₹\?]([\d,]+)/', $text, $matches);
			// Check if match is found
			if (!empty($matches[1])) {
				$amount = $matches[1];
			}
			if($amount == "0.0"){
				preg_match('/[₹\?]([\d,]+(?:\.\d{1,2})?)/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match('/Amount:\s*([\d,]+)/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match('/\*\*Transfer Amount:\*\* ([\d,]+\.\d{2})/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match('/\*\*Transfer Amount:\*\* ([\d,]+\.\d{2})/', $text, $matches);
				// Check if match is found
				if (!empty($matches[1])) {
					$amount = $matches[1];
				}
			}
			if($amount == "0.0"){
				preg_match_all('/\?[\s]*([\d,.]+)/', $text, $matches);

				if (!empty($matches[1])) {
					$amount = !empty($matches[1][0]) ? $matches[1][0] : $matches[1][1] ?? '0.0';
				}
			}

			$type = 0;
			/************************************************** */
			// Regular Expression to extract UTR No.
			preg_match('/Reference No\. \(UTR No\.\/RRN\): (\S+)/', $text, $matches);
			// Check if match is found
			if (!empty($matches[1])) {
				$upi_no = $matches[1];
				$type = 1;
				//echo "UTR Number: " . $matches[1]; // Output: KKBKH25070930804
			}

			if(empty($upi_no)){
				preg_match('/UTR:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 2;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI transaction ID:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 3;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/Transaction ID:\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 4;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI Ref\. No:\s*([\d\s]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 5;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI txn id:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 6;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI Ref ID:\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 7;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI transaction ID\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 8;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				preg_match('/UPI Ref\. No:\s*([\d\s]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 9;
					//echo "UTR Number: " . $matches[1];
				}
			}

			if(empty($upi_no)){
				// Regex se UPI Reference Number extract karna
				preg_match('/\*\*UPI Ref\. No:\*\*\s*([\d\s]+)/', $text, $matches);

				if (!empty($matches[1])) {
					$upi_no = preg_replace('/\s+/', '', $matches[1]); // Space remove karna
					$type = 10;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se UPI Reference Number extract karna
				preg_match('/\*\*\s*Reference No\. \(UTR No\.\/RRN\):\s*\*\*\s*(\w+\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 11;
					//echo "UTR Number: " . $upi_no;
				}
			}

			/*if(empty($upi_no)){
				// Regex se UPI Reference Number extract karna
				preg_match('/\b[\w.-]+@[\w.-]+\b/', $text, $matches);
				if (!empty($matches[0])) {
					$upi_no = $matches[0];
					$type = 12;
					//echo "UTR Number: " . $upi_no;
				}
			}*/

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/\b\d{12}\b/', $text, $matches);
				if (!empty($matches[0])) {
					$upi_no = $matches[0];
					$type = 13;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/\*\*Transaction ID:\*\*\s*(\d+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 14;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/Transaction ID\s*\n*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 15;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/\*\*Reference Number:\*\*\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 16;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/Reference\s*Number\s*([A-Z0-9]+)/i', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 17;
					//echo "UTR Number: " . $upi_no;
				}
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/UTR:\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 18;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			if(empty($upi_no)){
				// Regex se Transaction ID extract karna
				preg_match('/Reference Number:\s*([\w\d]+)/', $text, $matches);
				if (!empty($matches[1])) {
					$upi_no = $matches[1];
					$type = 19;
					//echo "UTR Number: " . $upi_no;
				} 
			}

			$amount = str_replace([",", ".00"], "", $amount);
			$amount = trim($amount);

			$upi_no = trim($upi_no);
			echo $row->id."-".$amount."-".$upi_no."-".$type;
			echo "<br>";

			$where = array(
				'id' => $row->id,
			);
			$dt = array(
				'upi_no'=>$upi_no,
				'amount'=>$amount,
			);
			$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
			/*********************************************************** */
		}
	}

	public function whatsapp_insert_in_process(){
		echo " get_whatsapp ";

		$result = $this->BankModel->select_query("SELECT p.id, wm.id as whatsapp_id,wm.body as body, wm.vision_text,wm.timestamp,wm.from_number,p.find_chemist FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.upi_no=wm.upi_no and wm.body=p.find_chemist where p.whatsapp_id='' ORDER BY RAND() limit 25");
		$result = $result->result();
		foreach($result as $row) {

			echo $id = $row->id;
			$whatsapp_id = trim($row->whatsapp_id);
			$whatsapp_body = ($row->body);
			$whatsapp_chemist = trim($whatsapp_body);
			$from_number = $row->from_number;
			$find_chemist = $row->find_chemist;
			if(empty($whatsapp_body)){
				$row1 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' and body='$find_chemist' LIMIT 0, 25");
				$row1 = $row1->row();
				if(!empty($row1)){
					$whatsapp_chemist = trim($row1->body);
				}
			}
			if(empty($whatsapp_body)){
				$timestamp = date('Y-m-d H:i:s', $row->timestamp);

				$row1 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body!='' LIMIT 0, 25");
				$row1 = $row1->row();
				$whatsapp_chemist = trim($row1->body);
			}

			$where = array(
				'id' => $id,
			);
			$dt = array(
				'process_status'=>2,
				'whatsapp_id'=>$whatsapp_id,
				'whatsapp_chemist'=>$whatsapp_chemist,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}

		$result = $this->BankModel->select_query("SELECT p.id, wm.id as whatsapp_id,wm.body as body, wm.vision_text,wm.timestamp,wm.from_number,p.find_chemist FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.upi_no=wm.upi_no where p.whatsapp_id='' ORDER BY RAND() limit 25");
		$result = $result->result();
		foreach($result as $row) {

			$id = $row->id;
			$whatsapp_id = trim($row->whatsapp_id);
			$whatsapp_chemist = trim($row->body);
			$from_number = $row->from_number;
			$find_chemist = $row->find_chemist;
			$timestamp = date('Y-m-d H:i:s', $row->timestamp);
			//agar body m chemist id nahi aa rahi ha to next say find karta ha yha
			if($whatsapp_chemist!=$find_chemist){
				$whatsapp_chemist = "";
			}
			if(empty($whatsapp_chemist)){
				$row1 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body='$find_chemist' LIMIT 0, 25");
				$row1 = $row1->row();
				if(!empty($row1)){
					$whatsapp_chemist = trim($row1->body);
				}
			}
			if(empty($whatsapp_chemist)){
				$row1 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body!='' LIMIT 0, 25");
				$row1 = $row1->row();
				$whatsapp_chemist = trim($row1->body);
				//agar pura naam milay to he next prcess karta ha
				if (strpos($whatsapp_chemist, $find_chemist) !== false) {
					$whatsapp_chemist = $find_chemist;
				} 
			}

			$where = array(
				'id' => $id,
			);
			$dt = array(
				'process_status'=>2,
				'whatsapp_id'=>$whatsapp_id,
				'whatsapp_chemist'=>$whatsapp_chemist,
			);
			print_r($dt);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}

	public function whatsapp_find_upi_to_process2(){
		
		//SELECT p.upi_no, wm.message_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and p.upi_no=507050353549 WHERE p.date = '2025-03-11';

		$result = $this->BankModel->select_query("SELECT p.upi_no, wm.id as whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%', TRIM(p.upi_no), '%') where p.whatsapp_id=''");
		$result = $result->result();
		foreach($result as $row) {

			$upi_no = trim($row->upi_no);
			$whatsapp_id = trim($row->whatsapp_id);

			$where = array(
				'id' => $whatsapp_id,
			);
			$dt = array(
				'upi_no'=>$upi_no,
			);
			$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
		}

		//other xx 1234
		$result = $this->BankModel->select_query("SELECT p.upi_no, wm.id AS whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount AND REPLACE(TRIM(wm.vision_text), ' ', '') LIKE CONCAT('%xx', RIGHT(TRIM(p.upi_no), 4), '%') WHERE p.whatsapp_id = ''");
		$result = $result->result();
		foreach($result as $row) {

			$upi_no = trim($row->upi_no);
			$whatsapp_id = trim($row->whatsapp_id);

			$where = array(
				'id' => $whatsapp_id,
			);
			$dt = array(
				'upi_no'=>$upi_no,
			);
			$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
		}

		//amount or body say upi no find karna
		$result = $this->BankModel->select_query("SELECT p.upi_no, wm.id as whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and TRIM(wm.body) = TRIM(p.find_chemist) and p.whatsapp_id=''");
		$result = $result->result();
		foreach($result as $row) {

			$upi_no = trim($row->upi_no);
			$whatsapp_id = trim($row->whatsapp_id);

			$where = array(
				'id' => $whatsapp_id,
			);
			$dt = array(
				'upi_no'=>$upi_no,
			);
			$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
		}

		//ager body me say kuch get ho skta ha
		$result = $this->BankModel->select_query("SELECT p.upi_no, wm.id as whatsapp_id, wm.body FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON REPLACE(TRIM(wm.body), '.00', '') LIKE CONCAT('%', TRIM(p.amount), '%') and REPLACE(TRIM(wm.body), ' ', '') LIKE CONCAT('%', TRIM(p.upi_no), '%') where p.whatsapp_id=''");
		$result = $result->result();
		foreach($result as $row) {

			$upi_no = trim($row->upi_no);
			$whatsapp_id = trim($row->whatsapp_id);

			$text = trim($row->body);

			$amount = "0.0";
			preg_match('/Rs\s([\d,]+\.?\d{0,2})/', $text, $matches);
			if (!empty($matches[1])) {
				// Remove comma from amount if present (e.g., "10,000.50" -> "10000.50")
				$amount = str_replace(',', '', $matches[1]);
			} 

			$amount = str_replace([",", ".00"], "", $amount);
			$where = array(
				'id' => $whatsapp_id,
			);
			$dt = array(
				'upi_no'=>$upi_no,
				'amount'=>$amount,
			);
			$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
		}

		//chemist id or rs say whatsapp find karti ha yha
		$result = $this->BankModel->select_query("SELECT p.upi_no, wm.id as whatsapp_id, wm.vision_text FROM tbl_bank_processing AS p JOIN tbl_whatsapp_message wm ON p.amount = wm.amount and REPLACE(TRIM(wm.body), ' ', '') LIKE CONCAT('%', TRIM(p.find_chemist), '%') where p.whatsapp_id=''");
		$result = $result->result();
		foreach($result as $row) {

			$upi_no = trim($row->upi_no);
			$whatsapp_id = trim($row->whatsapp_id);
			
			$where = array(
				'id' => $whatsapp_id,
			);
			$dt = array(
				'upi_no'=>$upi_no,
			);
			$this->BankModel->edit_fun("tbl_whatsapp_message", $dt,$where);
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