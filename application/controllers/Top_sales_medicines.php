<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Top_sales_medicines extends CI_Controller {
	public function __construct(){

		parent::__construct();
	}
	
	public function index()	{
		$date = date("Y-m-d");
		$result = $this->db->query("SELECT DISTINCT itemc, ANY_VALUE(tbl_medicine.item_name) AS item_name, COUNT(*) AS ct FROM tbl_invoice_item JOIN tbl_medicine ON tbl_medicine.i_code = tbl_invoice_item.itemc WHERE tbl_invoice_item.date = '$date' AND tbl_medicine.image1 = '' GROUP BY itemc HAVING COUNT(*) > 1 ORDER BY ct DESC LIMIT 100");
		$data["result"] = $result->result();
		$this->load->view('top_sales_medicines',$data);
	}
}