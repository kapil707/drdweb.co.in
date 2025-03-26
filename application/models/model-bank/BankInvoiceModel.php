<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankInvoiceModel extends CI_Model  
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function get_invoice_find_user(){

		$this->invoice_find_in_total('0','T102','2663');
		die();

		$start_date = date('Y-m-d', strtotime('-2 day'));
		$end_date = date('Y-m-d');

		$working = 0;
		if($working == 0){
			$result = $this->BankModel->select_query("select id,from_text_find_chemist,amount from tbl_bank_processing where invoice_id='' and from_text_find_chemist!='' and date BETWEEN '$start_date' and '$end_date' ORDER BY RAND() limit 500");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->from_text_find_chemist;
				$amount 	= $row->amount;
				$working = $this->invoice_find($id,$chemist_id,$amount);
			}
		}

		if($working == 0){
			$result = $this->BankModel->select_query("select id,from_text_find_chemist,amount from tbl_bank_processing where invoice_id='' and from_text_find_chemist!='' and date BETWEEN '$start_date' and '$end_date' ORDER BY RAND() limit 500");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->from_text_find_chemist;
				$amount 	= $row->amount;
				$working = $this->invoice_find_in_total($id,$chemist_id,$amount);
			}
		}

		//find from recommended
		if($working == 0){
			$result = $this->BankModel->select_query("select id,whatsapp_recommended,amount from tbl_bank_processing where invoice_id='' and from_text_find_chemist!='' and whatsapp_recommended!='' and date BETWEEN '$start_date' and '$end_date' ORDER BY RAND() limit 100");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->whatsapp_recommended;
				$amount 	= $row->amount;
				$working 	= $this->recommended_invoice_find($id,$chemist_id,$amount);
			}
		}

		if($working == 0){
			$result = $this->BankModel->select_query("select id,whatsapp_recommended,amount from tbl_bank_processing where invoice_id='' and whatsapp_recommended!='' and date BETWEEN '$start_date' and '$end_date' ORDER BY RAND() limit 100");
			$result = $result->result();
			foreach($result as $row){
				$id 		= $row->id;
				$chemist_id = $row->whatsapp_recommended;
				$amount 	= $row->amount;
				$working 	= $this->recommended_invoice_find($id,$chemist_id,$amount);
			}
		}
	}

	public function invoice_find($id,$chemist_id,$amount){

		$status = 0;
		$start_date = date('Y-m-d', strtotime('-2 day'));
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
		$start_date = date('Y-m-d', strtotime('-2 day'));
		$end_date = date('Y-m-d');

		$resultArray = [];
		$result = $this->BankModel->select_query("SELECT * FROM `tbl_invoice` WHERE `chemist_id`='$chemist_id' and date BETWEEN '$start_date' and '$end_date'");
		$result = $result->result();
		foreach($result as $row) {
			$amount = str_replace(".00", "", $row->amt);
			$invoices[] = [
				'id' => $row->id,
				'chemist_id' => $row->chemist_id,
				'gstvno' => $row->gstvno,
				'amount' => $amount
			];		
		}

		$targetValue = $amount;
		$found = [];
		print_r($invoices);
		echo "<br>";
		
		if ($invoices[0]['amount'] + $invoices[1]['amount'] == $targetValue) {
			$found[] = [$invoices[0]['id'], $invoices[1]['id']];
		}
		
		if(emtpy($found)){
			$invoice_count = count($invoices);	
			// Check all combinations of 2 or 3 invoices
			for ($i = 0; $i < $invoice_count; $i++) {
				for ($j = $i + 1; $j < $invoice_count; $j++) {
					// Check sum of 2 invoices
					if ($invoices[$i]['amount'] + $invoices[$j]['amount'] == $targetValue) {
						$found[] = [$invoices[$i]['id'], $invoices[$j]['id']];
					}
			
					for ($k = $j + 1; $k < $invoice_count; $k++) {
						// Check sum of 3 invoices
						if ($invoices[$i]['amount'] + $invoices[$j]['amount'] + $invoices[$k]['amount'] == $targetValue) {
							$found[] = [$invoices[$i]['id'], $invoices[$j]['id'], $invoices[$k]['id']];
						}
					}
				}
			}
		}

		if (!empty($found)) {
			echo "Matching Invoices: \n";
			foreach ($found as $set) {
				echo implode(", ", $set) . "\n";
			}
		}

		$json_invoice_id = [];
		$json_invoice_text = [];
		/*if ($found) {
			for ($i = 0; $i < count($selectedValues[0]); $i++) {
				$rt = $selectedValues[0][$i];
				$json_invoice_id[] = $rt['id'];
				$json_invoice_text[] = $rt['gstvno']." Amount.".$rt['amount'];
			}
		}*/

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

	public function recommended_invoice_find($id,$chemist_id,$amount){
		
		$status = 0;

		$start_date = date('Y-m-d', strtotime('-3 day'));
		$end_date = date('Y-m-d');

		$result = $this->BankModel->select_query("SELECT id,gstvno FROM `tbl_invoice` WHERE `chemist_id` LIKE '$chemist_id' and REPLACE(TRIM(amt), '.00', '')='$amount' and date BETWEEN '$start_date' and '$end_date'");
		$result = $result->result();
		foreach($result as $row) {
			if($row->id){
				$status = 1;

				$invoice_id = $row->id;
				$invoice_recommended = $chemist_id;
				$invoice_text = $row->gstvno." Amount.".$amount;

				$where = array(
					'id' => $id,
				);
				$dt = array(
					'process_status'=>3,
					'invoice_id'=>$invoice_id,
					'invoice_text'=>$invoice_text,
					'invoice_recommended'=>$invoice_recommended,
				);
				print_r($dt);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}
		return $status;
	}
}	