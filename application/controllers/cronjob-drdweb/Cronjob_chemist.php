<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_chemist extends CI_Controller 
{
	public function __construct(){

		parent::__construct();

		// Load model
	}
	
	public function active_inactive_chemist()
	{
		$this->db->query("update tbl_chemist_other set block='0'");

		$result = $this->db->query("SELECT tbl_chemist.code,tbl_chemist.altercode FROM `tbl_chemist` join tbl_chemist_other on tbl_chemist
		.code=tbl_chemist_other.code WHERE tbl_chemist.STATUS='*'")->result();
		foreach($result as $row){
			echo $row->code. " :-> " .$row->altercode;
			echo "<br>";
			$this->db->query("update tbl_chemist_other set block='1' where code='$row->code'");
			$this->db->query("update tbl_android_device_id set logout='1' where user_type='chemist' and chemist_id='$row->altercode'");
		}
	}
}