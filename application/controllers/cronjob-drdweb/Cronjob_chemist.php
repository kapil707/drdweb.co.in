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

		$result = $this->db->query("select * from tbl_chemist where status='*'")->result();
		foreach($result as $row){

			$this->db->query("update tbl_chemist_other set block='1' where code='$row->code'");
			$this->db->query("update tbl_android_device_id  set logout='1' where user_type='chemist' and chemist_id='$row->altercode'");

		}
	}
}