<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_medicine_use extends CI_Controller {
	var $Page_title = "Manage Medicine Use";
	var $Page_name  = "manage_medicine_use";
	var $Page_view  = "manage_medicine_use";
	var $Page_menu  = "manage_medicine_use";
	var $page_controllers = "manage_medicine_use";
	var $Page_tbl   = "tbl_medicine_use";
	public function __construct(){
		parent::__construct();

		$folder_path = './medicine_use/*';
		$files = glob($folder_path);
		
		foreach ($files as $file) {
			$item_code = basename($file);

			$row = $this->db->query("SELECT * from tbl_medicine_use where item_code='$item_code'")->row();
			if(empty($row)){
				$dt = array(
					'item_code'=>$item_code,
				);
				$this->Scheme_Model->insert_fun("tbl_medicine_use",$dt);
			}
		}
	}
	public function index()
	{
		$page_controllers = $this->page_controllers;
		redirect("admin/$page_controllers/view");
	}
	
	public function add()
	{
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

		$data['title1'] = $Page_title." || Add";
		$data['title2'] = "Add";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;
		$this->breadcrumbs->push("Admin","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Add","admin/$page_controllers/add");	

		$tbl = $Page_tbl;	

		$data['url_path'] 	= base_url()."uploads/$page_controllers/photo/";
		$upload_path 		= "./uploads/$page_controllers/photo/";
		$upload_resize 		= "./uploads/$page_controllers/photo/";	

		/*$system_ip = $this->input->ip_address();
		$user_type = $status = "";
		extract($_POST);
		if(isset($Submit))
		{
			$message_db = "";
			
			$time = time();
			$date = date("Y-m-d",$time);			

			if (!empty($_FILES["image"]["name"]))
			{
				$ext = end(explode(".", $_FILES['image']['name']));
				$filename = time().".".$ext;
				
				$fileName = $_FILES['image']['name'];
				$fileTmpName = $_FILES['image']['tmp_name'];
				$fileSize = $_FILES['image']['size'];
				$fileError = $_FILES['image']['error'];
				$fileType = $_FILES['image']['type'];
				
				$uploadedfile = $_FILES['image']['tmp_name'];
				move_uploaded_file($uploadedfile, $upload_path.$filename);
				$image = $filename;
			}
			else
			{
				$image = "";
			}
			
			$description = "";
			
			$result = "";
			$dt = array(
			'status'=>$status,
			'date'=>$date,
			'time'=>$time,
			'item_code'=>$item_code,
			'description'=>$description,
			);
			$result = $this->Scheme_Model->insert_fun($tbl,$dt);
			$name = base64_decode($name);
			if($result)
			{
				$message_db = "($name) -  Add Successfully.";
				$message = "Add Successfully.";
				$this->session->set_flashdata("message_type","success");
			}
			else
			{
				$message_db = "($property_title) - Not Add.";
				$message = "Not Add.";
				$this->session->set_flashdata("message_type","error");
			}
			if($message_db!="")
			{
				$message = $Page_title." - ".$message;
				$message_db = $Page_title." - ".$message_db;
				$this->session->set_flashdata("message_footer","yes");
				$this->session->set_flashdata("full_message",$message);
				$this->Admin_Model->Add_Activity_log($message_db);
				if($result)
				{
					redirect(base_url()."admin/$page_controllers/view");
				}
			}
		}	*/	

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/add",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}

	public function view()
	{
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

		$data['url_path'] 	= base_url()."uploads/$page_controllers/photo/";
		$upload_path 		= "./uploads/$page_controllers/photo/";
		$upload_resize 		= "./uploads/$page_controllers/photo/";	

		
		$query = $this->db->query("SELECT DISTINCT(tbl_medicine_use.item_code),tbl_medicine.item_name,tbl_medicine.item_code,tbl_medicine.i_code FROM tbl_medicine_use left join tbl_medicine on tbl_medicine_use.item_code = tbl_medicine.i_code");
		$data["result"] = $query->result();
		
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
	public function edit($id)
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

		$data['title1'] = $Page_title." || Edit";
		$data['title2'] = "Edit";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;

		$this->breadcrumbs->push("Edit","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Edit","admin/$page_controllers/edit");		

		$tbl = $Page_tbl;	

		$data['url_path'] 	= base_url()."uploads/$page_controllers/photo/resize/";
		$upload_path 		= "./uploads/$page_controllers/photo/main/";
		$upload_resize 		= "./uploads/$page_controllers/photo/resize/";
		
		

		/*$system_ip = $this->input->ip_address();
		extract($_POST);
		if(isset($Submit))
		{
			$itemid = $i_code;
			$message_db = "";
			
			$time = time();
			$date = date("Y-m-d",$time);
			$where = array('id'=>$id);
			
			if (!empty($_FILES["image"]["name"]))
			{
				$this->Image_Model->uploadTo = $upload_path;
				$image = $this->Image_Model->upload($_FILES['image']);
				$image = str_replace($upload_path,"",$image);
				
				$this->Image_Model->newPath = $upload_resize;
				$this->Image_Model->newWidth = 1024;
				$this->Image_Model->newHeight = 250;
				$this->Image_Model->resize();
			}
			else
			{
				$image = $old_image;
			}
			
			
			if($funtype=="2")
			{
				$itemid = "";
			}
			
			if($funtype=="1")
			{
				$compid = "";
				$division = "";
			}
			
			if($funtype=="0")
			{
				$itemid = "";
				$compid = "";
				$division = "";
			}
			
			$result = "";
			$dt = array(
			'short_order'=>$short_order,
			'user_id'=>$user_id,
			'image'=>$image,
			'status'=>$status,
			'date'=>$date,
			'time'=>$time,
			'update_date'=>$date,
			'update_time'=>$time,
			'system_ip'=>$system_ip,
			'funtype'=>$funtype,
			'itemid'=>$itemid,
			'compid'=>$compid,
			'division'=>$division,
			);
			$result = $this->Scheme_Model->edit_fun($tbl,$dt,$where);
			$change_text = $title." - ($change_text)";	

			if($result)
			{
				$message_db = "$change_text - Edit Successfully.";
				$message = "Edit Successfully.";
				$this->session->set_flashdata("message_type","success");
			}
			else
			{
				$message_db = "$change_text - Not Add.";
				$message = "Not Add.";
				$this->session->set_flashdata("message_type","error");
			}
			if($message_db!="")
			{
				$message = $Page_title." - ".$message;
				$message_db = $Page_title." - ".$message_db;
				$this->session->set_flashdata("message_footer","yes");
				$this->session->set_flashdata("full_message",$message);
				$this->Admin_Model->Add_Activity_log($message_db);
				if($result)
				{
					redirect(current_url());
					//redirect(base_url()."admin/$page_controllers/view");
				}
			}
		}	*/	

		$data["row"] = $this->db->query("SELECT DISTINCT(tbl_medicine_use.item_code),tbl_medicine.item_name,tbl_medicine.item_code,tbl_medicine.i_code FROM tbl_medicine_use left join tbl_medicine on tbl_medicine_use.item_code = tbl_medicine.i_code where tbl_medicine.i_code='$id'")->row();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/edit",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
}