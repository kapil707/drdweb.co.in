<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_whatsapp_group_message extends CI_Controller {
	var $Page_title = "Manage Whatsapp Group Message";	
	var $Page_name  = "manage_whatsapp_group_message";
	var $Page_view  = "manage_whatsapp_group_message";
	var $Page_menu  = "manage_whatsapp_group_message";
	var $page_controllers = "manage_whatsapp_group_message";
	var $Page_tbl   = "tbl_whatsapp_group_message";
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
			
			$message = nl2br($message);
			$message = str_replace("'","&#39;",$message);
			$message = str_replace("\r\n","<br>",$message);

			if($android_user==1)
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
			{
				$result = "";
				if($altercode=="0" || $altercode=="")
				{
					$query1 = $this->db->query("select * from tbl_chemist where slcd='CL' order by name asc")->result();
				}
				else
				{
					$query1 = $this->db->query("select * from tbl_chemist where slcd='CL' and altercode='$altercode' order by name asc")->result();
				}
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

		$data['url_path'] = base_url()."uploads/$page_controllers/photo/";
		$upload_path = "./uploads/$page_controllers/photo/";
		
		$vdt = date("Y-m-d");
		if($_GET["submit"])
		{
			$vdt = $_GET["vdt"];
			$vdt = date("Y-m-d",strtotime($vdt));
		}
		$data["vdt1"] = $vdt;

		$this->load->library('pagination');

		$result = $this->db->query("select * from $tbl where `date`= '$vdt' order by id desc")->result();
		
		$config['total_rows'] = count($result);
		$data["count_records"] = count($result);
        $config['per_page'] = 100;

        if($num!=""){
           $config['per_page'] = $num;
        }
        $config['full_tag_open']="<ul class='pagination'>";
        $config['full_tag_close']="</ul>";
        $config['first_tag_open']='<li>';
        $config['first_tag_close']='</li>';
        $config['last_tag_open']='<li>';
        $config['last_tag_close']='</li>';
        $config['next_tag_open']='<li>';
        $config['next_tag_close']='</li>';
        $config['prev_tag_open']='<li>';
        $config['prev_tag_close']='</li>';
        $config['num_tag_open']='<li>';
        $config['num_tag_close']='</li>';
        $config['cur_tag_open']="<li class='active'><a>";
        $config['cur_tag_close']='</a></li>';
        $config['num_links'] = 100;    
        $config['page_query_string'] = TRUE;
		$per_page=$_GET["pg"];
		if($per_page=="")
		{
			$per_page = 0;
		}


		$data['per_page']=$per_page;
		
		$data['user_id'] = $user_id;

		/*$query = $this->db->query("select * from $tbl order by id desc LIMIT $per_page,100");*/

		$query = $this->db->query("select * from $tbl where `date`= '$vdt' order by id desc LIMIT $per_page,100");
  		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
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
}