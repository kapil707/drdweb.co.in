<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_bank_statment extends CI_Controller {
	var $Page_title = "Manage Bank Statment";
	var $Page_name  = "manage_bank_statment";
	var $Page_view  = "manage_bank_statment";
	var $Page_menu  = "manage_bank_statment";
	var $page_controllers = "manage_bank_statment";
	var $Page_tbl   = "tbl_statment";
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
			$closing_ledger_balance = "D";
			$calculated_balances 	= "E";
			$amount 				= "F";
			$enter_date 			= "G";
			$value_date 			= "H";
			$bank_reference 		= "I";
			$customer_reference 	= "J";
			$narrative 				= "K";
			$transaction_description= "L";
			$iban_number			= "M";

			$start_row 				= "13";

			$upload_path = "uploads/manage_bank_statment/myfile/";
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
						$account_no1 = $worksheet->getCell($account_no.$row)->getValue();
						$branch_no1 = $worksheet->getCell($branch_no.$row)->getValue();
						$statment_date1 = $worksheet->getCell($statment_date.$row)->getValue();
						$closing_ledger_balance1 = $worksheet->getCell($closing_ledger_balance.$row)->getValue();
						$calculated_balances1 = $worksheet->getCell($calculated_balances.$row)->getValue();
						$amount1 = $worksheet->getCell($amount.$row)->getValue();
						$enter_date1 = $worksheet->getCell($enter_date.$row)->getValue();
						$value_date1 = $worksheet->getCell($value_date.$row)->getValue();
						$bank_reference1 = $worksheet->getCell($bank_reference.$row)->getValue();
						$customer_reference1 = $worksheet->getCell($customer_reference.$row)->getValue();
						$narrative1 = $worksheet->getCell($narrative.$row)->getValue();
						$transaction_description1 = $worksheet->getCell($transaction_description.$row)->getValue();
						$iban_number1 = $worksheet->getCell($iban_number.$row)->getValue();

						$value_date1 = date('Y-m-d', strtotime($value_date1));

						$dt = array(
							'account_no'=>$account_no1,
							'branch_no'=>$branch_no1,
							'statment_date'=>$statment_date1,
							'closing_ledger_balance'=>$closing_ledger_balance1,
							'calculated_balances'=>$calculated_balances1,
							'amount'=>$amount1,
							'enter_date'=>$enter_date1,
							'value_date'=>$value_date1,
							'bank_reference'=>$bank_reference1,
							'customer_reference'=>$customer_reference1,
							'narrative'=>$narrative1,
							'transaction_description'=>$transaction_description1,
							'iban_number'=>$iban_number1,
						);
						$row = $this->BankModel->select_query("select customer_reference from tbl_statment where customer_reference='$customer_reference1'");
						$row = $row->row();
						if(empty($row->customer_reference)){
							$this->BankModel->insert_fun("tbl_statment", $dt);
						}
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

		$start_date = $end_date = date('d-m-Y');
		if(isset($_GET["date-range"])){
			$date_range = $_GET["date-range"];
	
			// `to` ke aas paas se string ko tukdon mein vibhajit karen
			$date_parts = explode(" to ", $date_range);
	
			// Start date aur end date ko extract karen
			$start_date = $date_parts[0];
			$end_date 	= $date_parts[1];
		}

		$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
		$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);
	
		$start_date = $start_date->format('Y-m-d');
		$end_date 	= $end_date->format('Y-m-d');
		
		$query = $this->BankModel->select_query("SELECT s.*,p.done_chemist_id as chemist_id,p.done_invoice as done_invoice,p.done_find_by as done_find_by from tbl_statment as s left JOIN tbl_bank_processing as p on p.upi_no=s.customer_reference where p.type='Statment' and s.value_date BETWEEN '$start_date' AND '$end_date'");
		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
	}

	public function statment_excel_file()
	{
		$start_date = $end_date = date('d-m-Y');
		if(isset($_GET["date-range"])){
			$date_range = $_GET["date-range"];
	
			// `to` ke aas paas se string ko tukdon mein vibhajit karen
			$date_parts = explode(" to ", $date_range);
	
			// Start date aur end date ko extract karen
			$start_date = $date_parts[0];
			$end_date 	= $date_parts[1];
		}

		$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
		$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);
	
		$start_date = $start_date->format('Y-m-d');
		$end_date 	= $end_date->format('Y-m-d');
		
		$this->BankModel->statment_excel_file("direct_download",$start_date,$end_date);
	}
}