<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeLowStock extends CI_Controller
{
	public function __construct(){

		parent::__construct();
		// Load model
	}
	public function download()
	{
		$jsonArray = array(); 
		
		$result = $this->db->query("SELECT tbl_low_stock_alert.id,tbl_low_stock_alert.date,tbl_low_stock_alert.time,tbl_chemist.code,tbl_low_stock_alert.i_code FROM tbl_low_stock_alert,tbl_chemist where tbl_chemist.altercode=tbl_low_stock_alert.chemist_id and tbl_low_stock_alert.user_type='chemist' and tbl_low_stock_alert.download_status=0 order by id asc limit 1")->result();
		foreach ($result as $row) {

			$id 	= $row->id;
			$slcd  	= "CL";
			$uid   	= "DRD";
			$vdt 	= $row->date." ".$row->time;
			$acno 	= $row->code;
			$itemc 	= $row->i_code;

			$dt = array(
				'id' => $id,
				'slcd' => $slcd,
				'uid' => $uid,
				'vdt' => $vdt,
				'acno' => $acno,
				'itemc' => $itemc
			);

			// Add the data to the JSON array
			$jsonArray[] = $dt;
		}

		if(!empty($jsonArray)){
			$response = array(
				'status' => "success",
				'message' => 'Data received successfully',
				'items' => $jsonArray
			);
		}else{
			$response = array(
				'success' => "error",
				'message' => 'Invalid data',
				'items' => ""
			);
		}

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}