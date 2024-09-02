<?php
header('Content-Type: application/json');
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeOrderLimit extends CI_Controller
{
	public function __construct(){

		parent::__construct();

	}
	public function set_order_limit($limit)
	{
		if(!empty($limit)){
			$this->db->query("UPDATE tbl_acm_other set order_limit='$limit',website_limit=='$limit',android_limit=='$limit'");
			echo json_encode(array("message" => "order limit set at $limit"));
		}
	}
}