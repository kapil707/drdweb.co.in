<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Api01 extends CI_Controller {	

	public function __construct(){

		parent::__construct();

		$this->load->model("model-drdweb/BankModel");
	}

	public function send_notification(){
		$serverKey = 'AAAAelgOeX0:APA91bHIZAiLQ1POU1ZXElsgZQKIJ4i7ouFgWCYj6zBOU-5tAC0QBPkqsdrhTh63bQY-EBP-4Dbzcg7dlvFj3PNexDwntPmXzNfmmDsGQRFxZtKiJsbuugEUmxDMoubzlChT-0DaqwME';
		//$fcmToken = 'fLNWXi_fS6SdDQ0Xg6jSMl:APA91bFQMm3M3zy4VJiwWYb_BJyhMTSUF8aBTc5nzyfHESLe6advHPoq5zljmq_5sDhznlSvDWgdJupG3_DO_CzfPMvpQu_W7TS3J_34MdHJuhfjGxP3uYv8bbS2S0pIYk6TzOYzvfcO';

		$fcmToken = 'cQ7o7jrCSDiWofJ_7IVCt0:APA91bGn0gLlYo0aXLtQaMaACG2LDBFaAdJiTULD3eQd-kJYRtnqBT5oAsbLUAocPECJ0dwD_1XVnTvk0bcZePFr5rUK0SifMFhkugpo2-zWuYA95Feb_eG6bOwDprysT9c7HKNDYqN1';

		$notification = [
			'title' => 'Notification Title',
			'body' => 'Notification Body',
		];

		$data = [
			'to' => $fcmToken,
			'notification' => $notification,
		];

		$url = 'https://fcm.googleapis.com/fcm/send';

		$headers = [
			'Authorization: key=' . $serverKey,
			'Content-Type: application/json',
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$response = curl_exec($ch);
		if ($response === false) {
			die('Error sending notification: ' . curl_error($ch));
		}

		curl_close($ch);

		echo $response.'--Notification sent successfully--raman ji phone';
	}

	public function get_firebase() {

		$token			= $_POST['token'];
		$phonenumber 	= $_POST['phonenumber'];
		$device_id 		= $_POST['device_id'];

		$date = date('Y-m-d');
		$time = date("H:i",time());
		$datetime = time();

		$dt = array(
			'token'=>$token,
			'phonenumber'=>$phonenumber,
			'device_id'=>$device_id,
			'date'=>$date,
			'time'=>$time,
			'datetime'=>$datetime,
		);
		//$this->Scheme_Model->insert_fun("tbl_upload_sms",$dt);
		$this->BankModel->insert_fun("tbl_firebase", $dt);

		$my_id = 0;
		$row = $this->BankModel->select_query("SELECT max(_id) as myid FROM `tbl_sms`");
		$row = $row->row();
		if(!empty($row)){
			$my_id = $row->myid;
		}

		$response = array(
            'success' => "1",
            'message' => 'Data add successfully',
			'my_id' => $my_id,
        );

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
	
	public function upload_sms() {

		$_id   			= $_POST["_id"];
		$sender			= $_POST['sender'];
		$message_body 	= $_POST["message_body"];
		$message_type   = $_POST["message_type"];

		$message_date 	= $_POST['message_date'];
		$message_time 	= $_POST['message_time'];


		$date = date('Y-m-d');
		$time = date("H:i",time());
		$datetime = time();

		if(!empty($message_date)){
			$date = $message_date;
		}
		if(!empty($message_time)){
			$time = $message_time;
		}

		$this->BankModel->select_query("delete from tbl_sms where _id='$_id'");

		$dt = array(
			'_id'=>$_id,
			'sender'=>$sender,
			'message_body'=>$message_body,
			'message_type'=>$message_type,
			'date'=>$date,
			'time'=>$time,
			'datetime'=>$datetime,
		);
		$this->BankModel->insert_fun("tbl_sms", $dt);

		$response = array(
            'success' => "1",
            'message' => 'Data add successfully',
			'sender' => $sender,
        );

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}

	public function get_upload_sms() {
		
		if(!empty($_REQUEST)){
			$from_date 	= $_REQUEST["from_date"];
			$to_date	= $_REQUEST['to_date'];

			$jsonArray = array();

			$items = "";
			if(!empty($from_date) && !empty($to_date)){

				$result = $this->BankModel->select_query("select * from tbl_sms where date BETWEEN '$from_date' AND '$to_date'");
				$result = $result->result();

				foreach($result as $row){

					$id = $row->id;
					$sender = $row->sender;
					$message_body = $row->message_body;
					$date = $row->date;
					$time = $row->time;
					$datetime = $row->datetime;
					$chemist_id = $row->chemist_id;

					$dt = array(
						'id' => $id,
						'sender' => $sender,
						'message_body'=>$message_body,
						'date'=>$date,
						'time'=>$time,
						'datetime'=>$datetime,
						'chemist_id'=>$chemist_id,
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
}