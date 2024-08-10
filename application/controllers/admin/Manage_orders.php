<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_orders extends CI_Controller {
	var $Page_title = "Manage Orders";
	var $Page_name  = "manage_orders";
	var $Page_view  = "manage_orders";
	var $Page_menu  = "manage_orders";
	var $page_controllers = "manage_orders";
	var $Page_tbl   = "tbl_order";
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
  		
		/*$this->load->library('pagination');

		$result = $this->db->query("SELECT DISTINCT(order_id) as order_id,sum(`sale_rate`*`quantity`) as total,gstvno,date,time,chemist_id,selesman_id,download_status,order_type,count(id) as line_items FROM `tbl_order` WHERE `date`= '$date' GROUP BY order_id,gstvno,date,time,chemist_id,selesman_id,download_status,order_type order by order_id desc")->result();
		
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
		
		$query = $this->db->query("SELECT DISTINCT(order_id) as order_id,sum(`sale_rate`*`quantity`) as total,gstvno,date,time,chemist_id,selesman_id,download_status,order_type,count(id) as line_items FROM `tbl_order` WHERE `date`= '$date' GROUP BY order_id,gstvno,date,time,chemist_id,selesman_id,download_status,order_type order by order_id desc LIMIT $per_page,100");*/
		
		
		$vdt = date("Y-m-d");
		if($_POST["submit"])
		{
			$vdt = $_POST["vdt"];
			$vdt = date("Y-m-d",strtotime($vdt));
		}
		$data["vdt1"] = $vdt;

		$query = $this->db->query("SELECT DISTINCT(order_id) as order_id,sum(`sale_rate`*`quantity`) as total,gstvno,date,time,chemist_id,selesman_id,download_status,order_type,count(id) as line_items,download_line FROM `tbl_order` WHERE `date`= '$vdt' GROUP BY order_id,gstvno,date,time,chemist_id,selesman_id,download_status,order_type,download_line order by order_id desc");
  		$data["result"] = $query->result();
		

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}
	
	public function view2($id)
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

  		$data["result"] = $this->db->query("select * from $tbl where order_id='$id'")->result();		

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view2",$data);
		$this->load->view("admin/header_footer/footer",$data);
	}

	public function download_order($order_id)
	{
		$Page_tbl 	= $this->Page_tbl;

		$where = array('order_id'=>$order_id);
		$this->db->where($where);
		$query = $this->db->get($Page_tbl);
		$row   = $query->row();
		$query = $query->result();

		$where 			= array('altercode'=>$row->chemist_id);
		$users 			= $this->Scheme_Model->select_row("tbl_acm",$where);
		$acm_altercode 	= $users->altercode;
		$acm_name		= ucwords(strtolower($users->name));		
		$chemist_excle 	= "$acm_name ($acm_altercode)";
		$this->Order_Model->excel_save_order_to_server($query,$chemist_excle,"direct_download");
	}
}