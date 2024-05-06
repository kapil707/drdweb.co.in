<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Top_sales_medicines extends CI_Controller {
	public function __construct(){

		parent::__construct();

		$this->load->model("model-drdweb/InvoiceModel");
	}
	
	public function index()	{
		$vdt = date("Y-m-d");
		$result = $this->db->query("SELECT t1.item_name, t1.item_code, COUNT(t1.id) as ct, t2.id as kp FROM drdistributor_invoice_db.tbl_invoice_item as t1
		INNER JOIN drdistributor_medicine_db.tbl_medicine as t2 ON t1.item_code = t2.item_code WHERE t1.vdt='$vdt' GROUP BY t1.item_name, t1.item_code, kp
		HAVING COUNT(t1.id) > 1 ORDER BY ct DESC LIMIT 10");
		$data["result"] = $result->result();
		$this->load->view('top_sales_medicines',$data);
	}
}