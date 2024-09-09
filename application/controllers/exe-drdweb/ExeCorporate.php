<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeCorporate extends CI_Controller
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
				$compcode = $record['compcode'];
				$staffname = $record['staffname'];
				$degn = $record['degn'];
				$mobilenumber = $record['mobilenumber'];
				$division = $record['division'];
				$memail = $record['memail'];
				$slcd = $record['slcd'];
				$automail = $record['automail'];
				$staffid = $record['staffid'];
				$staffpwd = $record['staffpwd'];
				$withsalerep = $record['withsalerep'];
				$salerepdt = $record['salerepdt'];
				$withcustsale = $record['withcustsale'];
				$custrepdt = $record['custrepdt'];
				$branchstatus = $record['branchstatus'];
				$maxosamt = $record['maxosamt'];
				$maxosinv = $record['maxosinv'];
				$snsrepdt = $record['snsrepdt'];
				$bank = $record['bank'];
				$chqno = $record['chqno'];
				$comp_altercode = $record['comp_altercode'];
				$company_full_name = $record['company_full_name'];

				$insert_time = date('Y-m-d,H:i');

				$dt = array(
					'code' => $code,
					'compcode' => $compcode,
					'staffname' => $staffname,
					'degn' => $degn,
					'mobilenumber' => $mobilenumber,
					'division' => $division,
					'memail' => $memail,
					'slcd' => $slcd,
					'automail' => $automail,
					'staffid' => $staffid,
					'staffpwd' => $staffpwd,
					'withsalerep' => $withsalerep,
					'salerepdt' => $salerepdt,
					'withcustsale' => $withcustsale,
					'custrepdt' => $custrepdt,
					'branchstatus' => $branchstatus,
					'maxosamt' => $maxosamt,
					'maxosinv' => $maxosinv,
					'snsrepdt' => $snsrepdt,
					'bank' => $bank,
					'chqno' => $chqno,
					'comp_altercode' => $comp_altercode,
					'company_full_name' => $company_full_name,
					'insert_time' => $insert_time,
				);

				if (!empty($code)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->select_row("tbl_corporate", array('code' => $code));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('code' => $code);
						$this->Scheme_Model->edit_fun("tbl_corporate", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_corporate", $dt);
					}

					/******************************************************* */
					$daily_date = date('Y-m-d');
					// Check karo agar record already exist karta hai
					$existing_record1 = $this->Scheme_Model->select_row("tbl_corporate_other", array('code' => $code));
					if ($existing_record1) {
						$dt1 = array(
							'daily_date'=>$daily_date,
							'download_status'=>1,
						);
						// Agar record exist karta hai to update karo
						$where1 = array('code' => $code);
						$this->Scheme_Model->edit_fun("tbl_corporate_other", $dt1, $where1);
					} else {
						$dt1 = array(
							'code'=>$code,
							'status'=>0,
							'password'=>'',
							'daily_date'=>$daily_date,
							'whatsapp_message'=>0,
							'item_wise_report'=>0,
							'item_wise_report_daily_email'=>0,
							'item_wise_report_monthly_email'=>0,	
							'chemist_wise_report'=>0,
							'chemist_wise_report_daily_email'=>0,
							'chemist_wise_report_monthly_email'=>0,
							'stock_and_sales_analysis'=>1,
							'stock_and_sales_analysis_daily_email'=>1,
							'download_status'=>0,
						);
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_corporate_other", $dt1);
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
				$compcode = $record['compcode'];
				$staffname = $record['staffname'];
				$degn = $record['degn'];
				$mobilenumber = $record['mobilenumber'];
				$division = $record['division'];
				$memail = $record['memail'];
				$slcd = $record['slcd'];
				$automail = $record['automail'];
				$staffid = $record['staffid'];
				$staffpwd = $record['staffpwd'];
				$withsalerep = $record['withsalerep'];
				$salerepdt = $record['salerepdt'];
				$withcustsale = $record['withcustsale'];
				$custrepdt = $record['custrepdt'];
				$branchstatus = $record['branchstatus'];
				$maxosamt = $record['maxosamt'];
				$maxosinv = $record['maxosinv'];
				$snsrepdt = $record['snsrepdt'];
				$bank = $record['bank'];
				$chqno = $record['chqno'];
				$comp_altercode = $record['comp_altercode'];
				$company_full_name = $record['company_full_name'];

				$insert_time = date('Y-m-d,H:i');

				$dt = array(
					'code' => $code,
					'compcode' => $compcode,
					'staffname' => $staffname,
					'degn' => $degn,
					'mobilenumber' => $mobilenumber,
					'division' => $division,
					'memail' => $memail,
					'slcd' => $slcd,
					'automail' => $automail,
					'staffid' => $staffid,
					'staffpwd' => $staffpwd,
					'withsalerep' => $withsalerep,
					'salerepdt' => $salerepdt,
					'withcustsale' => $withcustsale,
					'custrepdt' => $custrepdt,
					'branchstatus' => $branchstatus,
					'maxosamt' => $maxosamt,
					'maxosinv' => $maxosinv,
					'snsrepdt' => $snsrepdt,
					'bank' => $bank,
					'chqno' => $chqno,
					'comp_altercode' => $comp_altercode,
					'company_full_name' => $company_full_name,
					'insert_time' => $insert_time,
				);

				if (!empty($code)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->select_row("tbl_corporate_test", array('code' => $code));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('code' => $code);
						$this->Scheme_Model->edit_fun("tbl_corporate_test", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_corporate_test", $dt);
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

	public function download(){
		$result = $this->db->query("select * from tbl_corporate_other where download_status=0 order by id asc limit 100");
		//$result = $result->result();
		if ($result) {
			// Fetch the result array
			$result_array = $result->result_array();
			
			/*// Add a new value to each element in the array
			foreach ($result_array as &$row) {
				$row['bacno'] = '9972'; // Replace 'new_key' with the key name and 'new_value' with the value you want to add
				$row['invoice1'] = '';
				$row['invoice2'] = 'N/a';
			}*/

			foreach ($result_array as $row) {
				$id = $row["id"];
				$this->db->query("update tbl_corporate_other set download_status=1 where id='$id'");
			}
		
			// Output the result as JSON
			echo json_encode($result_array);
		} else {
			// Handle the case where the query fails
			echo json_encode(array("error" => "Failed to fetch records"));
		}
	}
}