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
		$this->db->query("TRUNCATE TABLE tbl_hot_selling;");
		$result = $this->db->query("SELECT `itemc`, COUNT(*) AS total_count FROM `tbl_invoice_item` WHERE `date` = '$date' GROUP BY `itemc` ORDER BY `total_count` DESC LIMIT 25")->result();
		foreach($result as $row)
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
		}
		echo "Hot Selling Working";
	}
}