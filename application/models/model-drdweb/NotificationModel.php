<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class NotificationModel extends CI_Model
{
	public function __construct() {
        parent::__construct();
    }
	public function insert_android_notification($funtype,$title,$message,$chemist_id,$user_type,$itemid='0',$compid='0',$division='',$image='',$insert_type='')
	{
		
		$device_id =  "default"; // yha sirf website or android me show ke liya use hota ha

		$status = $firebase_status = "0";
		$respose = "";
		$dt = array(
			'title'=>$title,
			'message'=>$message,
			'user_type'=>$user_type,
			'chemist_id'=>$chemist_id,
			'device_id'=>$device_id,
			'funtype'=>$funtype,
			'itemid'=>$itemid,
			'compid'=>$compid,
			'division'=>$division,
			'image'=>$image,
			'insert_type'=>$insert_type,
			'status'=>$status,
			'firebase_status'=>$firebase_status,
			'respose'=>$respose,
			'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'timestamp' => time(),);
		
		return $this->Scheme_Model->insert_fun("tbl_android_notification",$dt);
	}

	
	function getAccessToken()
	{
		$serviceAccount = json_decode(file_get_contents('firbase_token/my.json'), true);
		
		$header = [
			"alg" => "RS256",
			"typ" => "JWT",
		];

		$iat = time();
		$exp = $iat + 3600;

		$payload = [
			"iss" => $serviceAccount['client_email'],
			"scope" => "https://www.googleapis.com/auth/firebase.messaging",
			"aud" => "https://oauth2.googleapis.com/token",
			"iat" => $iat,
			"exp" => $exp,
		];

		$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
		$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

		$signature = '';
		openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $serviceAccount['private_key'], "SHA256");
		$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

		$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

		// Request access token
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
			'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
			'assertion' => $jwt,
		]));

		$response = json_decode(curl_exec($ch), true);
		curl_close($ch);

		if (!isset($response['access_token'])) {
			die('Error getting access token');
		}

		return $response['access_token'];
	}


	function send_android_notification()
	{
		//error_reporting(0);
		//define('API_ACCESS_KEY', 'AAAAdZCD4YU:APA91bFjmo0O-bWCz2ESy0EuG9lz0gjqhAatkakhxJmxK1XdNGEusI5s_vy7v7wT5TeDsjcQH0ZVooDiDEtOU64oTLZpfXqA8EOmGoPBpOCgsZnIZkoOLVgErCQ68i5mGL9T6jnzF7lO');
		
		$time = time();
		$date = date("Y-m-d",$time);
		$image= $company_full_name = "";
		$where = array('firebase_status'=>'0','device_id'=>'default');
		$this->db->where($where);
		$this->db->order_by('id','asc');
		$this->db->limit(1);
		$query = $this->db->get("tbl_android_notification")->result();
		foreach($query as $row)
		{
			$response   = ""; 
			$id 		= $row->id;
			$user_type 	= $row->user_type;
			$chemist_id = $row->chemist_id;
			$title 		= ($row->title);
			$message    = ($row->message);
			//$message    = htmlentities(str_replace("\n","<br>",$message));
			$funtype 	= $row->funtype;
			$item_id 	= $row->id;
			$itemid 	= $row->itemid;
			$division 	= $row->division;
			$image1		= $row->image;
			if($funtype=="2")
			{
				$itemid = $row->compid;
				$row1   =  $this->db->query("select company_full_name from tbl_medicine where compcode='$itemid'")->row();
				$company_full_name = base64_decode($row1->company_full_name);
				
				$row1  =  $this->db->query("select image from tbl_division_wise where compcode='$itemid'")->row();
				if($row1->image!=""){
					//$image =   base_url()."uploads/manage_division_wise/photo/resize/".$row1->image;
					
					// jab tak old server on ha to iss code say img aya ge notication m
					$image =   constant('main_site')."uploads/manage_division_wise/photo/resize/".$row1->image;
				}
				else{
					$image = constant('main_site')."uploads/manage_users/photo/photo_1562659909.png";
				}
			}
			
			if($image1!="")
			{
				$image =   constant('main_site')."uploads/manage_notification/photo/resize/".$image1;
				
				// jab tak old server on ha to iss code say img aya ge notication m
				$image =   constant('main_site')."uploads/manage_notification/photo/resize/".$image1;
			}
			
			if($image=="")
			{
				$image = "not";
			}
			if($company_full_name=="")
			{
				$company_full_name = "not";
			}
			if($itemid=="")
			{
				$itemid = "not";
			}
			if($division=="")
			{
				$division = "not";
			}
						
			$query1 = $this->db->query("select firebase_token from tbl_android_device_id where chemist_id='$chemist_id' and user_type='$user_type'")->result();
			foreach($query1 as $row1)
			{
				$token = $row1->firebase_token;
				$notification_body = [
					"message" => [
						"token" => $token,
						"notification" => [
							"title"=>$title,
							"body"=>$message,
							"image"=>$image,
						],
						"data" => [
							'id'=>$id,
							'title'=>$title,
							'message'=>$message,
							'funtype'=>$funtype,
							'item_id'=>$item_id,
							'itemid'=>$itemid,
							'division'=>$division,
							'company_full_name'=>$company_full_name,
							'image'=>$image,
						],
						"android" => [
							"priority" => "HIGH"
						]
					]
				];
				
				$accessToken = $this->Scheme_Model->get_website_data("firebase_bearer_access_token");
				$headers = [
					'Authorization: Bearer ' . $accessToken,
					'Content-Type: application/json',
				];
				#Send Reponse To FireBase Server	
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL,'https://fcm.googleapis.com/v1/projects/drd-noti-fire-base/messages:send');
				curl_setopt($ch,CURLOPT_POST,true);
				curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($notification_body));
				$response = curl_exec($ch);
				//echo $respose;
				curl_close($ch);

				// Check for errors
				if ($response === false) {
					echo 'Curl error: ' . curl_error($ch);
				} else {
					// Decode the response
					$responseData = json_decode($response, true);

					// Check if 'name' exists in the response
					if (isset($responseData['name'])) {
						$name = $responseData['name'];

						$this->db->query("update tbl_android_notification set firebase_status='1',respose='$name' where firebase_status='0' and id='$id'");
					} else {
						echo "Failed to send notification. Response: " . $response;
						
						/******************************************************** */
						$mydata = base64_encode($this->getAccessToken());
						$dt = array('mydata'=>$mydata,);
						$where = array('page_type'=>"firebase_bearer_access_token");
						$result = $this->Scheme_Model->edit_fun("tbl_website",$dt,$where);
						/******************************************************** */

						die();
					}
				}
			}
			if(!empty($response)){
				$this->db->query("update tbl_android_notification set firebase_status='1',respose='no' where firebase_status='1' and id='$id'");
			}
		}
	}
}