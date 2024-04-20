<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Api01 extends CI_Controller {	

	public function __construct(){

		parent::__construct();

		$this->load->model("model-drdweb/BankModel");
	}

	public function get_firebase() {

		$firebase	= $_POST['firebase'];

		$date = date('Y-m-d');
		$time = date("H:i",time());
		$datetime = time();

		$dt = array(
			'firebase'=>$firebase,
			'date'=>$date,
			'time'=>$time,
			'datetime'=>$datetime,
		);
		//$this->Scheme_Model->insert_fun("tbl_upload_sms",$dt);
		$this->BankModel->insert_fun("tbl_firebase", $dt);

		$response = array(
            'success' => "1",
            'message' => 'Data add successfully',
			'sender' => $sender,
        );

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}

	public function upload_sms_test() {

		$sender			= $_POST['sender'];
		$message_body 	= $_POST["message_body"];
		$message_type   = $_POST["message_type"];

		$date = date('Y-m-d');
		$time = date("H:i",time());
		$datetime = time();

		$dt = array(
			'sender'=>$sender,
			'message_body'=>$message_body,
			'message_type'=>$message_type,
			'date'=>$date,
			'time'=>$time,
			'datetime'=>$datetime,
		);
		//$this->Scheme_Model->insert_fun("tbl_upload_sms",$dt);
		$this->BankModel->insert_fun("tbl_sms_test", $dt);

		$response = array(
            'success' => "1",
            'message' => 'Data add successfully',
			'sender' => $sender,
        );

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}

	public function upload_sms() {

		$sender			= $_POST['sender'];
		$message_body 	= $_POST["message_body"];
		$message_type   = $_POST["message_type"];

		$date = date('Y-m-d');
		$time = date("H:i",time());
		$datetime = time();

		$dt = array(
			'sender'=>$sender,
			'message_body'=>$message_body,
			'message_type'=>$message_type,
			'date'=>$date,
			'time'=>$time,
			'datetime'=>$datetime,
		);
		$this->Scheme_Model->insert_fun("tbl_upload_sms",$dt);
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