<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sms extends CI_Controller {		
	public function index()	{	
		$this->load->view('sms');
	}

	public function split_function(){

		$result = $this->db->query("select * from tbl_upload_sms where status='0' limit 100")->result();
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
			
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>'1',
				'amount'=>$amount,
				'getdate'=>$getdate,
				'upi_no'=>$upi_no,
				'orderid'=>$orderid,
			);
			$this->Scheme_Model->edit_fun("tbl_upload_sms",$dt,$where);
		}
	}
}