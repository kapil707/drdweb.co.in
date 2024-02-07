<?php 
header("Content-type: application/json; charset=utf-8");
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Api05 extends CI_Controller {	

	public $upload_profile_path;
	public $upload_meter_path;
	public $upload_chemist_path;
	public function __construct(){

		parent::__construct();
		// Load model
		$this->load->model('Drd_Master_Model');
		$date = date("Y-m-d");
		$this->upload_profile_path = "upload_drd_master/profile_photo/";
		// if (!file_exists($this->upload_profile_path)) {
		// 	mkdir($this->upload_profile_path, 0777, true);
		// }
		$this->upload_meter_path = "upload_drd_master/meter_photo/".$date."/";
		if (!file_exists($this->upload_meter_path)) {
			mkdir($this->upload_meter_path, 0777, true);
		}
		$this->upload_chemist_path = "upload_drd_master/chemist_photo/".$date."/";
		if (!file_exists($this->upload_chemist_path)) {
			mkdir($this->upload_chemist_path, 0777, true);
		}
	}
   
	public function test(){
		$test = $_POST['test'];
$items .= <<<EOD
{"test":"{$test}"},
EOD;
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php	
	}

	public function get_login_api(){
		$defaultpassword= $this->Scheme_Model->get_website_data("defaultpassword");
		$items = "";
		$return_id 		= 	0;
		$return_message = 	"Logic error.";
		if(!empty($_POST)){
			$api_key 		= $_POST["api_key"];
		
			/*******************************************/
			$user_name 		= $_POST['user_name'];
			$password 		= $_POST['password'];
			$firebase_token = $_POST['firebase_token'];
			/*******************************************/
			
			$user_return 	= 	"0";
			$user_alert 	= 	"Wrong Anything Else";
			if($user_name!="" && $password!="")
			{
				$user_password = md5($password);
				$user_alert = "Enter true User name or Password";			
				$query = $this->db->query("select tbl_master.id,tbl_master.code,tbl_master.altercode,tbl_master.name,tbl_master.mobile,tbl_master.email,tbl_master_other.image,tbl_master.status as status1,tbl_master_other.status,tbl_master_other.password as password,tbl_master_other.exp_date from tbl_master left join tbl_master_other on tbl_master.code = tbl_master_other.code where tbl_master.altercode='$user_name' and tbl_master.code=tbl_master_other.code and tbl_master.slcd='SM' limit 1")->row();
				if ($query->id!="")
				{
					if ($query->password == $user_password || $user_password==md5($defaultpassword))
					{
						$user_session 	= 	$query->id;
						$user_fname		= 	$query->name;
						$user_code	 	= 	$query->code;
						$user_altercode	= 	$query->altercode;
						$return_id 		= 	"1";
						$return_message = 	"Logged in successfully";
						$user_image 	=   base_url().$this->upload_profile_path.$query->image;

						$this->db->query("update tbl_master_other set firebase_token='$firebase_token' where code='$user_code'");
					}
					else
					{
						$return_message = "Incorrect password";
					}
				}
				else
				{
						$return_message = "You are not registered";
				}
			}
$items .= <<<EOD
{"return_id":"{$return_id}","return_message":"{$return_message}","user_session":"{$user_session}","user_fname":"{$user_fname}","user_code":"{$user_code}","user_altercode":"{$user_altercode}","user_password":"{$user_password}","user_image":"{$user_image}"},
EOD;
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
		}
	}

	public function get_slider_api(){
		$items = "";
		if(!empty($_POST)){
			$api_key 		= $_POST["api_key"];
			
			$top_flash = $this->Chemist_Model->top_flash();
$items .= <<<EOD
{"top_flash":$top_flash},
EOD;
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $top_flash;?>]
<?php
		}
	}

	public function home_page_api(){
		$items = "";
		if(!empty($_POST)){
			$api_key 		= $_POST["api_key"];
		
			$user_code 		= $_POST["user_code"];
			$user_altercode = $_POST["user_altercode"];
			$firebase_token = $_POST["firebase_token"];
			$latitude 		= $_POST["latitude"];
			$longitude 		= $_POST["longitude"];
			$versionname 	= $_POST["versionname"];

			$date			= date("Y-m-d");
			$time			= date("H:i");		

			//***************************************** */
			$this->insert_tracking($user_code,$user_altercode,$firebase_token,$latitude,$longitude,$date,$time,"home_page_api");
			//***************************************** */

			//***************************************** */
			$this->db->query("update tbl_master_other set firebase_token='$firebase_token' where code='$user_code'");
			//***************************************** */

			$dt = array(
				'user_code'=>$user_code,
				'user_altercode'=>$user_altercode,
				'firebase_token'=>$firebase_token,
				'latitude'=>$latitude,
				'longitude'=>$longitude,
				'date'=>$date,
				'time'=>$time,);

			$where = array(
				'user_code'=>$user_code,
				'user_altercode'=>$user_altercode,);
			$row = $this->Drd_Master_Model->select_query_row("id","tbl_firebase_token",$where);
			if(empty($row->id)){
				$this->Drd_Master_Model->insert_query("tbl_firebase_token",$dt);
			}else{
				$this->Drd_Master_Model->update_query("tbl_firebase_token",$dt,$where);
			}

			$return_logout = "0";
			$return_id = 1;
			if($versionname=="5.0"){
				$return_title = "";
				$return_message = "";
				$return_url = "";
			}else{
				$return_title = "Upload App Now";
				$return_message = "Upload App Now";
				$return_url = "https://drdweb.co.in/upload_drd_master/app-debug.apk";
			}

$items .= <<<EOD
{"return_id":"{$return_id}","return_title":"{$return_title}","return_message":"{$return_message}","return_url":"{$return_url}","return_logout":"{$return_logout}"},
EOD;
	}
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
	}

	public function insert_tracking($user_code,$user_altercode,$firebase_token,$latitude,$longitude,$getdate,$gettime,$insert_type){
		$date			= date("Y-m-d");
		$time			= date("H:i");

		$dt = array(
			'user_code'=>$user_code,
			'user_altercode'=>$user_altercode,
			'firebase_token'=>$firebase_token,
			'latitude'=>$latitude,
			'longitude'=>$longitude,
			'getdate'=>$getdate,
			'gettime'=>$gettime,
			'date'=>$date,
			'time'=>$time,
			'insert_type'=>$insert_type,
		);
		$this->Drd_Master_Model->insert_query("tbl_tracking",$dt);
	}
	
	public function update_tracking_api(){
		$items = "";
		if(!empty($_POST)){
			$api_key 		= $_POST["api_key"];

			$user_code 		= $_POST["user_code"];
			$user_altercode = $_POST["user_altercode"];
			$firebase_token	= $_POST["firebase_token"];
			$latitude 		= $_POST["latitude"];
			$longitude		= $_POST["longitude"];
			$getdate 		= $_POST["getdate"];
			$gettime 		= $_POST["gettime"];
			
			$return_id = 0;

			$where = array(
				'user_code'=>$user_code,
				'user_altercode'=>$user_altercode,
				'getdate'=>$getdate,
				'gettime'=>$gettime,);
			$row = $this->Drd_Master_Model->select_query_row("id","tbl_tracking",$where);
			if(empty($row->id)){
				$this->insert_tracking($user_code,$user_altercode,$firebase_token,$latitude,$longitude,$getdate,$gettime,"update_tracking_api");
				$return_id = 1;
			}			
$items .= <<<EOD
{"return_id":"{$return_id}"},
EOD;
		}
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
	}
	
	public function upload_profile_image_api(){
		$items = "";
		$return_id = 0;
		$user_image = "";
		$return_message = "Error Uploading File";
		//header("Content-type: application/json; charset=utf-8");
		if(!empty($_POST)){

			$api_key 		= $_POST["api_key"];

			$user_code 		= $_POST['user_code'];
			$user_altercode = $_POST['user_altercode'];
			
			$upload_image = $this->upload_profile_path;			
			
			if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

				ini_set('upload_max_filesize', '10M');
				ini_set('post_max_size', '10M');
				ini_set('max_input_time', 300);
				ini_set('max_execution_time', 300);
		
				$config['upload_path'] = $upload_image;  // Define the directory where you want to store the uploaded files.
				$config['allowed_types'] = '*';  // You may want to restrict allowed file types.
				$config['max_size'] = 0;  // Set to 0 to allow any size.

				$new_name = time().$_FILES["image"]['name'];
				$config['file_name'] = $new_name;
		
				$this->load->library('upload', $config);
		
				if (!$this->upload->do_upload('image')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					print_r($error);
				} else {
					$data = $this->upload->data();
					$image = ($data['file_name']);
					//$this->load->view('upload_success', $data);
				}

			} else {
				// Invalid file or no file uploaded
				$return_message = "Invalid file or no file uploaded.";
			}
			
			$time = time();
			$date = date("Y-m-d",$time);
			$time = date("H:i",$time);

			$where = array('code'=>$user_code);
			$dt = array('image'=>$image);
			$this->Scheme_Model->edit_fun("tbl_master_other",$dt,$where);
			
			$user_image = base_url().$upload_image.$image;
			$return_id = 1;
			$return_message = "Image Uploaded Successfully";
		}			
$items .= <<<EOD
{"return_id":"{$return_id}","return_message":"{$return_message}","user_image":"{$user_image}"},
EOD;

if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
	}
	
	public function get_delivery_order_list_api(){
		$items = "";
		if(!empty($_POST)){
			$api_key 		= $_POST["api_key"];
			$user_code		= $_POST['user_code'];
			$user_altercode	= $_POST['user_altercode'];
			$status			= $_POST['status'];

			$date = date("Y-m-d");
			$result = $this->Drd_Master_Model->full_query_result("SELECT DISTINCT mm.`tagno`,mm.vdt FROM `drd_master_tbl_delivery` as mm where mm.`user_altercode`='$user_altercode' and mm.status='$status' order by mm.tagno desc limit 10");
			foreach($result as $row)
			{
				$tagno 		= 	$row->tagno;
				$date		=	$row->vdt;
				
				$where = array('tagno'=>$tagno);
				$row1 = $this->Drd_Master_Model->select_query_row("mtime","drd_master_tbl_delivery",$where);
				
				$time		=	$row1->mtime;

$items .= <<<EOD
{"tagno":"{$tagno}","date":"{$date}","time":"{$time}"},
EOD;
			}
		}
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
	}

	public function get_delivery_order_list_tag_api(){
		$items = "";
		$medicine_items = "";
		if(!empty($_POST)){
			$api_key 		= $_POST["api_key"];
			$user_code		= $_POST['user_code'];
			$user_altercode	= $_POST['user_altercode'];
			$tagno			= $_POST['tagno'];
			$status			= $_POST['status'];

			$date = date("Y-m-d");
			$where = array(
				'user_altercode'=>$user_altercode,
				'tagno'=>$tagno,
				'status'=>$status);
			$result = $this->Drd_Master_Model->select_query("*","drd_master_tbl_delivery",$where);
			foreach($result as $row)
			{
				$tagno 		= 	$row->tagno;
				$date		=	$row->vdt;
				$time		=	$row->mtime;

				$items_s	=	$row->items;
				$medicine_items = "";
				if($items_s){
					$i = 1;
					$items_array = explode (",", $items_s);
					foreach($items_array as $row1){
						
						$medicine_row = $this->get_medicine_details($row1);

						$medicine_items .= "<hr>".$i.".".$medicine_row->item_name." | Pack : ".$medicine_row->packing." | Mrp : ".$medicine_row->mrp."<br>";
						$i++;
					}				
				}

				$gstvno			= 	$row->gstvno;
				$mydate			=	$row->vdt;
				$chemist_code	=	$row->chemist_code;
				$chemist_name 	= 	$row->chemist_name;
				$amount 		=   $row->amount; 

$items .= <<<EOD
{"gstvno":"{$gstvno}","mydate":"{$mydate}","chemist_code":"{$chemist_code}","chemist_name":"{$chemist_name}","amount":"{$amount}","medicine_items":"{$medicine_items}"},
EOD;
			}
		}
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
	}

	public function get_medicine_details($item_c){
		return $this->db->query("select * from tbl_medicine where i_code='$item_c'")->row();
	}

	public function upload_delivery_order_photo_api(){
		$items = "";
		$return_id = 0;
		$return_message = "Error Uploading File";
		//header("Content-type: application/json; charset=utf-8");
		if(!empty($_POST)){

			$api_key 		= $_POST["api_key"];

			$user_code 		= $_POST['user_code'];
			$user_altercode = $_POST['user_altercode'];
			$chemist_code 	= $_POST['chemist_code'];
			$gstvno 		= $_POST['gstvno'];

			$id 			= $_POST['id'];
			$tagno 			= $_POST['tagno'];
			$message 		= $_POST['message'];
			$payment_message= $_POST['payment_message'];
			$payment_type 	= $_POST['payment_type'];

			$latitude 		= $_POST['latitude'];
			$longitude 		= $_POST['longitude'];

			$upload_image = $this->upload_chemist_path;

			ini_set('upload_max_filesize', '10M');
			ini_set('post_max_size', '10M');
			ini_set('max_input_time', 300);
			ini_set('max_execution_time', 300);
	
			$config['upload_path'] = $upload_image;  // Define the directory where you want to store the uploaded files.
			$config['allowed_types'] = '*';  // You may want to restrict allowed file types.
			$config['max_size'] = 0;  // Set to 0 to allow any size.
	
			$this->load->library('upload', $config);
			
			if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK) {
			
				if (!$this->upload->do_upload('image1')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					//print_r($error);
					$return_message = $error;
				} else {
					$data = $this->upload->data();
					$image1 = ($data['file_name']);
					//$this->load->view('upload_success', $data);
					if (strpos($image1, "temp_image") !== false){
						//$return_message = "ok ha bhai";
						$image1 = "";
					}
				}
			} else {
				// Invalid file or no file uploaded
				$return_message = "Invalid file or no file uploaded.";
			}

			if (isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK) {
				if (!$this->upload->do_upload('image2')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					//print_r($error);
					$return_message = $error;
				} else {
					$data = $this->upload->data();
					$image2 = ($data['file_name']);
					//$this->load->view('upload_success', $data);
					if (strpos($image2, "temp_image") !== false){
						//$return_message = "ok ha bhai";
						$image2 = "";
					}
				}

			} else {
				// Invalid file or no file uploaded
				$return_message = "Invalid file or no file uploaded.";
			}

			if (isset($_FILES['image3']) && $_FILES['image3']['error'] === UPLOAD_ERR_OK) {
				if (!$this->upload->do_upload('image3')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					//print_r($error);
					$return_message = $error;
				} else {
					$data = $this->upload->data();
					$image3 = ($data['file_name']);
					//$this->load->view('upload_success', $data);
					if (strpos($image3, "temp_image") !== false){
						//$return_message = "ok ha bhai";
						$image3 = "";
					}
				}

			} else {
				// Invalid file or no file uploaded
				$return_message = "Invalid file or no file uploaded.";
			}

			if (isset($_FILES['image4']) && $_FILES['image4']['error'] === UPLOAD_ERR_OK) {
				if (!$this->upload->do_upload('image4')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					//print_r($error);
					$return_message = $error;
				} else {
					$data = $this->upload->data();
					$image4 = ($data['file_name']);
					//$this->load->view('upload_success', $data);
					if (strpos($image4, "temp_image") !== false){
						//$return_message = "ok ha bhai";
						$image4 = "";
					}
				}

			} else {
				// Invalid file or no file uploaded
				$return_message = "Invalid file or no file uploaded.";
			}
			
			$time = time();
			$date = date("Y-m-d",$time);
			$time = date("H:i",$time);
			
			if($id==0){
				//***************************************** */
				$firebase_token = "";
				$this->insert_tracking($user_code,$user_altercode,$firebase_token,$latitude,$longitude,$date,$time,"upload_delivery_order_photo_api");
				//***************************************** */

				$where = array(
					'tagno'=>$tagno,
					'chemist_code'=>$chemist_code,);
				$result = $this->Drd_Master_Model->select_query("*","drd_master_tbl_delivery",$where);
				// iss jab enter karay ga ager same tagno same date or same chemist code ho to ek enter ki multipul copy ke liya yha use hota ha
				foreach($result as $row)
				{
					$gstvno_ = $row->gstvno;

					$where = array(
						'user_code'=>$user_code,
						'user_altercode'=>$user_altercode,
						'chemist_code'=>$chemist_code,
						'gstvno'=>$gstvno_,);
					$row1 = $this->Drd_Master_Model->select_query_row("*","tbl_delivery_photo",$where);
					if(empty($row1->id)){

						if($gstvno_!=$gstvno){
							$image2 = $image4 = ""; // yha jab use hota ha jab same day me copy hota ha data to
						}
					
						$dt = array(
							'user_code'=>$user_code,
							'user_altercode'=>$user_altercode,
							'chemist_code'=>$chemist_code,
							'gstvno'=>$gstvno_,
							'message'=>$message,
							'payment_message'=>$payment_message,
							'payment_type'=>$payment_type,
							'image1'=>$image1,
							'image2'=>$image2,
							'image3'=>$image3,
							'image4'=>$image4,
							'latitude'=>$latitude,
							'longitude'=>$longitude,
							'date'=>$date,
							'time'=>$time,
							'status'=>1,
						);
						$this->Drd_Master_Model->insert_query("tbl_delivery_photo",$dt);

						$where = array(
							'user_altercode'=>$user_altercode,
							'chemist_code'=>$chemist_code,
							'gstvno'=>$gstvno_,
						);
						$dt = array(
							'status'=>"1",
						);
						$this->Drd_Master_Model->update_query("drd_master_tbl_delivery",$dt,$where);
					}
				}
			}else{

				$where = array('id'=>$id);
				$row1 = $this->Drd_Master_Model->select_query_row("*","tbl_delivery_photo",$where);
				$image1_ = $image1;
				if($image1==""){
					$image1_ = $row1->image1;
				}
				$image2_ = $image2;
				if($image2==""){
					$image2_ = $row1->image2;
				}
				$image3_ = $image3;
				if($image3==""){
					$image3_ = $row1->image3;
				}
				$image4_ = $image4;
				if($image4==""){
					$image4_ = $row1->image4;
				}

				$where = array('id'=>$id);
				$dt = array(
					'user_code'=>$user_code,
					'user_altercode'=>$user_altercode,
					'chemist_code'=>$chemist_code,
					'gstvno'=>$gstvno,
					'message'=>$message,
					'payment_message'=>$payment_message,
					'payment_type'=>$payment_type,
					'image1'=>$image1_,
					'image2'=>$image2_,
					'image3'=>$image3_,
					'image4'=>$image4_,
					'date'=>$date,
					'time'=>$time,
					'status'=>1,
				);
				$this->Drd_Master_Model->update_query("tbl_delivery_photo",$dt,$where);
			}
			
			$return_id = 1;
			$return_message = "Uploaded Successfully";
		}			
$items .= <<<EOD
{"return_id":"{$return_id}","return_message":"{$return_message}"},
EOD;

if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
	}

	public function get_delivery_order_photo_api(){
		$items = $items_others = "";
		if(!empty($_POST)){
			$api_key 		= $_POST["api_key"];

			$user_code 		= $_POST['user_code'];
			$user_altercode = $_POST['user_altercode'];
			$chemist_code	= $_POST['chemist_code'];
			$gstvno 		= $_POST['gstvno'];

			$time = time();
			$todaydate = date("Y-m-d",$time);
			
			$at_a    = $nrx = "0";
			$is_edit = "0";
			$id      = "0";

			$where = array(
				// 'user_code'=>$user_code,
				'user_altercode'=>$user_altercode,
				'chemist_code'=>$chemist_code,
				'gstvno'=>$gstvno,);
			$result = $this->Drd_Master_Model->select_query("*","drd_master_tbl_delivery",$where);
			foreach($result as $row)
			{
				$items_s	=	$row->items;
				$medicine_items = "";
				if($items_s){
					$i = 1;
					$items_array = explode (",", $items_s);
					foreach($items_array as $row1){
						
						$medicine_row = $this->get_medicine_details($row1);

						if($medicine_row->misc_settings=="#NRX"){
							$nrx = "1";
						}else{
							$at_a = "1";
						}						
					}				
				}
			}

			$image1 = $image2 = $image3 = $image4 = $message = $payment_type =$payment_message = $date = $time = "";
			$where = array(
				'user_code'=>$user_code,
				'user_altercode'=>$user_altercode,
				'chemist_code'=>$chemist_code,
				'gstvno'=>$gstvno,
				'status'=>1);
			$result = $this->Drd_Master_Model->select_query("*","tbl_delivery_photo",$where);
			foreach($result as $row)
			{
				$id  	=	$row->id;
				$image1 = 	"https://drdweb.co.in/upload_drd_master/chemist_photo/".$row->date."/".$row->image1;
				$image2 = 	"https://drdweb.co.in/upload_drd_master/chemist_photo/".$row->date."/".$row->image2;
				$image3 = 	"https://drdweb.co.in/upload_drd_master/chemist_photo/".$row->date."/".$row->image3;
				$image4 = 	"https://drdweb.co.in/upload_drd_master/chemist_photo/".$row->date."/".$row->image4;
				$message = 	$row->message;
				$payment_type = $row->payment_type;
				$payment_message = $row->payment_message;
				$date  	=	$row->date;
				$time  	=	$row->time;
				if($date!=$todaydate){
					$is_edit= 	"1";
				}
			}

$items.= <<<EOD
{"id":"{$id}","image1":"{$image1}","image2":"{$image2}","image3":"{$image3}","image4":"{$image4}","message":"{$message}","payment_type":"{$payment_type}","payment_message":"{$payment_message}","date":"{$date}","time":"{$time}","is_edit":"{$is_edit}","at_a":"{$at_a}","nrx":"{$nrx}"},
EOD;

			$where = array(
				'user_code'=>$user_code,
				'user_altercode'=>$user_altercode,
				'chemist_code'=>$chemist_code,
				'gstvno'=>$gstvno,
				'status'=>1);
			$result = $this->Drd_Master_Model->select_query("*","tbl_delivery_photo_more",$where);
			foreach($result as $row)
			{
				$id  	=	$row->id;
				$image 	= 	"https://drdweb.co.in/upload_drd_master/chemist_photo/".$row->date."/".$row->image;
				$date   = 	$row->date;
				$time   = 	$row->time;

$items_others.= <<<EOD
{"id":"{$id}","image":"{$image}","date":"{$date}","time":"{$time}"},
EOD;
			}

if ($items != '') {
	$items = substr($items, 0, -1);
}
if ($items_others != '') {
	$items_others = substr($items_others, 0, -1);
}
?>
[{"items":[<?= $items;?>],"items_others":[<?= $items_others;?>]}]
<?php
		}
	}
	
	public function wakeup_phone(){
		date_default_timezone_set("Asia/Calcutta");

		define('API_ACCESS_KEY', 'AAAAdZCD4YU:APA91bFjmo0O-bWCz2ESy0EuG9lz0gjqhAatkakhxJmxK1XdNGEusI5s_vy7v7wT5TeDsjcQH0ZVooDiDEtOU64oTLZpfXqA8EOmGoPBpOCgsZnIZkoOLVgErCQ68i5mGL9T6jnzF7lO');

		//$result = $this->db->query("select * from insert_firebase_token")->result();

		$where = array('status'=>0);
		$result = $this->Drd_Master_Model->select_query("*","tbl_firebase_token",$where);
		foreach($result as $row){
			
			/*echo $row->user_altercode;
			echo "--";*/
			$token = $row->firebase_token;
			
			$id = "123";
			$title = "hello";
			$message = date("h:i:s");

			$data = array
			(
				'id'=>$id,
				'title'=>$title,
				'message'=>$message,
			);

			$fields = array
			(
				'to'=>$token,
				'data'=>$data,
				"priority"=>"high",
			);

			$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			#Send Reponse To FireBase Server	
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,'https://fcm.googleapis.com/fcm/send');
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
			$respose = curl_exec($ch);
			curl_close($ch);
			$array = json_decode($respose, true);
			print_r($array);
			if($array["failure"]==1){
				$where = array('id'=>$row->id);
				$this->Drd_Master_Model->delete_query("tbl_firebase_token",$where);
			}
		}
	}

	public function upload_attendance_api(){
		$items = "";
		$return_id = 0;
		$return_message = "upload_attendance_api error";
		if(!empty($_POST)){
			$api_key = $_POST["api_key"];

			$user_code 		= $_POST["user_code"];
			$user_altercode = $_POST["user_altercode"];
			$latitude 		= $_POST["latitude"];
			$longitude 		= $_POST["longitude"];
			$getdate 		= $_POST["getdate"];
			$gettime 		= $_POST["gettime"];
			$token_key 		= $_POST["token_key"];

			$time = time();
			$date = date("Y-m-d",$time);
			$time = date("H:i",$time);

			$return_id = 0;
			if($getdate==$date && $gettime==$time){
				//token check karta ha taki same time par koi or dusra user to nahi scan kar raha ha
				$where = array(
					'date'=>$date,
					'token_key'=>$token_key,);
				$row = $this->Drd_Master_Model->select_query_row("id","tbl_attendance",$where);
				if(empty($row->id)){

					// insert hota ha iss say
					$dt = array(
						'user_code'=>$user_code,
						'user_altercode'=>$user_altercode,
						'latitude'=>$latitude,
						'longitude'=>$longitude,
						'date'=>$date,
						'time'=>$time,
						'token_key'=>$token_key,
					);
					$this->Drd_Master_Model->insert_query("tbl_attendance",$dt);

					$return_id = 1;
					$return_message = "Attendance Done";
				}
			}
		}
$items .= <<<EOD
{"return_id":"{$return_id}","return_message":"{$return_message}"},
EOD;

if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
	}

	// iss say user kay meter ki value upload hoti ha
	public function upload_meter_photo_api(){
		$items = "";
		$return_id = 0;
		$user_image = "";
		$return_message = "Error Uploading File";
		//header("Content-type: application/json; charset=utf-8");
		if(!empty($_POST)){

			$api_key 		= $_POST["api_key"];

			$user_code 		= $_POST['user_code'];
			$user_altercode = $_POST['user_altercode'];
			$latitude 		= $_POST["latitude"];
			$longitude 		= $_POST["longitude"];
			$message		= $_POST['message'];
			
			$upload_image = $this->upload_meter_path;			
			
			if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

				ini_set('upload_max_filesize', '10M');
				ini_set('post_max_size', '10M');
				ini_set('max_input_time', 300);
				ini_set('max_execution_time', 300);
		
				$config['upload_path'] = $upload_image;  // Define the directory where you want to store the uploaded files.
				$config['allowed_types'] = '*';  // You may want to restrict allowed file types.
				$config['max_size'] = 0;  // Set to 0 to allow any size.
		
				$this->load->library('upload', $config);
		
				if (!$this->upload->do_upload('image')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					print_r($error);
				} else {
					$data = $this->upload->data();
					$image = ($data['file_name']);
					//$this->load->view('upload_success', $data);
				}

			} else {
				// Invalid file or no file uploaded
				$return_message = "Invalid file or no file uploaded.";
			}
			
			$time = time();
			$date = date("Y-m-d",$time);
			$time = date("H:i",$time);

			$dt = array(
				'user_code'=>$user_code,
				'user_altercode'=>$user_altercode,
				'latitude'=>$latitude,
				'longitude'=>$longitude,
				'message'=>$message,
				'image'=>$image,
				'date'=>$date,
				'time'=>$time,);
			$this->Drd_Master_Model->insert_query("tbl_meter",$dt,$where);
			
			$user_image = base_url().$upload_image."/".$fileName;
			$return_id = 1;
			$return_message = "Uploaded Successfully";
		}			
$items .= <<<EOD
{"return_id":"{$return_id}","return_message":"{$return_message}"},
EOD;

if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
	}


	//drd gate app api key start from hear//
	public function get_qr_code_api(){
		if(!empty($_POST)){
			$api_key = $_POST["api_key"];

			$token_key = $this->Drd_Master_Model->create_token_key();

			$time = time();
			$date = date("Y-m-d",$time);
			$time = date("H:i",$time);

			$return_id = "1";

$items .= <<<EOD
{"return_id":"{$return_id}","token_key":"{$token_key}","date":"{$date}","time":"{$time}"},
EOD;
			}
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
	}

	public function upload_delivery_order_photo_more_api(){
		$items = "";
		$return_id = 0;
		$return_message = "Error Uploading File";
		//header("Content-type: application/json; charset=utf-8");
		if(!empty($_POST)){

			$api_key 		= $_POST["api_key"];

			$user_code 		= $_POST['user_code'];
			$user_altercode = $_POST['user_altercode'];
			$chemist_code 	= $_POST['chemist_code'];
			$gstvno 		= $_POST['gstvno'];

			$upload_image = $this->upload_chemist_path;

			ini_set('upload_max_filesize', '10M');
			ini_set('post_max_size', '10M');
			ini_set('max_input_time', 300);
			ini_set('max_execution_time', 300);
	
			$config['upload_path'] = $upload_image;  // Define the directory where you want to store the uploaded files.
			$config['allowed_types'] = '*';  // You may want to restrict allowed file types.
			$config['max_size'] = 0;  // Set to 0 to allow any size.
	
			$this->load->library('upload', $config);
			
			if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
			
				if (!$this->upload->do_upload('image')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					//print_r($error);
					$return_message = $error;
				} else {
					$data = $this->upload->data();
					$image = ($data['file_name']);
					//$this->load->view('upload_success', $data);
					if (strpos($image, "temp_image") !== false){
						//$return_message = "ok ha bhai";
						$image = "";
					}
				}
			} else {
				// Invalid file or no file uploaded
				$return_message = "Invalid file or no file uploaded.";
			}
			
			$time = time();
			$date = date("Y-m-d",$time);
			$time = date("H:i",$time);


			$dt = array(
				'user_code'=>$user_code,
				'user_altercode'=>$user_altercode,
				'chemist_code'=>$chemist_code,
				'gstvno'=>$gstvno,
				'image'=>$image,
				'date'=>$date,
				'time'=>$time,
				'status'=>1,
			);
			$this->Drd_Master_Model->insert_query("tbl_delivery_photo_more",$dt);
			
			$return_id = 1;
			$return_message = "Uploaded Successfully";
		}			
$items .= <<<EOD
{"return_id":"{$return_id}","return_message":"{$return_message}"},
EOD;

if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
	}

	public function delete_delivery_order_photo_more_api(){
		$items = "";
		$return_id = 0;
		$return_message = "Error File";
		//header("Content-type: application/json; charset=utf-8");
		if(!empty($_POST)){

			$api_key 		= $_POST["api_key"];

			$user_code 		= $_POST['user_code'];
			$user_altercode = $_POST['user_altercode'];
			$chemist_code 	= $_POST['chemist_code'];
			$gstvno 		= $_POST['gstvno'];
			$id 			= $_POST['id'];
			
			$where = array(
				'user_code'=>$user_code,
				'user_altercode'=>$user_altercode,
				'chemist_code'=>$chemist_code,
				'gstvno'=>$gstvno,
				'id'=>$id);

			$dt = array(
				'status'=>0,
			);
			$this->Drd_Master_Model->update_query("tbl_delivery_photo_more",$dt,$where);
			
			$return_id = 1;
			$return_message = "Deleted Successfully";
		}			
$items .= <<<EOD
{"return_id":"{$return_id}","return_message":"{$return_message}"},
EOD;

if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
<?php
	}

	public function test_upload(){
		ini_set('upload_max_filesize', '10M');
		ini_set('post_max_size', '10M');
		ini_set('max_input_time', 300);
		ini_set('max_execution_time', 300);

		$config['upload_path'] = './upload_drd_master/';  // Define the directory where you want to store the uploaded files.
		$config['allowed_types'] = '*';  // You may want to restrict allowed file types.
		$config['max_size'] = 0;  // Set to 0 to allow any size.

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('image')) {
			$error = array('error' => $this->upload->display_errors());
			//$this->load->view('upload_form', $error);
			print_r($error);
		} else {
			$data = array('upload_data' => $this->upload->data());
			print_r($data);
			//$this->load->view('upload_success', $data);
		}
		// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// 	$uploadDirectory = 'upload_drd_master/'; // Directory where you want to save uploaded images
		// 	// if (!file_exists($uploadDirectory)) {
		// 	// 	mkdir($uploadDirectory, 0777, true);
		// 	// }
		
		// 	print_r($_FILES);
		// 	print_r($_POST);
		// 	if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
		// 		$tmpName = $_FILES['image']['tmp_name'];
		// 		$fileName = $_FILES['image']['name'];
		// 		$destination = $uploadDirectory . $fileName;
		
		// 		if (move_uploaded_file($tmpName, $destination)) {
		// 			// Image uploaded successfully
		// 			echo "Image uploaded successfully.";
		// 		} else {
		// 			// Failed to move the uploaded file
		// 			echo "Failed to upload image.";
		// 		}
		// 	} else {
		// 		// Invalid file or no file uploaded
		// 		echo "Invalid file or no file uploaded.";
		// 	}
		// } else {
		// 	// Invalid request method
		// 	echo "Invalid request method.";
		// }
	}
}