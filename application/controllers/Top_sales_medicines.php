<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Top_sales_medicines extends CI_Controller {
	public function __construct(){

		parent::__construct();

		$this->load->model("model-drdweb/InvoiceModel");
	}
	
	public function index()	{
		$vdt = date("Y-m-d");
		$result = $this->db->select_query("select DISTINCT item_name,item_code, COUNT(*) as ct FROM tbl_invoice_item where vdt='$vdt' GROUP BY item_name,item_code HAVING COUNT(*) > 1 order by ct desc limit 100");
		$data["result"] = $result->result();
		$this->load->view('top_sales_medicines',$data);
	}
}