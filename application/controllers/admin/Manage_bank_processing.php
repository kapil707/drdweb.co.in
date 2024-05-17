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
		$this->load->model("model-drdweb/BankModel");
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
			$filename = $image;

			$account_no 			= "A";
			$branch_no 				= "B";
			$statment_date 			= "C";
			$amount 				= "F";
			$enter_date 			= "G";
			$value_date 			= "H";
			$bank_reference 		= "I";
			$customer_reference 	= "J";
			$narrative 				= "K";
			$transaction_description= "L";

			$start_row = "13";

			$upload_path = "uploads/manage_bank_processing/myfile/";
			$excelFile = $upload_path.$filename;
			$i=1;
			if(file_exists($excelFile))
			{
				$this->load->library('excel');
				$objPHPExcel = PHPExcel_IOFactory::load($excelFile);
				foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
				{
					$highestRow = $worksheet->getHighestRow();
					for ($row=$start_row; $row<=$highestRow; $row++)
					{
						$amount1 = $worksheet->getCell($amount.$row)->getValue();
						//$statment_date1 = $worksheet->getCell($statment_date.$row)->getValue();
						$statment_date1 = $worksheet->getCell($value_date.$row)->getValue();
						$text = $worksheet->getCell($narrative.$row)->getValue();
						//$text = trim($text);
						//$text = str_replace("'", "", $text);
						//$text = "+91-9899067942 411801191476 FROM GUPTAMEDICALSTORE 9300966180 CITI0000 9026 NA UBIN0579203";

						$transaction_description1 = $worksheet->getCell($transaction_description.$row)->getValue();
						
						//$mydate = date('Y-m-d', strtotime($statment_date1));
						echo $statment_date1 = date('Y-m-d', strtotime($statment_date1));
						echo "<br>";

						// echo $i.". ";
						// $i++;
						// echo $text;
						//$text = str_replace("@ ", "@", $text);
						//echo $text = preg_replace('/@\s/', "@", $text, 1);

						$received_from = "";
						// Use regular expression to extract text after "FROM"

						$from_value = "";
						preg_match("/FROM\s+(\d+)@\s+(\w+)/", $text, $matches);
						if (!empty($matches) && empty($from_value)){
							$received_from = trim($matches[1])."@".trim($matches[2]);
							$received_from = str_replace("'", "", $received_from);
							$received_from = str_replace(" ", "", $received_from);
							$received_from = str_replace("\n", "", $received_from);
							//$from_value = "<b>find: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
							$from_value = $received_from;
						}

						
						preg_match("/FROM\s+(\d+)\s+@\s*(\w+)/", $text, $matches);
						if (!empty($matches) && empty($from_value)){
							$received_from = trim($matches[1])."@".trim($matches[2]);
							$received_from = str_replace("'", "", $received_from);
							$received_from = str_replace(" ", "", $received_from);
							$received_from = str_replace("\n", "", $received_from);
							//$from_value = "<b>find2: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
							$from_value = $received_from;
						}

						preg_match("/FROM\s+(\w+)\d+@\s*(\w+)/", $text, $matches);
						if (!empty($matches) && empty($from_value)){
							$received_from = trim($matches[1])."@".trim($matches[2]);
							$received_from = str_replace("'", "", $received_from);
							$received_from = str_replace(" ", "", $received_from);
							$received_from = str_replace("\n", "", $received_from);
							//$from_value = "<b>find3: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
							$from_value = $received_from;
						}

						preg_match("/FROM\s+([^\s@]+)\s+@\s*(\w+)/", $text, $matches);
						if (!empty($matches) && empty($from_value)){
							$received_from = trim($matches[1])."@".trim($matches[2]);
							$received_from = str_replace("'", "", $received_from);
							$received_from = str_replace(" ", "", $received_from);
							$received_from = str_replace("\n", "", $received_from);
							//$from_value = "<b>find4: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
							$from_value = $received_from;
						}

						preg_match("/FROM\s+([^\@]+)@\s*(\w+)/", $text, $matches);
						if (!empty($matches) && empty($from_value)){
							$received_from = trim($matches[1])."@".trim($matches[2]);
							$received_from = str_replace("'", "", $received_from);
							$received_from = str_replace(" ", "", $received_from);
							$received_from = str_replace("\n", "", $received_from);
							//$from_value = "<b>find5: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
							$from_value = $received_from;
						}

						preg_match("/FROM\s+(.*)/", $text, $matches);
						if (!empty($matches) && empty($from_value)){
							$received_from = trim($matches[1]);
							//$received_from = str_replace("'", "", $received_from);
							//$received_from = str_replace(" ", "", $received_from);
							//$received_from = str_replace("\n", "", $received_from);
							//$from_value = "<b>find6: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
							$from_value = $received_from;
						}

						$upi_no = $orderid = $worksheet->getCell($customer_reference.$row)->getValue();
						
						$_id = 1;
						$received_from = $from_value;
						if(!empty($received_from)){
							$status = 0;
							$type = "Statment";
							$dt = array(
								'status'=>$status,
								'amount'=>$amount1,
								'date'=>$statment_date1,
								'received_from'=>$received_from,
								'upi_no'=>$upi_no,
								'orderid'=>$orderid,
								'type'=>$type,
								'_id'=>$_id,
							);
							$this->BankModel->insert_fun("tbl_bank_processing", $dt);
						}
						/*************************** */					
					}
				}
			}
			
			redirect(base_url()."admin/$page_controllers/view");
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
		
		$query = $this->BankModel->select_query("SELECT upi_no,process_name,date,process_value,find_chemist_id,find_invoice_chemist_id GROUP_CONCAT(id SEPARATOR ', ') AS id,GROUP_CONCAT(amount SEPARATOR ', ') AS amount, GROUP_CONCAT(orderid SEPARATOR ', ') AS orderid, GROUP_CONCAT(type SEPARATOR ', ') AS type FROM `tbl_bank_processing` GROUP BY upi_no,process_name,date,process_value,find_chemist_id,find_invoice_chemist_id");
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

	public function add_done_chemist_id()
	{
		$id 			= $_POST["id"];
		$done_chemist_id = $_POST["done_chemist_id"];
		$received_from 	= $_POST["received_from"];
		if(!empty($id) && !empty($done_chemist_id) && !empty($received_from)){

			/*$query = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` where string_value='$received_from'");
			$row = $query->row();
			if(empty($row)){
				$dt = array(
					'chemist_id' => $done_chemist_id,
					'string_value' => $received_from,
					'date'=>date('Y-m-d'),
					'time'=>time(),
					'user_id'=>$this->session->userdata("user_id")
				);
				$this->BankModel->insert_fun("tbl_bank_chemist", $dt);
			}

			/********************************************* */
			// agar kisi from user ko chmist say add kartay ha to jitnay be from user ha sab ka status 0 ho jaya or wo re-process hota ha 
			/*
			$where = array(				
				'received_from'=>$received_from,
				'status' => '1',
			);
			$dt = array(
				'status' => '0',
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);*/
			
			/********************************************* */
			$where = array(
				'id' => $id,
			);
			$dt = array(
				'done_chemist_id'=>$done_chemist_id,
				'done_status' => '1',
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}

	public function add_received_from_chemist_id()
	{
		$chemist_id 	= $_POST["chemist_id"];
		$received_from 	= $_POST["received_from"];
		if(!empty($chemist_id) && !empty($received_from)){

			$query = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` where string_value='$received_from'");
			$row = $query->row();
			if(empty($row)){
				$dt = array(
					'chemist_id' => $chemist_id,
					'string_value' => $received_from,
					'date'=>date('Y-m-d'),
					'time'=>time(),
					'user_id'=>$this->session->userdata("user_id")
				);
				$this->BankModel->insert_fun("tbl_bank_chemist", $dt);
			}

			/********************************************* */
			// agar kisi from user ko chmist say add kartay ha to jitnay be from user ha sab ka status 0 ho jaya or wo re-process hota ha 
			$where = array(				
				'received_from'=>$received_from,
			);
			$dt = array(
				'status' => '0',
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}
}