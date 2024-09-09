<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_broadcast extends CI_Controller {
	var $Page_title = "Manage Broadcast";	
	var $Page_name  = "manage_broadcast";
	var $Page_view  = "manage_broadcast";
	var $Page_menu  = "manage_broadcast";
	var $page_controllers = "manage_broadcast";
	var $Page_tbl   = "tbl_broadcast";
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

		$system_ip = $this->input->ip_address();
		extract($_POST);
		if(isset($Submit))
		{	
			$message = nl2br($message);
			$message = str_replace("'","&#39;",$message);
			$message = str_replace("\r\n","<br>",$message);		

			$message_db = "";
			
			$date = date('Y-m-d');
			$time = date("H:i",time());
			
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
					$user_type = "chemist";
					$chemist_id = $row1->altercode;
					
					$dt = array(
					'chemist_id'=>$chemist_id,
					'user_type'=>$user_type,
					'title'=>$title,
					'message'=>$message,
					'date'=>$date,
					'time'=>$time,);
					
					$result = $this->Scheme_Model->insert_fun("tbl_broadcast",$dt);
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

		/*$where = array('status'=>"0");
		$data["result"] = $this->Admin_Model->select_result("tbl_broadcast",$where,"id","asc");*/

		$vdt = date("Y-m-d");
		if($_GET["submit"])
		{
			$vdt = $_GET["vdt"];
			$vdt = date("Y-m-d",strtotime($vdt));
		}
		$data["vdt1"] = $vdt;

		$this->load->library('pagination');

		$result = $this->db->query("select * from $tbl where date='$vdt' and status='0' order by id desc")->result();
		
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

		$query = $this->db->query("select * from $tbl where date='$vdt' and status='0' order by id desc LIMIT $per_page,100");
  		$data["result"] = $query->result();

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
	
	public function add2($page_type="")
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
		$Page_tbl 	= "tbl_website";
		$page_controllers 	= $this->page_controllers;		

		$this->Admin_Model->permissions_check_or_set($Page_title,$Page_name,$user_type);
		$Page_menu  = $page_type;
		
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

		if($page_type=="broadcast_title")
		{
			$data["type"] = "text";
			$data["titlepg"] = "Broadcast Title";
			$data["placeholderpg"] = "Broadcast Title";
			$data["pagetextpg"] = "";
		}

		if($page_type=="broadcast_message")
		{
			$data["type"] = "text";
			$data["titlepg"] = "Broadcast Message";
			$data["placeholderpg"] = "Broadcast Message";
			$data["pagetextpg"] = "";
		}

		if($page_type=="broadcast_status")
		{
			$data["type"] = "checkbox";
			$data["titlepg"] = "Broadcast Status";
			$data["placeholderpg"] = "Broadcast Status";
			$data["pagetextpg"] = "";
		}

		extract($_POST);
		if(isset($Submit))
		{
			if($page_type=="send_whatsapp_message_all_chemist")
			{
				$this->Scheme_Model->send_whatsapp_message_all_chemist($mydata);
			}
			
			$message_db = "";
			if($type=="image")
			{
				if (!empty($_FILES["image"]["name"]))
				{
					$x = $_FILES["image"]['name'];
					$y = $_FILES["image"]['tmp_name'];
					$mydata = $this->Scheme_Model->photo_up("photo",$x,$y,$upload_path);
				}
				else
				{
					$mydata = $old_mydata;
				}
			}
			$mydata = base64_encode($mydata);
			$time = time();
			$date = date("Y-m-d",$time);
			
			$result = "";
			$dt = array('mydata'=>$mydata,'page_type'=>$page_type,'update_date'=>$date,'update_time'=>$time,);	

			$change_text = "";
			if($old_mydata!=$mydata)
			{
				if($data["type"]=="text")
				{
					$change_text = $data["titlepg"]." - ($old_mydata to ".base64_decode($mydata).")";
				}
				if($data["type"]=="image")
				{
					$change_text = $data["titlepg"]." - (Upload) ";
					$url_path = "./uploads/$page_controllers/photo/";
					$query = $this->db->query("select * from $tbl where page_type='$page_type'");
					$row11 = $query->row();
					$filename = $url_path.base64_decode($row11->mydata);
					unlink($filename);
				}
			}		

			$query = $this->db->query("select * from $tbl where page_type='$page_type'");
			$row = $query->row();
			if(empty($row->id))
			{
				$result = $this->Scheme_Model->insert_fun($tbl,$dt);
			}
			else
			{
				$where = array('page_type'=>$page_type);
				$result = $this->Scheme_Model->edit_fun($tbl,$dt,$where);
			}
			if($result)
			{
				$message_db = "$change_text - Set Successfully.";
				$message = "Set Successfully.";
				$this->session->set_flashdata("message_type","success");
			}
			else
			{
				$message_db = "$change_text - Not Set.";
				$message = "Not Set.";
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
				}
			}
		}
		$data["mydata"] = "";
		$query = $this->db->query("select * from $tbl where page_type='$page_type'");
		$row = $query->row();
		if(!empty($row->id))
		{
			$data["mydata"] = base64_decode($row->mydata);
		}		

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/add2",$data);
		$this->load->view("admin/header_footer/footer",$data);
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