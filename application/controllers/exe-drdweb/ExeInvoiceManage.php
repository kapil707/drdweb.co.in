<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeInvoiceManage extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		// Load model
		$this->load->model("model-drdweb/InvoiceModel");
	}
	public function upload_invoice()
	{
		$isdone = "";
		
		$data = json_decode(file_get_contents('php://input'),true);
		// Data ko check karna
		if ($data && is_array($data)) {

			$id_array = array();
			foreach ($data as $record) {

				if (!empty($record["id"])) {
					$id_array[] 	= $record['id'];
					$id 			= $record["id"];
					$dispatchtime 	= $record["dispatchtime"];
					$vdt 			= $record["vdt"];
					$vno 			= $record["vno"];
					$vtype 			= $record["vtype"];
					$gstvno 		= $record["gstvno"];
					$pickedby 		= $record["pickedby"];
					$checkedby 		= $record["checkedby"];
					$deliverby 		= $record["deliverby"];
					$amt 			= $record["amt"];
					$taxamt 		= $record["taxamt"];
					$acno 			= $record["acno"];
					$chemist_id 	= $record["chemist_id"];
					$chemist_name 	= $record["chemist_name"];
					$chemist_email 	= $record["chemist_email"];
					$chemist_mobile = $record["chemist_mobile"];
					$date 			= $record["date"];
					$update_at 		= $record["update_at"];
					$status 		= $record["status"];
					
					/*******************************************/
					$where = array('vno'=>$vno,'vdt'=>$vdt);
					$this->InvoiceModel->delete_fun("tbl_invoice_new",$where);
					/*******************************************/
					
					/*******************************************
					$where = array('vno'=>$vno,'vdt'=>$vdt);
					$this->InvoiceModel->delete_fun("tbl_invoice_item",$where);
					/*******************************************/
					
					/*******************************************
					$where = array('vno'=>$vno,'vdt'=>$vdt);	$this->InvoiceModel->delete_fun("tbl_invoice_item_delete",$where);
					/*******************************************/
					
					$dt = array(
						'dispatchtime' => $dispatchtime,
						'vdt' => $vdt,
						'vno' => $vno,
						'vtype' => $vtype,
						'gstvno' => $gstvno,
						'pickedby' => $pickedby,
						'checkedby' => $checkedby,
						'deliverby' => $deliverby,
						'amt' => $amt,
						'taxamt' => $taxamt,
						'acno' => $acno,
						'chemist_id' => $chemist_id,
						'chemist_name' => $chemist_name,
						'chemist_email' => $chemist_email,
						'chemist_mobile' => $chemist_mobile,
						'date' => $date,
						'update_at' => $update_at,
						'status' => $status,
					);
					$this->InvoiceModel->insert_fun("tbl_invoice_new", $dt);
				}
			}
			$commaSeparatedString = implode(',', $id_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["code" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}
	
	public function upload_invoice_item()
	{
		$isdone = "";
		//$db_invoice = $this->load->database('default3', TRUE);
		
		$data = json_decode(file_get_contents('php://input'),true);
		// Data ko check karna
		if ($data && is_array($data)) {

			$id_array = array();
			foreach ($data as $record) {

				if (!empty($record["id"])) {
					$id_array[] 	= $record['id'];
					$id 			= $record["id"];
					$vno 			= $record["vno"];
					$vdt			= $record["vdt"];
					$psrlno			= $record["psrlno"];
					$itemc			= $record["itemc"];
					$batch			= $record["batch"];
					$qty			= $record["qty"];
					$fqty			= $record["fqty"];
					$ntrate			= $record["ntrate"];
					$ftrate			= $record["ftrate"];
					$dis			= $record["dis"];
					$disamt			= $record["disamt"];
					$netamt			= $record["netamt"];
					$halfp			= $record["halfp"];
					$mrp			= $record["mrp"];
					$hsncode		= $record["hsncode"];
					$expiry			= $record["expiry"];
					$scm1			= $record["scm1"];
					$scm2			= $record["scm2"];
					$scmper			= $record["scmper"];
					$localcent 		= $record["localcent"];
					$excise			= $record["excise"];
					$cgst			= $record["cgst"];
					$sgst			= $record["sgst"];
					$igst			= $record["igst"];
					$adnlvat		= $record["adnlvat"];
					$gdn			= $record["gdn"];
					$compcode		= $record["compcode"];
					$division		= $record["division"];
					$item_name		= $record["item_name"];
					$item_code		= $record["item_code"];
					$packing		= $record["packing"];
					$escode			= $record["escode"];
					$vtype			= $record["vtype"];
					$company_full_name= $record["company_full_name"];
					$image1			= $record["image1"];
					$update_at		= $record["update_at"];

					$dt = array(
						'vno' => $vno,
						'vdt' => $vdt,
						'psrlno' => $psrlno,
						'itemc' => $itemc,
						'batch' => $batch,
						'qty' => $qty,
						'fqty' => $fqty,
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
						'compcode' => $compcode,
						'division' => $division,
						'item_name' => $item_name,
						'item_code' => $item_code,
						'packing' => $packing,
						'escode' => $escode,
						'vtype' => $vtype,
						'company_full_name' => $company_full_name,
						'image1' => $image1,
						'update_at' => $update_at,);
						
					$this->InvoiceModel->insert_fun("tbl_invoice_item", $dt);
					
					$isdone = "yes";
				}
			}
			$commaSeparatedString = implode(',', $id_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["code" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}
	
	public function upload_invoice_item_delete()
	{
		$isdone = "";
		//$db_invoice = $this->load->database('default3', TRUE);
		
		$data = json_decode(file_get_contents('php://input'),true);
		// Data ko check karna
		if ($data && is_array($data)) {

			$id_array = array();
			foreach ($data as $record) {

				if (!empty($record["id"])) {
					$id_array[] 	= $record['id'];
					$id 			= $record["id"];
					$vdt 			= $record["vdt"];
					$vno 			= $record["vno"];
					$itemc 			= $record["itemc"];
					$item_name 		= $record["item_name"];
					$slcd 			= $record["slcd"];
					$amt 			= $record["amt"];
					$namt 			= $record["namt"];
					$remarks 		= $record["remarks"];
					$descp 			= $record["descp"];
					$item_remarks1 	= $record["item_remarks1"];
					$type 			= $record["type"];
					$update_at 		= $record["update_at"];
					
					$dt = array(
							'vdt' => $vdt,
							'vno' => $vno,
							'itemc' => $itemc,
							'item_name' => $item_name,
							'slcd' => $slcd,
							'amt' => $amt,
							'namt' => $namt,
							'remarks' => $remarks,
							'descp' => $descp,
							'item_remarks1' => $item_remarks1,
							'type' => $type,
							'update_at' => $update_at
						);
					$this->InvoiceModel->insert_fun("tbl_invoice_item_delete", $dt);
				}
			}
			$commaSeparatedString = implode(',', $id_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["code" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}
}