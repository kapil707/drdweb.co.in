<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeHotSellingManage extends CI_Controller
{
	public function upload_hot_selling()
	{
		//error_reporting(0);
		$data = json_decode(file_get_contents('php://input'),true);
		$items = $data["items"];
		if(!empty($items)){
			$this->db->query("TRUNCATE TABLE tbl_hot_selling;");
		}
		foreach($items as $row)
		{
			$item_code 	= $row["item_code"];
			$total 		= $row["total"];
			$datetime 	= $row["datetime"];
			
			$dt = array(
				'item_code'=>$item_code,
				'total'=>$total,
				'datetime'=>$datetime,
			);
			$this->Scheme_Model->insert_fun("tbl_hot_selling", $dt);
		}
		echo "Upload Hot Selling Working";
	}
}