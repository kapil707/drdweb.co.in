<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_medicine extends CI_Controller {

	var $Page_title = "Manage Medicine";
	var $Page_name  = "manage_medicine";
	var $Page_view  = "manage_medicine";
	var $Page_menu  = "manage_medicine";
	var $page_controllers = "manage_medicine";
	var $Page_tbl   = "tbl_medicine";
	public function index()
	{
		$page_controllers = $this->page_controllers;
		redirect("admin/$page_controllers/view");
	}
	public function view()
	{
		error_reporting(0);
		/******************session***********************/
		$user_id = $this->session->userdata("user_id");
		$user_type = $this->session->userdata("user_type");
		/******************session***********************/
		
		$Page_title = $this->Page_title;
		$Page_name 	= $this->Page_name;
		$Page_view 	= $this->Page_view;
		$Page_menu 	= $this->Page_menu;
		$Page_tbl 	= $this->Page_tbl;
		$page_controllers 	= $this->page_controllers;
		
		$this->Admin_Model->permissions_check_or_set($Page_title,$Page_name,$user_type);
		
		$data['title1'] = $Page_title." || View";
		$data['title2'] = "View";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;		
		$this->breadcrumbs->push("Admin","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("View","admin/$page_controllers/view");
		
		$tbl = $Page_tbl;
		
		$data['url_path'] 	= base_url()."uploads/$page_controllers/photo/resize/";
		$upload_path 		= "./uploads/$page_controllers/photo/main/";
		$upload_resize 		= "./uploads/$page_controllers/photo/resize/";

		$result = $this->db->query("select * from $tbl order by id desc")->result();
		$data["result"] = $result;
		
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}

	public function get_medicine_count() {

		$this->db->select('id');
		$this->db->from('tbl_medicine');
	
		$query = $this->db->get();
		$total_medicine = $query->num_rows();

		// Combine both results into a single array
		$result = array(
			'total_medicine' => $total_medicine
		);

		// Output the result as JSON
		echo json_encode($result);
	}
}