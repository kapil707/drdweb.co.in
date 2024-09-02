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
		$data = $this->db->get('tbl_medicine_new');
        echo json_encode($data);
	}
}