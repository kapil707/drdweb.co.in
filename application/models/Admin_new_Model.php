<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin_new_Model extends CI_Model  
{  
	public function get_online_users()
	{
		//$this->db->distinct();
		//$this->db->select('DISTINCT `order_id`'); //You may use $this->db->distinct('name');
		$date = date("Y-m-d");
		$where = array('date'=>$date);
		$this->db->select('*');
		$this->db->where($where);		
		$this->db->order_by("id","desc");
		return $this->db->get("tbl_user_activity_log")->result();
	}
}