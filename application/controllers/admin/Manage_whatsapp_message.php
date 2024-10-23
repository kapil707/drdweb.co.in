<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_whatsapp_message extends CI_Controller {
	var $Page_title = "Manage Whatsapp Message";	
	var $Page_name  = "manage_whatsapp_message";
	var $Page_view  = "manage_whatsapp_message";
	var $Page_menu  = "manage_whatsapp_message";
	var $page_controllers = "manage_whatsapp_message";
	var $Page_tbl   = "tbl_whatsapp_message";
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

		$data['title1'] = $Page_title." || Add";
		$data['title2'] = "Add";
		$data['Page_name'] = $Page_name;
		$data['Page_menu'] = $Page_menu;	

		$this->breadcrumbs->push("Admin","admin/");
		$this->breadcrumbs->push("$Page_title","admin/$page_controllers/");
		$this->breadcrumbs->push("Add","admin/$page_controllers/add");		

		$tbl = $Page_tbl;
		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";	

		$android_user = $version_code = "";
		$system_ip = $this->input->ip_address();
		extract($_POST);
		if(isset($Submit))
		{
			$message_db = "";
			
			$date = date('Y-m-d');
			$time = date("H:i",time());
			$timestamp = time();
			
			$message = nl2br($message);
			$message = str_replace("'","&#39;",$message);
			$message = str_replace("\r\n","<br>",$message);

			/*if($android_user==1)
			{
				$versioncode = "";
				if($version_code!="")
				{
					$versioncode = " and versioncode='$version_code'";
				}
				if($altercode=="0" || $altercode=="")
				{
					$query1 = $this->db->query("SELECT DISTINCT `chemist_id` FROM `tbl_android_device_id` where (1 or 1) $versioncode")->result();
				}
				else
				{
					$query1 = $this->db->query("SELECT DISTINCT `chemist_id` FROM `tbl_android_device_id` where chemist_id='$altercode' $versioncode")->result();
				}
				foreach($query1 as $row)
				{
					$row1 = $this->db->query("select * from tbl_chemist where slcd='CL' and altercode='$row->chemist_id' order by name asc")->row();
					
					$chemist_id1 	= $row1->altercode;
					$mobile		 	= $row1->mobile;
					$name		 	= $row1->name;
					$message1 		= ("Hello $name ($chemist_id1),\\n\\n$message");
					
					if($chemist_id1!="")
					{
						if($mobile!="")
						{
							$dt = array(
							'mobile'=>"+91".$mobile,
							'message'=>$message1,
							'media'=>$media,
							'date'=>$date,
							'time'=>$time,
							'chemist_id'=>$chemist_id1,
							);
							$result = $this->Scheme_Model->insert_fun("tbl_whatsapp_message",$dt);
						}
						else
						{
							$err = "Number not Available";
							$mobile = "";
							$this->Email_Model->tbl_whatsapp_email_fail($mobile,$err,$chemist_id1);
						}
					}
				}
			}
			else
			{*/

			$result = "";
			$where 	= "";
			if($altercode=="")
			{ 
				$message_db = "Select Acm";
				$message = "Select Acm";
				$this->session->set_flashdata("message_type","error");
			} else {
				if($altercode!="All")
				{ 
					$where = "and altercode='$altercode'";
				}
				$query1 = $this->db->query("select * from tbl_chemist where slcd='CL' $where order by name asc")->result();
				foreach($query1 as $row1)
				{
					$chemist_id1 	= $row1->altercode;
					$mobile		 	= $row1->mobile;
					$name		 	= $row1->name;
					$message1 		= ("Hello $name ($chemist_id1),\\n\\n$message");
					
					if($chemist_id1!="")
					{
						if($mobile!="")
						{
							$dt = array(
							'mobile'=>"+91".$mobile,
							'message'=>$message1,
							'media'=>$media,
							'date'=>$date,
							'time'=>$time,
							'timestamp'=>$timestamp,
							'chemist_id'=>$chemist_id1,
							);
							$result = $this->Scheme_Model->insert_fun("tbl_whatsapp_message",$dt);
						}
						else
						{
							$err = "Number not Available";
							$mobile = "xxxx";
							//$this->Email_Model->tbl_whatsapp_email_fail($mobile,$err,$chemist_id1);
						}
					}
				}
			
				if($result)
				{
					$message_db = "($property_title) - Add Successfully.";
					$message = "Add Successfully.";
					$this->session->set_flashdata("message_type","success");
				}
				else
				{
					$message_db = "($property_title) - Not Add.";
					$message = "Not Add.";
					$this->session->set_flashdata("message_type","error");
				}
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
		}		

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/add",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
	public function view()
	{
		error_reporting(0);
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

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
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
		$this->Admin_Model->Add_Activity_log($message);
		echo "ok";
	}

	public function view_api() {
		
		$i = 1;
		$Page_tbl = $this->Page_tbl;
		if(!empty($_REQUEST)){
			$from_date 	= $_REQUEST["from_date"];
			$to_date	= $_REQUEST['to_date'];

			$jsonArray = array();

			$items = "";
			if(!empty($from_date) && !empty($to_date)){

				$result = $this->db->query("SELECT * FROM $Page_tbl WHERE `date` BETWEEN '$from_date' and '$to_date' order by id desc");
				$result = $result->result();

				foreach($result as $row){

					$sr_no = $i++;
					$id = $row->id;
					$mobile = $row->mobile;
					$message = $row->message;
					$datetime = date("d-M-y",strtotime($row->date)) . " @ " .$row->time;

					$dt = array(
						'sr_no' => $sr_no,
						'id' => $id,
						'mobile' => $mobile,
						'message'=>$message,
						'datetime'=>$datetime,
					);
					$jsonArray[] = $dt;
				}
			}

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

	public function call_search_acm()
	{		
		error_reporting(0);
		?><ul style="margin: 0px;padding: 0px;">
		<li style="list-style: none;margin: 5px;"><a href="javascript:addacm('All','<?php echo base64_encode('All') ?>')">All</a></li>
		<?php
		$acm_name = $this->input->post('acm_name');
		$result =  $this->db->query ("select * from tbl_chemist where name Like '$acm_name%' or name Like '%$acm_name' or altercode='$acm_name' limit 50")->result();
		foreach($result as $row)
		{
			$id = $row->altercode;
			$name = ($row->name);
			$name1 = base64_encode($row->name);
			$altercode = ($row->altercode);
			?>
			<li style="list-style: none;margin: 5px;"><a href="javascript:addacm('<?= $id ?>','<?= $name1 ?>')"><?= $name ?> (<?= $altercode ?>)</a></li>
			<?php
		}
		?></ul><?php
	}
}