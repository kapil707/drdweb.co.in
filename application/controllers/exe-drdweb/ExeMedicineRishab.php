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

	/*public function download_medicines_id($id)
	{
		$default2 = $this->load->database('default2', TRUE);

		$where 	= array('id' => $id);
		$query 	= $default2->where($where)->get('tbl_medicine');
		$data 	= $query->result_array();
		echo json_encode($data);
	}*/

	public function download_medicines_image_id($id)
	{
		$where 	= array('id' => $id);
		$query 	= $this->db->where($where)->get('tbl_medicine_image');
		$data 	= $query->result_array();
		echo json_encode($data);
	}

	public function download_medicines_info_id($id)
	{
		$where 	= array('id' => $id);
		$query 	= $this->db->where($where)->get('tbl_med_info');
		$data 	= $query->result_array();
		echo json_encode($data);
	}
}