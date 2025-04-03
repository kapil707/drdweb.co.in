<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankWhatsAppModel extends CI_Model  
{
	public function __construct(){
		parent::__construct();
	}

	function select_query($query)
	{
		$db_bank = $this->load->database('default', TRUE);
		return $db_bank->query($query);	
	}

	function select_row($tbl,$where)
	{
		$db_bank = $this->load->database('default', TRUE);
		if($where!="")
		{
			$db_bank->where($where);
		}
		return $db_bank->get($tbl)->row();
	}
	
	function insert_fun($tbl,$dt)
	{
		$db_bank = $this->load->database('default', TRUE);
		if($db_bank->insert($tbl,$dt))
		{
			return $db_bank->insert_id();
		}
		else
		{
			return false;
		}
	}
	function edit_fun($tbl,$dt,$where)
	{
		$db_bank = $this->load->database('default', TRUE);
		if($db_bank->update($tbl,$dt,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function delete_fun($tbl,$where)
	{
		$db_bank = $this->load->database('default', TRUE);
		if($db_bank->delete($tbl,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function get_bank_whatsapp_or_insert(){

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
				
				// Decode "quoted_text" JSON
				$quoted = json_decode($message["quoted_text"], true);
				// Extract "wid"
				$reply_id = isset($quoted['wid']) ? $quoted['wid'] : "0";

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
					'reply_id'=>$reply_id,
					'upi_no' => '',
					'set_chemist' => '',
					'amount' => '',
					'amount' => 0,
				);

				if (!empty($message_id)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->select_row("tbl_bank_whatsapp_message", array('message_id' => $message_id));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('message_id' => $message_id);
						$this->edit_fun("tbl_bank_whatsapp_message", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->insert_fun("tbl_bank_whatsapp_message", $dt);
					}
				}
			}
		}
	}
}	