<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_must_buy extends CI_Controller
{
	public function __construct(){
		parent::__construct();
	}

	public function run_job()
	{
		$date = date("Y-m-d");
		$this->db->query("delete from tbl_medicine_compare where compare_type='must_buy'");

		$this->db->select('i_code, COUNT(*) as total_count');
        $this->db->from('tbl_cart');
        $this->db->where('date', $date);
		$this->db->where('status', 1);
        $this->db->group_by('i_code');
        $this->db->order_by('total_count', 'DESC');
        $this->db->limit(25);

        $query = $this->db->get()->result();
		foreach($query as $row)
		{
			$i_code 	= $row->i_code;
			$total 		= $row->total_count;

			/********************************************* */
			$compare_type = "must_buy";
			$compare_now = 0;
			$compare_before = 0;

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
		echo "Hot Selling Working";
	}
}