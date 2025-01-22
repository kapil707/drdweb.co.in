<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_net_rate extends CI_Controller
{
	public function __construct(){
		parent::__construct();
	}

	public function run_job()
	{
		$this->db->query("delete from tbl_medicine_compare where compare_type='net_rate'");
		//$date = date("Y-m-d", strtotime("-30 days", time()));

		$this->db->select('i_code');
		$this->db->from('tbl_medicine');
		$this->db->like('item_name', '#', 'before');
		$query = $this->db->get()->result();
		foreach($query as $row)
		{
			$i_code 	= $row->i_code;
			$total 		= "#";
			
			$compare_type 	= "net_rate";
			$compare_now 	= $total;
			$compare_before = $total;

			$dt = array(
				'i_code' => $i_code,
				'compare_type' => $compare_type,
				'compare_now' => $compare_now,
				'compare_before' => $compare_before,
				'date' => date('Y-m-d'),
				'time' => date('H:i:s'),
				'timestamp' => time(),
			);
			$this->Scheme_Model->insert_fun("tbl_medicine_compare", $dt);
		}
		echo "NetRate Medicine Working";
	}
}