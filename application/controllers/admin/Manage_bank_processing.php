<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_bank_processing extends CI_Controller {
	var $Page_title = "Manage Bank Processing";
	var $Page_name  = "manage_bank_processing";
	var $Page_view  = "manage_bank_processing";
	var $Page_menu  = "manage_bank_processing";
	var $page_controllers = "manage_bank_processing";
	var $Page_tbl   = "tbl_bank_processing";
	public function __construct()
    {
        parent::__construct();
    }
	public function index()
	{
		$page_controllers = $this->page_controllers;
		redirect("admin/$page_controllers/view");
	}
	public function add()
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

		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$data['upload_path'] = "./uploads/$page_controllers/myfile/";
		$upload_thumbs_path = "./uploads/$page_controllers/photo/thumbs/";		
		$system_ip = $this->input->ip_address();

		$data["filename"] = "";
		extract($_POST);
		if (isset($Submit)) {
			$message_db = "";
			$time = time();
			$date = date("Y-m-d", $time);

			if (!empty($_FILES["myfile"]["name"])) {
				$upload_image = "./uploads/$page_controllers/myfile/";

				ini_set('upload_max_filesize', '10M');
				ini_set('post_max_size', '10M');
				ini_set('max_input_time', 300);
				ini_set('max_execution_time', 300);
		
				$config['upload_path'] = $upload_image;  // Define the directory where you want to store the uploaded files.
				$config['allowed_types'] = '*';  // You may want to restrict allowed file types.
				$config['max_size'] = 0;  // Set to 0 to allow any size.

				$new_name = time().$_FILES["myfile"]['name'];
				$config['file_name'] = $new_name;
		
				$this->load->library('upload', $config);
		
				if (!$this->upload->do_upload('myfile')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					print_r($error);
				} else {
					$data1 = $this->upload->data();
					$image = ($data1['file_name']);
					//$this->load->view('upload_success', $data);
				}
			}
			$data["filename"] = $image;
			

			/*$result = "";
			$dt = array(
				'status' => $status,
			);
			$result = $this->BankModel->insert_fun("tbl_bank_file", $dt);
			$change_text = "hello";
			if ($result) {
				$message_db = "$change_text - Edit Successfully.";
				$message = "Edit Successfully.";
				$this->session->set_flashdata("message_type", "success");
			} else {
				$message_db = "$change_text - Not Add.";
				$message = "Not Add.";
				$this->session->set_flashdata("message_type", "error");
			}
			if ($message_db != "") {
				$message = $Page_title . " - " . $message;
				$message_db = $Page_title . " - " . $message_db;
				$this->session->set_flashdata("message_footer", "yes");
				$this->session->set_flashdata("full_message", $message);
				$this->Admin_Model->Add_Activity_log($message_db);
				if ($result) {
					//redirect(current_url());
					//redirect(base_url()."admin/$page_controllers/view");
				}
			}*/
		}

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

		$_SESSION["latitude"] = 
		$_SESSION["longitude"] = "";		

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

		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";
		
		$query = $this->db->query("SELECT * FROM `tbl_bank_processing`");
		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
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

		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";
		$upload_thumbs_path = "./uploads/$page_controllers/photo/thumbs/";		
		$system_ip = $this->input->ip_address();


		$status = $new_password = "";
		extract($_POST);
		if (isset($Submit)) {
			$message_db = "";
			$time = time();
			$date = date("Y-m-d", $time);
			
			$where = array('code' => $id);

			if (!empty($_FILES["image"]["name"])) {
				$img = "image";
				$url_path = "./uploads/$page_controllers/photo/";

				$query = $this->db->query("select * from $tbl where id='$id'");
				$row11 = $query->row();
				$filename = $url_path . $row11->$img;
				unlink($filename);
				$name1 = "photo";

				$imagename = $_FILES["image"]['name'];
				$uploadedfile = $_FILES["image"]['tmp_name'];
				$image = "";

				$valid_formats = array("jpg", "png", "gif", "bmp", "jpeg", "PNG", "JPG", "JPEG", "GIF", "BMP");
				$ext = strtolower($this->Scheme_Model->getExtension($imagename));
				if (in_array($ext, $valid_formats)) {
					//$ext = "jpeg";
					$actual_image_name = $name1 . "_" . time() . "." . $ext;
					$widthArray = array(300);
					foreach ($widthArray as $newwidth) {
						$image = $this->Scheme_Model->compressImage($ext, $uploadedfile, $upload_path, $actual_image_name, $newwidth);
						$image = $newwidth . "_" . $image;
					}
				}
			} else {
				$image = $old_image;
			}

			//$exp_date = date("Y-m-d", strtotime($exp_date));
			$result = "";
			$dt = array(
				'status' => $status,
			);
			if ($new_password != "") {
				$password = $new_password;
				$password = md5($password);

				$dt = array(
					'status' => $status,
					'password' => $password,
				);
			}

			$result = $this->Scheme_Model->edit_fun("tbl_master_other", $dt, $where);
			if ($result) {
				$message_db = "$change_text - Edit Successfully.";
				$message = "Edit Successfully.";
				$this->session->set_flashdata("message_type", "success");
			} else {
				$message_db = "$change_text - Not Add.";
				$message = "Not Add.";
				$this->session->set_flashdata("message_type", "error");
			}
			if ($message_db != "") {
				$message = $Page_title . " - " . $message;
				$message_db = $Page_title . " - " . $message_db;
				$this->session->set_flashdata("message_footer", "yes");
				$this->session->set_flashdata("full_message", $message);
				$this->Admin_Model->Add_Activity_log($message_db);
				if ($result) {
					redirect(current_url());
					//redirect(base_url()."admin/$page_controllers/view");
				}
			}
		}

		$query = $this->db->query("select tbl_master.id,tbl_master.code,tbl_master.altercode,tbl_master.name,tbl_master.email,tbl_master.mobile,tbl_master.status,tbl_master_other.exp_date,tbl_master_other.status as status1 from tbl_master left join tbl_master_other on tbl_master.code=tbl_master_other.code where tbl_master.code='$id' order by tbl_master.id desc");
  		$data["result"] = $query->result();		
		
		$row = $this->db->query("select id from tbl_master_other where code=$id")->row();
  		if(empty($row->id)){
			$dt = array(
			'code'=>$id,
			'status'=>0,
			'exp_date'=>0,
			'updated_at'=>0,
			'password_change'=>0,
			'password'=>'',
			'latitude'=>'',
			'longitude'=>'',
			'date'=>'',
			'time'=>'',
			'datetime'=>'',
			'firebase_token'=>'',
			'image'=>'',
			);
			$this->Scheme_Model->insert_fun("tbl_master_other",$dt);
		}

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/edit",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
}