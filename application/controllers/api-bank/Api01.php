<?php 
header("Content-type: application/json; charset=utf-8");
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Api01 extends CI_Controller {	

	public function __construct(){
		parent::__construct();
	}

	function edit_fun($tbl,$dt,$where)
	{
		$db_bank = $this->load->database('default', TRUE);
		if($db_bank->update($tbl,$dt,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function get_invoice_api() {
		$jsonArray = array();
		$items = "";

		$result = $this->db->select("select * from tbl_invoice where upload_status=0 limit 1000");
		$result = $result->result();
		foreach($result as $row) {
			
			$id = $row->id;
			$mtime = $row->mtime;
			$dispatchtime = $row->dispatchtime;
			$date = $row->date;
			$vno = $row->vno;
			$tagno = $row->tagno;
			$gstvno = $row->gstvno;
			$pickedby = $row->pickedby;
			$checkedby = $row->checkedby;
			$deliverby = $row->deliverby;
			$amt = $row->amt;
			$taxamt = $row->taxamt;
			$acno = $row->acno;
			$chemist_id = $row->chemist_id;
			$status = $row->status;
			$insert_time = $row->insert_time;

			$dt = array(
				'mtime' => $mtime,
				'mtime' => $mtime,
				'dispatchtime' => $dispatchtime,
				'date' => $date,
				'vno'=>$vno,
				'tagno'=>$tagno,
				'gstvno'=>$gstvno,
				'pickedby'=>$pickedby,
				'checkedby'=>$checkedby,
				'deliverby'=>$deliverby,
				'amt'=>$amt,
				'taxamt'=>$taxamt,
				'acno'=>$acno,
				'chemist_id'=>$chemist_id,
				'status'=>$status,
				'insert_time'=>$insert_time,
			);
			$jsonArray[] = $dt;

			$where = array('id'=>$id);
			$dt = array(				
				'upload_status'=>'1',
			);
			$this->Scheme_Model->edit_fun("tbl_invoice", $dt,$where);
		}
		if(!empty($jsonArray)){
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

	public function get_bank_whatsapp_api() {
		$jsonArray = array();
		$items = "";

		$result = $this->db->query("select * from tbl_whatsapp_message where upload_status=0 limit 1000");
		$result = $result->result();
		foreach($result as $row) {
			
			$id = $row->id;
			$message_id = $row->message_id;
			$body 		= $row->body;
			$date 		= $row->date;
			$extracted_text = $row->extracted_text;
			$from_number = $row->from_number;
			$ist_timestamp = $row->ist_timestamp;
			$screenshot_image = $row->screenshot_image;
			$sender_name_place = $row->sender_name_place;
			$timestamp = $row->timestamp;
			$vision_text = $row->vision_text;
			$quoted_text = $row->reply_id;

			$dt = array(
				'message_id' => $message_id,
				'body' => $body,
				'date' => $date,
				'extracted_text' => $extracted_text,
				'from_number'=>$from_number,
				'ist_timestamp'=>$ist_timestamp,
				'screenshot_image'=>$screenshot_image,
				'sender_name_place'=>$sender_name_place,
				'timestamp'=>$timestamp,
				'vision_text'=>$vision_text,
				'quoted_text'=>$quoted_text,
			);
			$jsonArray[] = $dt;

			$where = array('id'=>$id);
			$dt = array(				
				'upload_status'=>'1',
			);
			$this->edit_fun("tbl_whatsapp_message", $dt,$where);
		}
		if(!empty($jsonArray)){
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

	public function get_chemist_api() {
		$jsonArray = array();
		$items = "";

		$result = $this->db->query("select * from tbl_chemist where upload_status=0 limit 250");
		$result = $result->result();
		foreach($result as $row) {
			
			$id = $row->id;
			$code = $row->code;
			$altercode  = $row->altercode;
			$groupcode 		= $row->groupcode;
			$name = $row->name;
			$type = $row->type;
			$trimname = $row->trimname;
			$address = $row->address;
			$address1 = $row->address1;
			$address2 = $row->address2;
			$address3 = $row->address3;
			$telephone = $row->telephone;
			$telephone1 = $row->telephone1;
			$mobile = $row->mobile;
			$email = $row->email;
			$gstno = $row->gstno;
			$statecode = $row->statecode;
			$status = $row->status;
			$invexport = $row->invexport;
			$slcd = $row->slcd;
			$narcolicence = $row->narcolicence;
			$insert_time = $row->insert_time;

			$dt = array(
				'code' => $code,
				'altercode' => $altercode,
				'groupcode' => $groupcode,
				'name' => $name,
				'type'=>$type,
				'trimname'=>$trimname,
				'address'=>$address,
				'address1'=>$address1,
				'address2'=>$address2,
				'address3'=>$address3,
				'telephone'=>$telephone,
				'telephone1'=>$telephone1,
				'mobile'=>$mobile,
				'email'=>$email,
				'gstno'=>$gstno,
				'statecode'=>$statecode,
				'status'=>$status,
				'invexport'=>$invexport,
				'slcd'=>$slcd,
				'narcolicence'=>$narcolicence,
				'insert_time'=>$insert_time,
			);
			$jsonArray[] = $dt;

			$where = array('id'=>$id);
			$dt = array(				
				'upload_status'=>'1',
			);
			$this->Scheme_Model->edit_fun("tbl_chemist", $dt,$where);
		}
		if(!empty($jsonArray)){
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