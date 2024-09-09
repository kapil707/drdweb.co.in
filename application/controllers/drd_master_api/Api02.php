<?php 
header("Content-type: application/json; charset=utf-8");
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Api02 extends CI_Controller {	

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

	public function get_login_api()
	{
		error_reporting(0);
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

	public function get_slider_api()
	{
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
			if($versionname=="2.0"){
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
			$date			= date("Y-m-d");
			$time			= date("H:i");
			
			$return_id = 0;

			$where = array(
				'user_code'=>$user_code,
				'user_altercode'=>$user_altercode,
				'getdate'=>$getdate,
				'gettime'=>$gettime,);
			$row = $this->Drd_Master_Model->select_query_row("id","tbl_tracking",$where);
			if(empty($row->id)){
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
				);
				$this->Drd_Master_Model->insert_query("tbl_tracking",$dt);
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
	
	public function upload_profile_image_api()
	{
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
	
	public function get_delivery_order_api()
	{
		$items = "";
		//error_reporting(0);
		if(!empty($_POST)){
			$api_key 		= $_POST["api_key"];
			$user_code		= $_POST['user_code'];
			$user_altercode	= $_POST['user_altercode'];

			$date = date("Y-m-d");
			$where = array(
				'user_altercode'=>$user_altercode,
				'status'=>'0',
				'vdt'=>$date,);
			$result = $this->Drd_Master_Model->select_query("*","tbl_delivery",$where);
			foreach($result as $row)
			{
				$chemist_id = 	$row->chemist_id;
				$amt		=	$row->amt;
				$gstvno		=	$row->gstvno;

				$row1 = $this->db->query("select name from tbl_chemist where altercode='$chemist_id' ")->row();
				$name 		=	$row1->name;

$items .= <<<EOD
{"chemist_id":"{$chemist_id}","name":"{$name}","amt":"{$amt}","gstvno":"{$gstvno}"},
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

	public function get_delivery_order_done_api()
	{
		$items = "";
		//error_reporting(0);
		if(!empty($_POST)){
			$api_key 		= $_POST["api_key"];
			$user_code		= $_POST['user_code'];
			$user_altercode	= $_POST['user_altercode'];

			$where = array(
				'user_altercode'=>$user_altercode,
				'status'=>'1');
			$result = $this->Drd_Master_Model->select_query("*","tbl_delivery",$where);
			foreach($result as $row)
			{
				$chemist_id = 	$row->chemist_id;
				$amt		=	$row->amt;
				$gstvno		=	$row->gstvno;

				$row1 = $this->db->query("select name from tbl_chemist where altercode='$chemist_id' ")->row();
				$name 		=	$row1->name;

$items .= <<<EOD
{"chemist_id":"{$chemist_id}","name":"{$name}","amt":"{$amt}","gstvno":"{$gstvno}"},
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

	public function upload_delivery_order_photo_api()
	{
		$items = "";
		$return_id = 0;
		$return_message = "Error Uploading File";
		//header("Content-type: application/json; charset=utf-8");
		if(!empty($_POST)){

			$api_key 		= $_POST["api_key"];

			$user_code 		= $_POST['user_code'];
			$user_altercode = $_POST['user_altercode'];
			$latitude 		= $_POST['latitude'];
			$longitude 		= $_POST['longitude'];

			$chemist_id 	= $_POST['chemist_id'];
			$gstvno 		= $_POST['gstvno'];
			$message 		= $_POST['message'];

			$payment_message= $_POST['payment_message'];
			$payment_type 	= $_POST['payment_type'];

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
					print_r($error);
				} else {
					$data = $this->upload->data();
					$image1 = ($data['file_name']);
					//$this->load->view('upload_success', $data);
				}

			} else {
				// Invalid file or no file uploaded
				$return_message = "Invalid file or no file uploaded.";
			}

			if (isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK) {
				if (!$this->upload->do_upload('image2')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					print_r($error);
				} else {
					$data = $this->upload->data();
					$image2 = ($data['file_name']);
					//$this->load->view('upload_success', $data);
				}

			} else {
				// Invalid file or no file uploaded
				$return_message = "Invalid file or no file uploaded.";
			}

			if (isset($_FILES['image3']) && $_FILES['image3']['error'] === UPLOAD_ERR_OK) {
				if (!$this->upload->do_upload('image3')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					print_r($error);
				} else {
					$data = $this->upload->data();
					$image3 = ($data['file_name']);
					//$this->load->view('upload_success', $data);
				}

			} else {
				// Invalid file or no file uploaded
				$return_message = "Invalid file or no file uploaded.";
			}

			if (isset($_FILES['image4']) && $_FILES['image4']['error'] === UPLOAD_ERR_OK) {
				if (!$this->upload->do_upload('image4')) {
					$error = array('error' => $this->upload->display_errors());
					//$this->load->view('upload_form', $error);
					print_r($error);
				} else {
					$data = $this->upload->data();
					$image4 = ($data['file_name']);
					//$this->load->view('upload_success', $data);
				}

			} else {
				// Invalid file or no file uploaded
				$return_message = "Invalid file or no file uploaded.";
			}
			
			$time = time();
			$date = date("Y-m-d",$time);
			$time = date("H:i",$time);

			$where = array(
				'user_altercode'=>$user_altercode,
				'gstvno'=>$gstvno,
				'chemist_id'=>$chemist_id,
			);

			$dt = array(
				'message'=>$message,
				'payment_message'=>$payment_message,
				'payment_type'=>$payment_type,
				'latitude'=>$latitude,
				'longitude'=>$longitude,
				'image1'=>$image1,
				'image2'=>$image2,
				'image3'=>$image3,
				'image4'=>$image4,
				'date'=>$date,
				'time'=>$time,
				'status'=>1,
			);
			$this->Drd_Master_Model->update_query("tbl_delivery",$dt,$where);
			
			$return_id = 1;
			$return_message = "Image Uploaded Successfully";
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
		
		if(!empty($_POST)){
			$api_key 		= $_POST["api_key"];

			$user_code 		= $_POST['user_code'];
			$user_altercode = $_POST['user_altercode'];
			$gstvno 		= $_POST['gstvno'];
			$chemist_id		= $_POST['chemist_id'];

			$where = array(
				'user_altercode'=>$user_altercode,
				'gstvno'=>$gstvno,
				'chemist_id'=>$chemist_id,
				'status'=>1);
			$result = $this->Drd_Master_Model->select_query("*","tbl_delivery",$where);
			foreach($result as $row)
			{
				$id  	=	$row->id;
				$image1 = 	"https://drdweb.co.in/upload_drd_master/chemist_photo/".$row->date."/".$row->image1;
				$image2 = 	"https://drdweb.co.in/upload_drd_master/chemist_photo/".$row->date."/".$row->image2;
				$image3 = 	"https://drdweb.co.in/upload_drd_master/chemist_photo/".$row->date."/".$row->image3;
				$image4 = 	"https://drdweb.co.in/upload_drd_master/chemist_photo/".$row->date."/".$row->image4;
				$message = 	"Message : ".$row->message;
				$message2 = "Payment : ".$row->payment_type." / ".$row->payment_message;
				$date  	=	$row->date;
				$time  	=	$row->time;			
$items .= <<<EOD
{"id":"{$id}","image1":"{$image1}","image2":"{$image2}","image3":"{$image3}","image4":"{$image4}","message":"{$message}","message2":"{$message2}","date":"{$date}","time":"{$time}"},
EOD;
			}
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
[<?= $items;?>]
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
	public function upload_meter_photo_api()
	{
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