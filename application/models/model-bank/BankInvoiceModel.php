<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankInvoiceModel extends CI_Model  
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function get_invoice_find_user(){
		$result = $this->BankModel->select_query("select id,find_chemist,amount from tbl_bank_processing where invoice_id='' and find_chemist!='' ORDER BY RAND() limit 100");
		$result = $result->result();
		foreach($result as $row){
			$id 		= $row->id;
			$chemist_id = $row->find_chemist;
			$amount 	= $row->amount;
			$this->invoice_find($id,$chemist_id,$amount);
		}
	}

	public function invoice_find($id,$chemist_id,$amount){

		$start_date = date('Y-m-d', strtotime('-3 day'));
		$end_date = date('Y-m-d');

		$result = $this->BankModel->select_query("SELECT id FROM `tbl_invoice-bk` WHERE `chemist_id` LIKE '$chemist_id' and  REPLACE(TRIM(amt), '.00', '')='$amount' and date BETWEEN '$start_date' and '$end_date'");
		$result = $result->result();
		foreach($result as $row) {
			if($row->id){
				$invoice_id = $row->id;
				$invoice_chemist = $chemist_id;

				$where = array(
					'id' => $id,
				);
				$dt = array(
					'process_status'=>3,
					'invoice_id'=>$invoice_id,
					'invoice_chemist'=>$invoice_chemist,
				);
				print_r($dt);
				$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			}
		}
	}
}	