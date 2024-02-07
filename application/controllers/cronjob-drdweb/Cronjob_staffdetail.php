<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_staffdetail extends CI_Controller 
{
	public function staffdetail_other_reset()
	{
		$time  = time();
		$vdt   = date("Y-m-d",$time);
		$this->db->query("update tbl_staffdetail_other set daily_date='$vdt',download_status='0'"); 

		$result = $this->db->query("select * from tbl_staffdetail_other")->result();
		foreach($result as $row)
		{
			$row1 = $this->db->query("select * from tbl_staffdetail where code='$row->code'")->row();
			if(empty($row1->id))
			{
				$code = $row->code;
				$this->db->query("delete from tbl_staffdetail_other where code='$code'");
			}
		}
		
		$this->staffdetail_folder_create();
		
		echo "Staffdetail Other Reset Working";
	}
	
	
	public function staffdetail_folder_create()
	{
		$time  = time();
		$vdt   = date("Y-m-d",$time);

		if (!file_exists('corporate_report/'.$vdt)) {
			mkdir('corporate_report/'.$vdt, 0777, true);
		}
	}
}