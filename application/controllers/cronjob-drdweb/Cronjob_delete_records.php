<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_delete_records extends CI_Controller 
{
    public function delete_old_records()
	{	    
		$time  = time();
		$vdt   = date("Y-m-d",$time);
		$day1  = date("Y-m-d", strtotime("-1 days", $time));
		$day7  = date("Y-m-d", strtotime("-7 days", $time));
		$day15  = date("Y-m-d", strtotime("-15 days", $time));
		$day30 = date("Y-m-d", strtotime("-30 days", $time));
		$day45 = date("Y-m-d", strtotime("-45 days", $time));
		$day60 = date("Y-m-d", strtotime("-60 days", $time));
		$day365 = date("Y-m-d", strtotime("-365 days", $time));

		$db2 = $this->load->database('default2', TRUE);
		$db2->query("DELETE FROM `tbl_medicine_compare_final` WHERE date<='$day30'");
		
		$db_master = $this->load->database('db_master', TRUE);
		$db_master->query("DELETE FROM `drd_master_tbl_delivery` WHERE vdt<='$day7'");	
		
		$this->db->query("DELETE FROM `tbl_top_search` WHERE date<='$day30'");
		
		$this->db->query("DELETE FROM `drd_import_file` WHERE date<='$day7'");
		$this->db->query("DELETE FROM `drd_temp_rec` WHERE date<='$day7' and status='1'");
		$this->db->query("DELETE FROM `tbl_whatsapp_message` WHERE date<='$day7'");
		$this->db->query("DELETE FROM `tbl_whatsapp_group_message` WHERE date<='$day7'");
		
		$this->db->query("DELETE FROM `tbl_order` WHERE date<='$day60'");
		$this->db->query("DELETE FROM `tbl_android_notification` WHERE date<='$day60'");
		$this->db->query("DELETE FROM `tbl_low_stock` WHERE date<='$day15'");
		$this->db->query("DELETE FROM `tbl_delete_import` WHERE date<='$day60'");
		$this->db->query("DELETE FROM `tbl_android_device_id` WHERE date<='$day60'");

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

		$result = $this->db->query("SELECT * FROM `tbl_email_send`  WHERE `date`<'$day60'")->result();
		foreach($result as $row)
		{
			$id = $row->id;
			$file_name1 = $row->file_name1;
			if($file_name1)
			{
				unlink($file_name1);
			}
			$file_name2 = $row->file_name2;
			if($file_name2)
			{
				unlink($file_name2);
			}
			$file_name3 = $row->file_name3;
			if($file_name3)
			{
				unlink($file_name3);
			}
			$this->db->query("DELETE FROM `tbl_email_send` WHERE id='$id'");
		}
		
		echo "Delete Old Records Working";
	}
}