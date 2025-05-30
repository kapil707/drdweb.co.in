<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_medicine_menu extends CI_Controller {

	var $Page_title = "Manage Medicine Menu";
	var $Page_name  = "manage_medicine_menu";
	var $Page_view  = "manage_medicine_menu";
	var $Page_menu  = "manage_medicine_menu";
	var $page_controllers = "manage_medicine_menu";
	var $Page_tbl   = "tbl_main_category";
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
		
		$data['url_path'] 	= base_url()."uploads/manage_company_division/photo/resize/";
		$upload_path 		= "./uploads/manage_company_division/photo/main/";
		$upload_resize 		= "./uploads/manage_company_division/photo/resize/";
		
		$find_medicine_id = $find_medicine_company_id = $find_medicine_company_division = "";
		$short_order = 0;
		extract($_POST);
		if(isset($Submit))
		{	
			$item_code 			= $find_medicine_id;
			$company_code 		= $find_medicine_company_id;
			$company_division 	= $find_medicine_company_division;

			if($function_type==3){
				$company_code = $find_medicine_category;
			}

			if(empty($short_order)){
				$short_order = 0;
			}	

			if (!empty($_FILES["image"]["name"]))
			{
				$this->Image_Model->uploadTo = $upload_path;
				$image = $this->Image_Model->upload($_FILES['image']);
				$image = str_replace($upload_path,"",$image);
				
				$this->Image_Model->newPath   = $upload_resize;
				$this->Image_Model->newWidth  = 500;
				$this->Image_Model->newHeight = 250;
				$this->Image_Model->resize();
			}		
			else
			{
				$image = "";
			}
			
			$result = "";
			$dt = array(
				'main_type'=>'menu',
				'main_type_id'=>$main_type_id,
				'short_order'=>$short_order,
				'title'=>$title,
				'url'=>$url,
				'function_type'=>$function_type,
				'item_code'=>$item_code,
				'company_code'=>$company_code,
				'company_division'=>$company_division,
				'image'=>$image,
				'status'=>$status,
				'date' => date('Y-m-d'),
				'time' => date('H:i:s'),
				'timestamp' => time(),
			);
			$result = $this->Scheme_Model->insert_fun($tbl,$dt);
			if($result)
			{
				$message = "Add Successfully.";
				$this->session->set_flashdata("message_type","success");
				redirect(base_url()."admin/$page_controllers/view");
			}
			else
			{
				$message = "Not Add.";
				$this->session->set_flashdata("message_type","error");
			}
		}
		
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/add",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/manage_medicine/find_medicine",$data);
		$this->load->view("admin/manage_medicine/find_medicine_company",$data);
		$this->load->view("admin/manage_medicine/find_medicine_category",$data);
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
		
		$data['url_path'] 	= base_url()."uploads/$page_controllers/photo/resize/";
		$upload_path 		= "./uploads/$page_controllers/photo/main/";
		$upload_resize 		= "./uploads/$page_controllers/photo/resize/";
				
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}

	public function edit($id)
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
		
		$data['title1'] = $Page_title." || Edit";
		$data['title2'] = "Edit";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;		
		$this->breadcrumbs->push("Edit","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Edit","admin/$page_controllers/edit");
		
		$tbl = $Page_tbl;
		
		$data['url_path'] 	= base_url()."uploads/manage_company_division/photo/resize/";
		$upload_path 		= "./uploads/manage_company_division/photo/main/";
		$upload_resize 		= "./uploads/manage_company_division/photo/resize/";
		
		extract($_POST);
		if(isset($Submit))
		{	
			$item_code = $find_medicine_id;
			$company_code = $find_medicine_company_id;
			$company_division = $find_medicine_company_division;

			if($function_type==3){
				$company_code = $find_medicine_category;
			}

			if(empty($short_order)){
				$short_order = 0;
			}	

			if (!empty($_FILES["image"]["name"]))
			{
				$this->Image_Model->uploadTo = $upload_path;
				$image = $this->Image_Model->upload($_FILES['image']);
				$image = str_replace($upload_path,"",$image);
				
				$this->Image_Model->newPath   = $upload_resize;
				$this->Image_Model->newWidth  = 500;
				$this->Image_Model->newHeight = 250;
				$this->Image_Model->resize();
			}		
			else
			{
				$image = $old_image;
			}
			
			$result = "";
			$dt = array(
				'main_type'=>'menu',
				'main_type_id'=>$main_type_id,
				'short_order'=>$short_order,
				'title'=>$title,
				'url'=>$url,
				'function_type'=>$function_type,
				'item_code'=>$item_code,
				'company_code'=>$company_code,
				'company_division'=>$company_division,
				'image'=>$image,
				'status'=>$status,
				'date' => date('Y-m-d'),
				'time' => date('H:i:s'),
				'timestamp' => time(),
			);
			$where = array('id'=>$id);
			$result = $this->Scheme_Model->edit_fun($tbl,$dt,$where);		
			if($result)
			{
				$message = "Edit Successfully.";
				$this->session->set_flashdata("message_type","success");
				redirect(current_url());
			}
			else
			{
				$message = "Not Add.";
				$this->session->set_flashdata("message_type","error");
			}
		}
		
		$query = $this->db->query("select * from $tbl where id='$id'");
  		$data["result"] = $query->result();
		$data["id"] = $id;
		
		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/edit",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/manage_medicine/find_medicine_company",$data);
	}
	
	public function delete_rec()
	{
		$id = $_POST["id"];
		$Page_title = $this->Page_title;
		$Page_tbl = $this->Page_tbl;
		$result = $this->db->query("delete from $Page_tbl where id='$id'");
		if($result)
		{
			$message = "Delete Successfully.";
		}
		else
		{
			$message = "Not Delete.";
		}
		$message = $Page_title." - ".$message;
		//$this->Admin_Model->Add_Activity_log($message);
		echo "ok";
	}

	public function delete_photo()
	{
		$path = $_POST["path"];
		$type_me = $_POST["type_me"];
		
		$Page_title = $this->Page_title;
		$Page_name 	= $this->Page_name;
		$Page_view 	= $this->Page_view;
		$Page_menu 	= $this->Page_menu;
		$Page_tbl 	= $this->Page_tbl;
		$page_controllers 	= $this->page_controllers;
				
		$upload_path = "./uploads/$page_controllers/photo/";
		$result = $this->db->query("update $Page_tbl set $type_me='' where $type_me='$path'");
		if($result!="")
		{
			$filename = $upload_path.$path;
			$message = "Delete Photo Successfully.";
			if (file_exists($filename)) 
			{
    			unlink($filename);
			}
		}
		else
		{
			$message = "Photo Not Update.";
		}
		$message = $Page_title." - ".$message;
		//$this->Admin_Model->Add_Activity_log($message);
		echo "ok";
	}

	public function view_api() {		

		$jsonArray = array();
		$items = "";
		$i = 1;
		$Page_tbl = $this->Page_tbl;

		$result = $this->db->query("SELECT * FROM $Page_tbl where main_type='menu' order by id desc");
		$result = $result->result();
		foreach($result as $row) {

			$sr_no = $i++;
			$id = $row->id;

			$short_order = $row->short_order;
			$type = "Menu ($row->main_type_id)";
			
			if($row->function_type=="0"){
				$function_type = "Not Need";
				$title = "N/a";
			}

			$title = $row->title;
			if($row->function_type=="1"){ 

				$function_type = "Medicine";
				$selected_type = "Medicine ($row->item_code)";
				
				$row1 =  $this->db->query("select item_name,i_code from tbl_medicine where i_code='$row->item_code'")->row();

				$url = "https://www.drdistributor.com/md/$row->item_code";
				$title = "<a href='".$url."' target='_blank'>$row->title</a>";
			}

			if($row->function_type=="2"){ 

				$function_type = "Company/Division";

				$company_division = $row->company_division;
				if(empty($company_division)){
					$company_division = "N/a";
				}
				$selected_type = "Company ($row->company_code) / Division ($company_division)";

				//$url = "https://www.drdistributor.com/compney/$url";
				$url = "https://www.drdistributor.com/cc/$row->url";
				$title = "<a href='".$url."' target='_blank'>$title</a>";
			}

			if($row->function_type=="3"){ 
				
				$function_type = "Category";
				$selected_type = "Category ($row->company_code) "; 

				$url = "https://www.drdistributor.com/cc/$row->url";
				$title = "<a href='".$url."' target='_blank'>$title</a>";
			}

			$image = $row->image;
			$datetime = date("d-M-y @ H:i:s", $row->timestamp);

			if(!empty($image)) {
				$image = base_url()."uploads/manage_company_division/photo/resize/".$image;
			}
			
			$dt = array(
				'sr_no' => $sr_no,
				'id' => $id,
				'short_order' => $short_order,
				'type' => $type,
				'function_type' => $function_type,
				'selected_type' => $selected_type,
				'title' => $title,
				'image' => $image,
				'datetime'=>$datetime,
			);
			$jsonArray[] = $dt;
		}
		if(!empty($jsonArray)){
			$items = $jsonArray;
			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $items,
			);
		}else{
			$response = array(
				'success' => "0",
				'message' => '502 error',
			);
		}
		
        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}