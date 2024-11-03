<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_Hot_Selling extends CI_Controller
{
	public function __construct(){
		parent::__construct();
	}

	public function run_job()
	{
		$date = date("Y-m-d");
		$this->db->query("delete from tbl_medicine_compare where compare_type='hot_selling'");

		$this->db->select('itemc, COUNT(*) as total_count');
        $this->db->from('tbl_invoice_item');
        $this->db->where('date', $date);
        $this->db->group_by('itemc');
        $this->db->order_by('total_count', 'DESC');
        $this->db->limit(25);

        $query = $this->db->get()->result();
		foreach($query as $row)
		{
			$i_code 	= $row->itemc;
			$total 		= $row->total_count;
			$datetime 	= date("d-M-Y h:i a");
			
			$dt = array(
				'i_code'=>$i_code,
				'total'=>$total,
				'datetime'=>$datetime,
			);
			$this->Scheme_Model->insert_fun("tbl_hot_selling", $dt);

			/********************************************* */
			$compare_type = "hot_selling";
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