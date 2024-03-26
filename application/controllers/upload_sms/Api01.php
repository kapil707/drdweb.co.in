<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Api01 extends CI_Controller {	

	public function upload_sms() {

		$sender			= $_POST['sender'];
		$message_body 	= $_POST["message_body"];

		$date = date('Y-m-d');
		$time = date("H:i",time());
		$datetime = time();

		$dt = array(
			'sender'=>$sender,
			'message_body'=>$message_body,
			'date'=>$date,
			'time'=>$time,
			'datetime'=>$datetime,
		);
		$this->Scheme_Model->insert_fun("tbl_upload_sms",$dt);

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
		
		$from_date 	= $_POST["from_date"];
		$to_date	= $_POST['to_date'];

		$jsonArray = array();

		$items = "";
		if(!empty($from_date) && !empty($to_date)){

			$result = $this->db->query("select * from tbl_upload_sms where date BETWEEN '$from_date' AND '$to_date'")->result();

			foreach($result as $row){

				$id = $row->id;
				$sender = $row->sender;
				$message_body = $row->message_body;
				$date = $row->date;
				$time = $row->time;
				$datetime = $row->datetime;

				$dt = array(
					'id' => $id,
					'sender' => $sender,
					'message_body'=>$message_body,
					'date'=>$date,
					'time'=>$time,
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

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}