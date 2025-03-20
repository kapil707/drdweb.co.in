<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Top_sales_medicines extends CI_Controller {
	public function __construct(){

		parent::__construct();
	}
	
	public function index()	{
		/*$date = date("Y-m-d");
		$result = $this->db->query("SELECT DISTINCT itemc, ANY_VALUE(tbl_medicine.item_name) AS item_name, COUNT(*) AS ct FROM tbl_invoice_item JOIN tbl_medicine ON tbl_medicine.i_code = tbl_invoice_item.itemc WHERE tbl_invoice_item.date = '$date' AND tbl_medicine.image1 = '' GROUP BY itemc HAVING COUNT(*) > 1 ORDER BY ct DESC LIMIT 100");
		$data["result"] = $result->result();*/
		$data = "";
		$this->load->view('top_sales_medicines',$data);
	}

	public function view_api() {		

		$jsonArray = array();
		$items = "";
		$i = 1;
		$date = date("Y-m-d");
		
		$result = $this->db->query("SELECT DISTINCT itemc, ANY_VALUE(tbl_medicine.item_name) AS item_name, COUNT(*) AS ct FROM tbl_invoice_item JOIN tbl_medicine ON tbl_medicine.i_code = tbl_invoice_item.itemc WHERE tbl_invoice_item.date = '$date' AND tbl_medicine.image1 = '' GROUP BY itemc HAVING COUNT(*) > 1 ORDER BY ct DESC LIMIT 100");
		$result = $result->result();
		foreach($result as $row) {

			$sr_no = $i++;
			$id = "";
			
			$item_code = $row->itemc;
			$item_name = $row->item_name;
			$item_total= $row->ct;
			
			$time = time();
			$datetime = date("d-M-y @ H:i:s", $time);

			$dt = array(
				'sr_no' => $sr_no,
				'id' => $id,
				'item_code' => $item_code,
				'item_name' => $item_name,
				'item_total' => $item_total,
				'datetime'=>$datetime,
			);
			$jsonArray[] = $dt;
		}
		if(!empty($jsonArray)){
			$items = $jsonArray;
			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $items,
			);
		}else{
			$response = array(
				'success' => "0",
				'message' => '502 error',
			);
		}
		
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}