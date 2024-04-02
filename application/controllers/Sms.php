<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sms extends CI_Controller {		
	public function index()	{	
		$this->load->view('sms');
	}

	public function split_function(){
		
		$from_date = '2024-04-02';
		$to_date = '2024-04-02';

		$result = $this->db->query("select * from tbl_upload_sms where date BETWEEN '$from_date' AND '$to_date' limit 100")->result();
		foreach($result as $row){
			$message_body = $row->message_body;
			
			$pattern = '/INR (\d+\.\d+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$amount = $matches[1];
				echo $amount;
			} else {
				echo "Amount not found";
			}

			$pattern = '/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/';
			if (preg_match($pattern, $message_body, $matches)) {
				$date = $matches[1];
				echo "--".$date;
			} else {
				echo "Date not found";
			}

			$pattern = '/UPI Ref No\. (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$upi_no = $matches[1];
				echo "--". $upi_no;
			} else {
				echo "UPI reference number not found";
			}
			echo "<br>";

			$pattern = '/OrderId\. (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$orderid = $matches[1];
				echo "--". $orderid;
			} else {
				echo "orderid not found";
			}
			echo "<br>";
		}
	}
}