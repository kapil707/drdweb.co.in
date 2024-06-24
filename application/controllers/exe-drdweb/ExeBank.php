<?php
header('Content-Type: application/json');
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeBank extends CI_Controller
{
	public function __construct(){

		parent::__construct();

		$this->load->model("model-drdweb/BankModel");
	}
	public function download_sms()
	{
		$result = $this->BankModel->select_query("SELECT * FROM `tbl_sms` where download_status=0 order by id asc limit 100");
		//$result = $result->result();
		if ($result) {
			// Fetch the result array
			$result_array = $result->result_array();
		
			// Output the result as JSON
			echo json_encode($result_array);
		} else {
			// Handle the case where the query fails
			echo json_encode(array("error" => "Failed to fetch records"));
		}
	}
}