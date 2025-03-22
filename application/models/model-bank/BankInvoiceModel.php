<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankInvoiceModel extends CI_Model  
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function get_invoice_find_user(){
		$working = 0;
		if($working == 0){
			$result = $this->BankModel->select_query("select id,find_chemist,amount from tbl_bank_processing where invoice_id='' and find_chemist!='' ORDER BY RAND() limit 100");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->find_chemist;
				$amount 	= $row->amount;
				$working = $this->invoice_find($id,$chemist_id,$amount);
			}
		}

		if($working == 0){
			$result = $this->BankModel->select_query("select id,find_chemist,amount from tbl_bank_processing where invoice_id='' and find_chemist!='' ORDER BY RAND() limit 100");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->find_chemist;
				$amount 	= $row->amount;
				$working = $this->invoice_find_in_total($id,$chemist_id,$amount);
			}
		}

		if($working == 0){
			$result = $this->BankModel->select_query("select id,whatsapp_remanded,amount from tbl_bank_processing where invoice_id='' and find_chemist!='' and whatsapp_remanded!='' ORDER BY RAND() limit 100");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->whatsapp_remanded;
				$amount 	= $row->amount;
				$working 	= $this->remanded_invoice_find($id,$chemist_id,$amount);
			}
		}

		if($working == 0){
			$result = $this->BankModel->select_query("select id,whatsapp_remanded,amount from tbl_bank_processing where invoice_id='' and whatsapp_remanded!='' ORDER BY RAND() limit 100");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->whatsapp_remanded;
				$amount 	= $row->amount;
				$working 	= $this->remanded_invoice_find($id,$chemist_id,$amount);
			}
		}
	}

	public function invoice_find($id,$chemist_id,$amount){

		$status = 0;
		$start_date = date('Y-m-d', strtotime('-3 day'));
		$end_date = date('Y-m-d');

		$result = $this->BankModel->select_query("SELECT id,gstvno,amt FROM `tbl_invoice` WHERE `chemist_id` LIKE '$chemist_id' and REPLACE(TRIM(amt), '.00', '')='$amount' and date BETWEEN '$start_date' and '$end_date'");
		$result = $result->result();
		foreach($result as $row) {
			if($row->id){
				$status = 1;
				$amount = str_replace(".00", "", $row->amt);

				$invoice_id = $row->id;
				$invoice_chemist = $chemist_id;
				$invoice_text = $row->gstvno." Amount.".$amount;

				$where = array(
					'id' => $id,
				);
				$dt = array(
					'process_status'=>3,
					'invoice_id'=>$invoice_id,
					'invoice_chemist'=>$invoice_chemist,
					'invoice_text'=>$invoice_text,
				);
				print_r($dt);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}

		return $status;
	}

	public function invoice_find_in_total($id,$chemist_id,$amount){
		
		$status = 0;
		$start_date = date('Y-m-d', strtotime('-3 day'));
		$end_date = date('Y-m-d');

		$resultArray = [];
		$result = $this->BankModel->select_query("SELECT * FROM `tbl_invoice` WHERE `chemist_id`='$chemist_id' and date BETWEEN '$start_date' and '$end_date'");
		$result = $result->result();
		foreach($result as $row) {
			$amount = str_replace(".00", "", $row->amt);
			$resultArray[] = [
				'id' => $row->id,
				'chemist_id' => $row->chemist_id,
				'gstvno' => $row->gstvno,
				'amount' => $amount
			];		
		}

		$targetValue = $amount;
		$found = false;
		$selectedValues = [];

		for ($i = 0; $i < count($resultArray); $i++) {
			for ($j = $i + 1; $j < count($resultArray); $j++) {
				if ($resultArray[$i]['amount'] + $resultArray[$j]['amount'] == $targetValue) {
					$selectedValues[] = [$resultArray[$i], $resultArray[$j]];
					$found = true;
					break 2; // Exit both loops
				}
			}
		}

		$json_invoice_id = [];
		$json_invoice_text = [];
		if ($found) {
			for ($i = 0; $i < count($selectedValues[0]); $i++) {
				$rt = $selectedValues[0][$i];
				$json_invoice_id[] = $rt['id'];
				$json_invoice_text[] = $rt['gstvno']." Amount.".$rt['amount'];
			}
		}

		if(!empty($json_invoice_id)){

			$status = 1;
			$invoice_id = implode(',', $json_invoice_id);
			$invoice_text = implode('||', $json_invoice_text);
			$invoice_chemist = $chemist_id;

			$where = array(
				'id' => $id,
			);
			$dt = array(
				'process_status'=>3,
				'invoice_id'=>$invoice_id,
				'invoice_chemist'=>$invoice_chemist,
				'invoice_text'=>$invoice_text,
			);
			print_r($dt);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}

		return $status;
	}

	public function remanded_invoice_find($id,$chemist_id,$amount){
		
		$status = 0;

		$start_date = date('Y-m-d', strtotime('-3 day'));
		$end_date = date('Y-m-d');

		$result = $this->BankModel->select_query("SELECT id FROM `tbl_invoice` WHERE `chemist_id` LIKE '$chemist_id' and REPLACE(TRIM(amt), '.00', '')='$amount' and date BETWEEN '$start_date' and '$end_date'");
		$result = $result->result();
		foreach($result as $row) {
			if($row->id){
				$status = 1;

				$invoice_id = $row->id;
				$invoice_remanded = $chemist_id;

				$where = array(
					'id' => $id,
				);
				$dt = array(
					'process_status'=>3,
					'invoice_id'=>$invoice_id,
					'invoice_remanded'=>$invoice_remanded,
				);
				print_r($dt);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}
		return $status;
	}
}	