<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeInvoice extends CI_Controller
{
	public function __construct(){
		parent::__construct();
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

			$id_array = array();
			foreach ($data as $record) {
				$id_array[] = $record['i_code'];
				
				$id 			= $record["id"];
				$mtime 			= $record["mtime"];
				$dispatchtime 	= $record["dispatchtime"];
				$date 			= $record["date"];
				$vno 			= $record["vno"];
				$tagno 			= $record["tagno"];
				$gstvno 		= $record["gstvno"];
				$pickedby 		= $record["pickedby"];
				$checkedby 		= $record["checkedby"];
				$deliverby 		= $record["deliverby"];
				$amt 			= $record["amt"];
				$taxamt 		= $record["taxamt"];
				$acno 			= $record["acno"];
				$chemist_id 	= $record["chemist_id"];
				
				$insert_time 	= date('Y-m-d,H:i');

				$dt = array(
					'mtime' => $mtime,
					'dispatchtime' => $dispatchtime,
					'date' => $date,
					'vno' => $vno,
					'tagno' => $tagno,
					'gstvno' => $gstvno,
					'pickedby' => $pickedby,
					'checkedby' => $checkedby,
					'deliverby' => $deliverby,
					'amt' => $amt,
					'taxamt' => $taxamt,
					'acno' => $acno,
					'chemist_id' => $chemist_id,
					'status' => 0,
					'insert_time' => $insert_time,
				);
				
				if (!empty($i_code)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->select_row("tbl_invoice", array('gstvno' => $gstvno));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('gstvno' => $gstvno);
						$this->Scheme_Model->edit_fun("tbl_invoice", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_invoice", $dt);
					}
				}
			}
			$commaSeparatedString = implode(',', $id_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["return_values" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}
}