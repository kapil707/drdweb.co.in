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

			$i_code_array = array();
			foreach ($data as $record) {
				$i_code_array[] = $record['i_code'];
				$i_code = $record['i_code'];
				$item_code = $record['item_code'];
				$item_name = $record['item_name'];
				$title = $record['title'];
				$packing = $record['packing'];
				$expiry = $record['expiry'];
				$batch_no = $record['batch_no'];
				$batchqty = $record['batchqty'];
				$salescm1 = $record['salescm1'];
				$salescm2 = $record['salescm2'];
				$sale_rate = $record['sale_rate'];
				if(empty($sale_rate)){
					$sale_rate = 0;
				}
				$mrp = $record['mrp'];
				if(empty($mrp)){
					$mrp = 0;
				}
				$final_price = $record['final_price'];
				if(empty($final_price)){
					$final_price = 0;
				}
				$costrate = $record['costrate'];
				if(empty($costrate)){
					$costrate = 0;
				}
				$compcode = $record['compcode'];
				$comp_altercode = $record['comp_altercode'];
				$company_name = $record['company_name'];
				$company_full_name = $record['company_full_name'];
				$division = $record['division'];
				$qscm = $record['qscm'];
				$hscm = $record['hscm'];
				$misc_settings = $record['misc_settings'];
				$item_date = $record['item_date'];
				$itemcat = $record['itemcat'];
				$gstper = $record['gstper'];
				$itemjoinid = $record['itemjoinid'];
				$present = $record['present'];
				if(empty($present)){
					$present = 0;
				}
				$time = $record['time'];
				$margin = $record['margin'];
				$status = $record['status'];
				$discount = $record['discount'];
				if(empty($discount)){
					$discount = 0;
				}
				$note = $record['note'];
				$category = $record['category'];
				$featured = $record['featured'];
				$image1 = $record['image1'];
				$image2 = $record['image2'];
				$image3 = $record['image3'];
				$image4 = $record['image4'];
				$title2 = $record['title2'];
				$description = $record['description'];

				$insert_time = date('Y-m-d,H:i');

				$dt = array(
					'i_code' => $i_code,
					'item_code' => $item_code,
					'item_name' => $item_name,
					'title' => $title,
					'packing' => $packing,
					'expiry' => $expiry,
					'batch_no' => $batch_no,
					'batchqty' => $batchqty,
					'salescm1' => $salescm1,
					'salescm2' => $salescm2,
					'sale_rate' => $sale_rate,
					'mrp' => $mrp,
					'final_price' => $final_price,
					'costrate' => $costrate,
					'compcode' => $compcode,
					'comp_altercode' => $comp_altercode,
					'company_name' => $company_name,
					'company_full_name' => $company_full_name,
					'division' => $division,
					'qscm' => $qscm,
					'hscm' => $hscm,
					'misc_settings' => $misc_settings,
					'item_date' => $item_date,
					'itemcat' => $itemcat,
					'gstper' => $gstper,
					'itemjoinid' => $itemjoinid,
					'present' => $present,
					'time' => $time,
					'margin' => $margin,
					'status' => $status,
					'discount' => $discount,
					'note' => $note,
					'category' => $category,
					'featured' => $featured,
					'image1' => $image1,
					'image2' => $image2,
					'image3' => $image3,
					'image4' => $image4,
					'title2' => $title2,
					'description' => $description,
					'insert_time' => $insert_time,
				);

				if (!empty($i_code)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->select_row("tbl_medicine_new", array('i_code' => $i_code));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('i_code' => $i_code);
						$this->Scheme_Model->edit_fun("tbl_medicine_new", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_medicine_new", $dt);
					}
				}
			}
			$commaSeparatedString = implode(',', $i_code_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["i_code" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}

	public function upload_medicine_test()
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

			$i_code_array = array();
			foreach ($data as $record) {
				$i_code_array[] = $record['i_code'];
				$i_code = $record['i_code'];
				$item_code = $record['item_code'];
				$item_name = $record['item_name'];
				$title = $record['title'];
				$packing = $record['packing'];
				$expiry = $record['expiry'];
				$batch_no = $record['batch_no'];
				$batchqty = $record['batchqty'];
				$salescm1 = $record['salescm1'];
				$salescm2 = $record['salescm2'];
				$sale_rate = $record['sale_rate'];
				if(empty($sale_rate)){
					$sale_rate = 0;
				}
				$mrp = $record['mrp'];
				if(empty($mrp)){
					$mrp = 0;
				}
				$final_price = $record['final_price'];
				if(empty($final_price)){
					$final_price = 0;
				}
				$costrate = $record['costrate'];
				if(empty($costrate)){
					$costrate = 0;
				}
				$compcode = $record['compcode'];
				$comp_altercode = $record['comp_altercode'];
				$company_name = $record['company_name'];
				$company_full_name = $record['company_full_name'];
				$division = $record['division'];
				$qscm = $record['qscm'];
				$hscm = $record['hscm'];
				$misc_settings = $record['misc_settings'];
				$item_date = $record['item_date'];
				$itemcat = $record['itemcat'];
				$gstper = $record['gstper'];
				$itemjoinid = $record['itemjoinid'];
				$present = $record['present'];
				if(empty($present)){
					$present = 0;
				}
				$time = $record['time'];
				$margin = $record['margin'];
				$status = $record['status'];
				$discount = $record['discount'];
				if(empty($discount)){
					$discount = 0;
				}
				$note = $record['note'];
				$category = $record['category'];
				$featured = $record['featured'];
				$image1 = $record['image1'];
				$image2 = $record['image2'];
				$image3 = $record['image3'];
				$image4 = $record['image4'];
				$title2 = $record['title2'];
				$description = $record['description'];

				$insert_time = date('Y-m-d,H:i');

				$dt = array(
					'i_code' => $i_code,
					'item_code' => $item_code,
					'item_name' => $item_name,
					'title' => $title,
					'packing' => $packing,
					'expiry' => $expiry,
					'batch_no' => $batch_no,
					'batchqty' => $batchqty,
					'salescm1' => $salescm1,
					'salescm2' => $salescm2,
					'sale_rate' => $sale_rate,
					'mrp' => $mrp,
					'final_price' => $final_price,
					'costrate' => $costrate,
					'compcode' => $compcode,
					'comp_altercode' => $comp_altercode,
					'company_name' => $company_name,
					'company_full_name' => $company_full_name,
					'division' => $division,
					'qscm' => $qscm,
					'hscm' => $hscm,
					'misc_settings' => $misc_settings,
					'item_date' => $item_date,
					'itemcat' => $itemcat,
					'gstper' => $gstper,
					'itemjoinid' => $itemjoinid,
					'present' => $present,
					'time' => $time,
					'margin' => $margin,
					'status' => $status,
					'discount' => $discount,
					'note' => $note,
					'category' => $category,
					'featured' => $featured,
					'image1' => $image1,
					'image2' => $image2,
					'image3' => $image3,
					'image4' => $image4,
					'title2' => $title2,
					'description' => $description,
					'insert_time' => $insert_time,
				);

				if (!empty($i_code)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->select_row("tbl_medicine_new_test", array('i_code' => $i_code));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('i_code' => $i_code);
						$this->Scheme_Model->edit_fun("tbl_medicine_new_test", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_medicine_new_test", $dt);
					}
				}
			}
			$commaSeparatedString = implode(',', $i_code_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["i_code" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}
}