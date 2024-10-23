<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeWhatsApp extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		$this->load->model("model-drdweb/WhatsAppModel");
	}
	public function upload()
	{
		//OPTIMIZE TABLE tbl_medicine;

		// Data ko read karna (input stream se)
		$inputData = file_get_contents("php://input");

		// JSON data ko PHP array me convert karna
		$data = json_decode($inputData, true);

		
		// Data ko check karna
		if ($data && is_array($data)) {
			// Aap yaha data ko process kar sakte hain, jaise ki database me save karna, logging karna, etc.
			
			//print_r($data);

			// Example: Data ko print karna (ya log karna)
			//file_put_contents("log.txt", print_r($data, true), FILE_APPEND);

			foreach ($data as $record) {
				$id = $record['id'];
				$mobile = $record['mobile'];
				$message = $record['message'];
				$chemist_id = $record['chemist_id'];
				$media = $record['media'];

				$this->WhatsAppModel->insert_whatsapp($mobile,$message,$chemist_id,$media);
			}
			// Response dena
			echo json_encode(["return_values" => $id,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["return_values" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}

	public function upload_test()
	{
		//OPTIMIZE TABLE tbl_medicine;

		// Data ko read karna (input stream se)
		$inputData = file_get_contents("php://input");

		// JSON data ko PHP array me convert karna
		$data = json_decode($inputData, true);

		
		// Data ko check karna
		if ($data && is_array($data)) {
			// Aap yaha data ko process kar sakte hain, jaise ki database me save karna, logging karna, etc.
			
			//print_r($data);

			// Example: Data ko print karna (ya log karna)
			//file_put_contents("log.txt", print_r($data, true), FILE_APPEND);

			foreach ($data as $record) {
				$id = $record['id'];
				$mobile = $record['mobile'];
				$message = $record['message'];
				$media = $record['media'];
				$chemist_id = $record['chemist_id'];

				$this->WhatsAppModel->insert_whatsapp($mobile,$message,$chemist_id,$media);
			}
			// Response dena
			echo json_encode(["return_values" => $id,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["return_values" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}
}