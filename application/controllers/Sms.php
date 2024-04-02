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
			echo $row->message_body;
			die();
		}
	}
}