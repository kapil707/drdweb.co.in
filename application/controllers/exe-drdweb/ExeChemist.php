<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeChemist extends CI_Controller
{
	public function __construct(){

		parent::__construct();

		// Load model
		//$this->load->model("model-drdweb/InvoiceModel");
	}
	public function upload()
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

			$code_array = array();
			foreach ($data as $record) {
				$code_array[] = $record['code'];
				$code = $record['code'];
				$altercode = $record['altercode'];
				$groupcode = $record['groupcode'];
				$name = $record['name'];
				$type = $record['type'];
				$trimname = $record['trimname'];
				$address = $record['address'];
				$address1 = $record['address1'];
				$address2 = $record['address2'];
				$address3 = $record['address3'];
				$telephone = $record['telephone'];
				$telephone1 = $record['telephone1'];
				$mobile = $record['mobile'];
				$email = $record['email'];
				$gstno = $record['gstno'];
				$status = $record['status'];
				$statecode = $record['statecode'];
				$invexport = $record['invexport'];
				$slcd = $record['slcd'];
				$narcolicence = $record['narcolicence'];

				$insert_time = date('Y-m-d,H:i');

				$dt = array(
					'code' => $code,
					'altercode' => $altercode,
					'groupcode' => $groupcode,
					'name' => $name,
					'type' => $type,
					'trimname' => $trimname,
					'address' => $address,
					'address1' => $address1,
					'address2' => $address2,
					'address3' => $address3,
					'telephone' => $telephone,
					'telephone1' => $telephone1,
					'mobile' => $mobile,
					'email' => $email,
					'gstno' => $gstno,
					'status' => $status,
					'statecode' => $statecode,
					'invexport' => $invexport,
					'slcd' => $slcd,
					'narcolicence' => $narcolicence,
					'insert_time' => $insert_time,
				);

				if (!empty($code)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->select_row("tbl_chemist", array('code' => $code,'slcd' => $slcd));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('code' => $code);
						$this->Scheme_Model->edit_fun("tbl_chemist", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_chemist", $dt);
					}
				}
			}
			$commaSeparatedString = implode(',', $code_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["code" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}

	public function upload_test()
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

			$code_array = array();
			foreach ($data as $record) {
				$code_array[] = $record['code'];
				$code = $record['code'];
				$altercode = $record['altercode'];
				$groupcode = $record['groupcode'];
				$name = $record['name'];
				$type = $record['type'];
				$trimname = $record['trimname'];
				$address = $record['address'];
				$address1 = $record['address1'];
				$address2 = $record['address2'];
				$address3 = $record['address3'];
				$telephone = $record['telephone'];
				$telephone1 = $record['telephone1'];
				$mobile = $record['mobile'];
				$email = $record['email'];
				$gstno = $record['gstno'];
				$status = $record['status'];
				$statecode = $record['statecode'];
				$invexport = $record['invexport'];
				$slcd = $record['slcd'];
				$narcolicence = $record['narcolicence'];

				$insert_time = date('Y-m-d,H:i');

				$dt = array(
					'code' => $code,
					'altercode' => $altercode,
					'groupcode' => $groupcode,
					'name' => $name,
					'type' => $type,
					'trimname' => $trimname,
					'address' => $address,
					'address1' => $address1,
					'address2' => $address2,
					'address3' => $address3,
					'telephone' => $telephone,
					'telephone1' => $telephone1,
					'mobile' => $mobile,
					'email' => $email,
					'gstno' => $gstno,
					'status' => $status,
					'statecode' => $statecode,
					'invexport' => $invexport,
					'slcd' => $slcd,
					'narcolicence' => $narcolicence,
					'insert_time' => $insert_time,
				);

				/********************************************* */
				if($status=="*")
				{
					$this->db->query("update tbl_chemist_other set block='1' where code='$code'");
					$this->db->query("update tbl_android_device_id  set logout='1' where user_type='chemist' and chemist_id='$altercode'");
				}
				/********************************************* */
				
				if (!empty($code)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->select_row("tbl_chemist_test", array('code' => $code,'slcd' => $slcd));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('code' => $code);
						$this->Scheme_Model->edit_fun("tbl_chemist_test", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_chemist_test", $dt);
					}
				}
			}
			$commaSeparatedString = implode(',', $code_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["code" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}
}