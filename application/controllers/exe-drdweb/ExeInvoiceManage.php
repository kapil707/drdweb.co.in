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
		$items = $data["items"];
		foreach($items as $row)
		{
			if (!empty($row["id"])) {
				
				$id 			= $row["id"];
				$dispatchtime 	= $row["dispatchtime"];
				$vdt 			= $row["vdt"];
				$vno 			= $row["vno"];
				$vtype 			= $row["vtype"];
				$gstvno 		= $row["gstvno"];
				$pickedby 		= $row["pickedby"];
				$checkedby 		= $row["checkedby"];
				$deliverby 		= $row["deliverby"];
				$amt 			= $row["amt"];
				$taxamt 		= $row["taxamt"];
                $acno 			= $row["acno"];
				$chemist_id 	= $row["chemist_id"];
				$chemist_name 	= $row["chemist_name"];
				$chemist_email 	= $row["chemist_email"];
				$chemist_mobile = $row["chemist_mobile"];
				$date 			= $row["date"];
				$update_at 		= $row["update_at"];
				$status 		= $row["status"];
				
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
				$isdone = "yes";
			}
		}
		if ($isdone == "yes") {
			echo "done";
		}
	}
	
	public function upload_invoice_item()
	{
		$isdone = "";
		//$db_invoice = $this->load->database('default3', TRUE);
		
		$data = json_decode(file_get_contents('php://input'),true);
		$items = $data["items"];
		foreach($items as $row)
		{
			if (!empty($row["id"])) {
				
				$id 	= $row["id"];
				$vno 	= $row["vno"];
				$vdt	= $row["vdt"];
				$psrlno	= $row["psrlno"];
				$itemc	= $row["itemc"];
				$batch	= $row["batch"];
				$qty	= $row["qty"];
				$fqty	= $row["fqty"];
				$ntrate	= $row["ntrate"];
				$ftrate	= $row["ftrate"];
				$dis	= $row["dis"];
				$disamt	= $row["disamt"];
				$netamt	= $row["netamt"];
				$halfp	= $row["halfp"];
				$mrp	= $row["mrp"];
				$hsncode= $row["hsncode"];
				$expiry	= $row["expiry"];
				$scm1	= $row["scm1"];
				$scm2	= $row["scm2"];
				$scmper	= $row["scmper"];
				$localcent = $row["localcent"];
				$excise	= $row["excise"];
				$cgst	= $row["cgst"];
				$sgst	= $row["sgst"];
				$igst	= $row["igst"];
				$adnlvat= $row["adnlvat"];
				$gdn	= $row["gdn"];
				$compcode= $row["compcode"];
				$division= $row["division"];
				$item_name= $row["item_name"];
				$item_code= $row["item_code"];
				$packing= $row["packing"];
				$escode= $row["escode"];
				$vtype= $row["vtype"];
				$company_full_name= $row["company_full_name"];
				$update_at= $row["update_at"];

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
					'update_at' => $update_at,);
					
				$this->InvoiceModel->insert_fun("tbl_invoice_item", $dt);
				
				$isdone = "yes";
			}
		}
		if ($isdone == "yes") {
			echo "done";
		}
	}
	
	public function upload_invoice_item_delete()
	{
		$isdone = "";
		//$db_invoice = $this->load->database('default3', TRUE);
		
		$data = json_decode(file_get_contents('php://input'),true);
		$items = $data["items"];
		foreach($items as $row)
		{
			if (!empty($row["id"])) {
				
				$id 		= $row["id"];
				$vdt 		= $row["vdt"];
				$vno 		= $row["vno"];
				$itemc 		= $row["itemc"];
				$item_name 	= $row["item_name"];
				$slcd 		= $row["slcd"];
				$amt 		= $row["amt"];
				$namt 		= $row["namt"];
				$remarks 	= $row["remarks"];
				$descp 		= $row["descp"];
				$item_remarks1 = $row["item_remarks1"];
				$type 		= $row["type"];
				$update_at 	= $row["update_at"];
				
				
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
				
				$isdone = "yes";
			}
		}
		if ($isdone == "yes") {
			echo "done";
		}
	}
}