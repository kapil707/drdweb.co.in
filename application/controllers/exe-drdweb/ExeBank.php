<?php
header('Content-Type: application/json');
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeBank extends CI_Controller
{
	public function download_sms()
	{
		$result = $this->db->query("SELECT * FROM `tbl_sms` where download_status=0 order by id asc limit 100")->result();
		if ($query) {
			// Fetch the result array
			$result_array = $query->result_array();
		
			// Output the result as JSON
			echo json_encode($result_array);
		} else {
			// Handle the case where the query fails
			echo json_encode(array("error" => "Failed to fetch records"));
		}
	}
}