<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeInvoice extends CI_Controller
{
	public function __construct(){
		parent::__construct();
	}

	public function upload_invoice()
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
				$id_array[] 	= $record['id'];
				
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
				
				if (!empty($gstvno)) {
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

	public function upload_invoice_item()
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
				$id_array[] 	= $record['id'];
				
				$id 			= $record["id"];
				$vno 			= $record["vno"];
				$vdt 			= $record["vdt"];
				$psrlno			= $record["psrlno"];
				$itemc 			= $record["itemc"];
				$batch 			= $record["batch"];
				$qty 			= $record["qty"];
				$fqty 			= $record["fqty"];
				$ntrate 		= $record["ntrate"];
				$ftrate 		= $record["ftrate"];
				$dis 			= $record["dis"];
				$disamt 		= $record["disamt"];
				$netamt 		= $record["netamt"];
				$halfp 			= $record["halfp"];
				$mrp 			= $record["mrp"];
				$hsncode 		= $record["hsncode"];
				$expiry 		= $record["expiry"];
				$scm1 			= $record["scm1"];
				$scm2 			= $record["scm2"];
				$scmper 		= $record["scmper"];
				$localcent 		= $record["localcent"];
				$excise 		= $record["excise"];
				$cgst 			= $record["cgst"];
				$sgst 			= $record["sgst"];
				$igst 			= $record["igst"];
				$igst 			= $record["igst"];
				$gdn 			= $record["gdn"];

				
				$insert_time 	= date('Y-m-d,H:i');

				$dt = array(
					'vno' => $vno,
					'vdt' => $vdt,
					'vdt' => $vdt,
					'itemc' => $itemc,
					'batch' => $batch,
					'qty' => $qty,
					'qty' => $qty,
					'ntrate' => $ntrate,
					'ftrate' => $ftrate,
					'dis' => $dis,
					'disamt' => $disamt,
					'netamt' => $netamt,
					'halfp' => $halfp,
					'mrp' => $mrp,
					'hsncode' => $hsncode,
					'expiry' => $expiry,
					'scm1' => $scm1,
					'scm2' => $scm2,
					'scmper' => $scmper,
					'localcent' => $localcent,
					'excise' => $excise,
					'cgst' => $cgst,
					'sgst' => $sgst,
					'igst' => $igst,
					'adnlvat' => $adnlvat,
					'gdn' => $gdn,
					'insert_time' => $insert_time,
				);
				
				/*if (!empty($gstvno)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->select_row("tbl_invoice_item", array('gstvno' => $gstvno));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('gstvno' => $gstvno);
						$this->Scheme_Model->edit_fun("tbl_invoice_item", $dt, $where);
					} else {*/
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_invoice_item", $dt);
					/*}
				}*/
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