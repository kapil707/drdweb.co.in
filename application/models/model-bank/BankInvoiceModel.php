<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankInvoiceModel extends CI_Model  
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function get_invoice_find_user(){
		$result = $this->BankModel->select_query("select find_chemist,amount from tbl_bank_processing where invoice_id='' and find_chemist!='' ORDER BY RAND() limit 100");
		$result = $result->result();
		foreach($result as $row){
			$chemist_id = $row->find_chemist;
			$amount 	= $row->amount;
			$this->invoice_find($chemist_id,$amount);
		}
	}

	public function invoice_find($chemist_id,$amount){

		$start_date = date('Y-m-d', strtotime('-3 day'));
		$end_date = date('Y-m-d');

		$result = $this->BankModel->select_query("SELECT * FROM `tbl_invoice-bk` WHERE `chemist_id` LIKE '$chemist_id' and  REPLACE(TRIM(amt), '.00', '')='$amount' and date BETWEEN '$start_date' and '$end_date' ORDER BY RAND() limit 100");
		$result = $result->result();
		foreach($result as $row) {
			if($row->id){
				echo $row->id;
			}
		}
	}
}	