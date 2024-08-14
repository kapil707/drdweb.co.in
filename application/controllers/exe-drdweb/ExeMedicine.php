<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeMedicine extends CI_Controller
{
	public function __construct(){

		parent::__construct();

		// Load model
		//$this->load->model("model-drdweb/InvoiceModel");
	}
	public function upload_medicine()
	{
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

			$i_code = "";
			foreach ($data as $record) {
				if (!empty($i_code)) {
					$i_code = $record['i_code'];
					$name = $record['name'];
					
					$dt = array(
						'i_code'=>$i_code,
					);

					if (!empty($i_code)) {
						$this->Scheme_Model->insert_fun("tbl_medicine_test", $dt);
					}
				}
			}

			// Response dena
			echo json_encode(["i_code" => $i_code,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["i_code" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}
}