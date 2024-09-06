<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeMedicineRishab extends CI_Controller
{
	public function __construct(){

		parent::__construct();

		// Load model
		//$this->load->model("model-drdweb/InvoiceModel");
	}
	public function download_medicines()
	{
		/*$query = $this->db->get('tbl_medicine_new');
		$data = $query->result_array();
        echo json_encode($data);*/
	}
	public function download_medicines_id($id)
	{
		$where = array('id' => $id);
		$query = $this->db->where($where)->get('tbl_medicine_xxx');
		$data = $query->result_array();
		echo json_encode($data);
	}
}