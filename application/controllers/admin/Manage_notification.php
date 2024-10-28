<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Manage_notification extends CI_Controller {
	var $Page_title = "Manage Notification";	
	var $Page_name  = "manage_notification";
	var $Page_view  = "manage_notification";
	var $Page_menu  = "manage_notification";
	var $page_controllers = "manage_notification";
	var $Page_tbl   = "tbl_android_notification";
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
		$data['url_path'] 	= base_url()."uploads/$page_controllers/photo/resize/";
		$upload_path 		= "./uploads/$page_controllers/photo/main/";
		$upload_resize 		= "./uploads/$page_controllers/photo/resize/";

		$system_ip = $this->input->ip_address();
		$itemid = 0;
		$med_item_id = "";
		extract($_POST);
		if(isset($Submit))
		{
			$message = nl2br($message);
			$message = str_replace("'","&#39;",$message);
			$message = str_replace("\r\n","<br>",$message);
			
			$message_db = "";
			$time = time();
			$date = date("Y-m-d",$time);

			if (!empty($_FILES["image"]["name"]))
			{
				$this->Image_Model->uploadTo = $upload_path;
				$image = $this->Image_Model->upload($_FILES['image']);
				$image = str_replace($upload_path,"",$image);

				$this->Image_Model->newPath = $upload_resize;
				$this->Image_Model->newWidth = 550;
				$this->Image_Model->newHeight = 550;
				$this->Image_Model->resize();
			}		
			else
			{
				$image = "";
			}			

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
				if($row1->altercode!="")
				{
					$altercode = $row1->altercode;
					$result = $this->send_android_notification($title,$message,$altercode,"chemist",$funtype,$itemid,$compid,$division,$image);
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
		$this->load->view("admin/manage_user_chemist/find_chemist",$data);
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

		$this->load->view("admin/header_footer/header",$data);
		$this->load->view("admin/$Page_view/view",$data);
		$this->load->view("admin/header_footer/footer",$data);
		$this->load->view("admin/$Page_view/footer2",$data);
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
					$myfun = "";
					if($row->funtype=="0")
					{ 
						$myfun = "Not Need"; 
					}
					if($row->funtype=="1")
					{ 
						$myfun = "Item ".$row->itemid; 
					} 
					if($row->funtype=="2")
					{ 
						$myfun = "Company ".$row->compid;
					} 
					if($row->funtype=="3")
					{ 
						$myfun = "Map Page"; 
					} 
					if($row->funtype=="4")
					{ 
						$myfun = "Orders Page"; 
					} 
					if($row->funtype=="4")
					{ 
						$myfun = "Invoice Page"; 
					}
					$user = $row->user_type."(".$row->chemist_id.")";
					$title = $row->title;
					$message = $row->message;
					$datetime = date("d-M-y",strtotime($row->date)) . " @ " .$row->time;

					$dt = array(
						'sr_no' => $sr_no,
						'id' => $id,
						'myfun' => $myfun,
						'user'=>$user,
						'title'=>$title,
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
	
	public function send_android_notification($title,$message,$chemist_id,$user_type,$funtype,$itemid,$compid,$division,$image)
	{
		$date = date('Y-m-d');
		$time = date("H:i",time());
		
		/*$result = $this->db->query("select * from tbl_android_device_id where chemist_id='$chemist_id'")->result();
		foreach($result as $row)
		{
			$device_id =  $row->device_id;
			
			$dt = array(
			'chemist_id'=>$chemist_id,
			'user_type'=>$user_type,
			'title'=>$title,
			'message'=>$message,
			'time'=>$time,
			'date'=>$date,
			'device_id'=>$device_id,
			'funtype'=>$funtype,
			'itemid'=>$itemid,
			'compid'=>$compid,
			'division'=>$division,);
			
			$this->Scheme_Model->insert_fun("tbl_android_notification",$dt);
		}*/
		
		$device_id =  "default"; // yha sirf website or android me show ke liya use hota ha web page par show ki liya sirf
			
		$dt = array(
		'chemist_id'=>$chemist_id,
		'user_type'=>$user_type,
		'title'=>$title,
		'message'=>$message,
		'date'=>$date,
		'time'=>$time,
		'device_id'=>$device_id,
		'funtype'=>$funtype,
		'itemid'=>$itemid,
		'compid'=>$compid,
		'division'=>$division,
		'image'=>$image,);
		
		$this->Scheme_Model->insert_fun("tbl_android_notification",$dt);
		return 1;
	}
	
	public function call_search_acm()
	{		
		error_reporting(0);
		?><ul style="margin: 0px;padding: 0px;"><?php
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
	
	public function call_search_item()
	{		
		error_reporting(0);
		?><ul style="margin: 0px;padding: 0px;"><?php
		$item_name = $this->input->post('item_name');
		$result =  $this->db->query ("select id,i_code,item_name,item_code from tbl_medicine where item_name Like '$item_name%' or item_name Like '%$item_name' limit 50")->result();
		foreach($result as $row)
		{
			$id = $row->i_code;
			$item_name = ($row->item_name);
			$item_name1 = base64_encode($row->item_name);
			$item_code = ($row->item_code);
			?>
			<li style="list-style: none;margin: 5px;"><a href="javascript:additem(<?= $id ?>,'<?= $item_name1 ?>')"><?= $item_name ?> (<?= $item_code ?>)</a></li>
			<?php
		}
		?></ul><?php
	}
	
	public function call_search_company()
	{		
		error_reporting(0);
		?><ul style="margin: 0px;padding: 0px;"><?php
		$company_full_name = $this->input->post('company_name');
		$result =  $this->db->query ("select DISTINCT company_full_name,compcode from tbl_medicine where company_full_name Like '$company_full_name%' or company_name Like '%$company_full_name' limit 50")->result();
		foreach($result as $row)
		{
			$id = $row->compcode;
			$company_full_name = ($row->company_full_name);
			$company_full_name1 = base64_encode($row->company_full_name);
			$compcode = ($row->compcode);
			?>
			<li style="list-style: none;margin: 5px;"><a href="javascript:addcompany(<?= $id ?>,'<?= $company_full_name1 ?>')"><?= $company_full_name ?> (<?= $compcode ?>)</a></li>
			<?php
		}
		?></ul><?php
	}
	
	public function get_company_division()
	{		
		error_reporting(0);
		$compid = $this->input->post('compid');
		?><select name="division" id="division" class="form-control">
			<option value="">
				Select Company Division
			</option>
			<?php
			$result =  $this->db->query ("select DISTINCT division from tbl_medicine where compcode='$compid' order by division asc")->result();
			foreach($result as $row)
			{
				$division = $row->division;
				?>
				<option value="<?= $division ?>">
					<?= $division ?>
				</option>
				<?php
			}
		?></select><?php
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