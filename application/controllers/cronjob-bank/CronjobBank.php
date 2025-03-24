<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CronjobBank extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
		$this->load->model("model-bank/BankSMSModel");
		$this->load->model("model-bank/BankStatmentModel");
		$this->load->model("model-bank/BankProcessingModel");
		$this->load->model("model-bank/BankWhatsAppModel");
		$this->load->model("model-bank/BankInvoiceModel");		
	}

	public function get_whatsapp_or_insert(){
		echo "get_whatsapp_or_insert";
		$this->BankWhatsAppModel->get_whatsapp_or_insert();
	}

	public function whatsapp_find_upi_amount(){
		echo "whatsapp_find_upi_amount";
		$this->BankWhatsAppModel->whatsapp_find_upi_amount();
	}

	public function whatsapp_update_upi(){
		echo "whatsapp_update_upi";
		$this->BankWhatsAppModel->whatsapp_update_upi();
	}

	public function whatsapp_update_reply_message(){
		echo "whatsapp_update_reply_message";
		$this->BankWhatsAppModel->whatsapp_update_reply_message();
	}

	public function get_invoice_find_user(){
		echo "get_invoice_find_user";
		$this->BankInvoiceModel->get_invoice_find_user();
	}

	public function testing(){
		$text = "+91-9899762072 507920298106 FROM MEHAK MEDICOS AND DEPARTMENTAL STORE 9300966180 CITI0000 9052 TRANS FER TO DR KARB0000547";
		preg_match("/FROM\s+(.+?)\s+CITI/", $text, $matches);
		if (!empty($matches) && empty($received_from)){
			echo $received_from = trim($matches[1]);
			//$from_value = "<b>find2: ".$received_from."</b>"; UPI CREDIT REFERENCE 956425755787 FROM APMAURYA6@I BL ARJUN PRASAD MAURYA PAYMENT FROM PHONEPE
			$statment_type = 2;
			echo "<br>2</br>";
		}
	}

	public function bank_main(){
		//$this->get_invoice();
		$check_sms = $this->BankModel->select_row("tbl_sms", array('status' => 0));
		if (!empty($check_sms)) {
			$this->BankSMSModel->get_sms();
		}else{
			$check_statment = $this->BankModel->select_row("tbl_statment", array('status' => 0));
			if (!empty($check_statment)) {
				$this->BankStatmentModel->get_statment();
			}else{
				$check_processing = $this->BankModel->select_row("tbl_bank_processing", array('process_status'=>0));
				if (!empty($check_processing)) {
					$this->BankProcessingModel->get_processing();
				}else{
					//$this->BankWhatsAppModel->whatsapp_find_upi_amount();
					//yha whatsapp message ko insert karwata ha processing me
					$this->BankWhatsAppModel->whatsapp_insert_in_processing();
				}
			}
		}
	}	
	
	public function get_invoice(){
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='1' limit 1");
		$result = $result->result();
		foreach($result as $row){
			
			$amount 		= $row->amount;
			$date 			= $row->date;

			$start_date = date('Y-m-d', strtotime($date . ' -2 day'));
			$end_date = date('Y-m-d', strtotime($date));
			
			$chemist = str_replace("/",",",$row->chemist);

			/************************************************* */
			$result = $this->find_by_invoice($amount,$start_date,$end_date,$chemist);
			$invoice = $result["find_invoice_chemist_id"];
		
			if(!empty($chemist)){
				if(empty($invoice)){
					$result = $this->find_by_invoice_amount($amount,$start_date,$end_date,$chemist);
					$invoice = $result["find_invoice_chemist_id"];
				}
			}

			/************************************************* */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>2,				
				'invoice'=>$invoice,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			/************************************************* */
		}
	}
	
	public function get_whatsapp(){
		echo " get_whatsapp ";

		$result = $this->BankModel->select_query("SELECT id,upi_no,amount,orderid from tbl_bank_processing where process_status=1 limit 25");
		$result = $result->result();
		foreach($result as $row) {

			$upi_no = trim($row->upi_no);
			$orderid= trim($row->orderid);
			$amount = $row->amount;

			$row1 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE REPLACE(`vision_text`, ' ', '') LIKE '%$upi_no%' or REPLACE(`vision_text`, ' ', '') LIKE '%$orderid%'");
			$row1 = $row1->row();
			if(empty($row1)){
				$row1 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE `vision_text` LIKE '%$upi_no%' or `vision_text` LIKE '%$orderid%'");
				$row1 = $row1->row();
			}

			if(empty($row1)){
				$last_four_digits = substr($upi_no, -4);
				$row1 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE (REPLACE(`vision_text`, ' ', '') LIKE '%XX$last_four_digits%' and REPLACE(`vision_text`, ',', '') LIKE '%$amount%')");
				$row1 = $row1->row();
			}
			
			$whatsapp_id = 0;
			$whatsapp_body = $whatsapp_image = $whatsapp_body2 = "";
			if(!empty($row1)){
				$whatsapp_id = $row1->id;
				$whatsapp_body = $row1->body;
				$whatsapp_image = $row1->screenshot_image;
				$whatsapp_body2 = $row1->vision_text;

				$from_number = $row1->from_number;
				$timestamp = date('Y-m-d H:i:s', $row1->timestamp);

				if(empty($whatsapp_body)){
					$row2 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body!='' LIMIT 0, 25");
					$row2 = $row2->row();
					$whatsapp_body = $row2->body;
				}
			}

			if(empty($row1)){
				$row2 = $this->BankModel->select_query("SELECT * FROM `tbl_whatsapp_message` WHERE REPLACE(`body`, ' ', '') LIKE '%$upi_no%'");
				$row2 = $row2->row();

				if(!empty($row2)){
					$whatsapp_id = $row2->id;
					$whatsapp_body = "";
					$whatsapp_image = $row2->screenshot_image;
					$whatsapp_body2 = $row2->body;

					$from_number = $row2->from_number;
					$timestamp = date('Y-m-d H:i:s', $row2->timestamp);

					if(empty($whatsapp_body)){
						$row2 = $this->BankModel->select_query("SELECT body FROM `tbl_whatsapp_message` WHERE from_number='$from_number' AND FROM_UNIXTIME(timestamp) BETWEEN DATE_SUB('$timestamp', INTERVAL 7 MINUTE) AND DATE_ADD('$timestamp', INTERVAL 7 MINUTE) and body!='' and id!='$whatsapp_id' LIMIT 0, 25");
						$row2 = $row2->row();
						$whatsapp_body = $row2->body;
					}
				}
			}

			$whatsapp_body  = str_replace(',', '', $whatsapp_body);
			$whatsapp_body2 = str_replace(',', '', $whatsapp_body2);
			echo "<br>";

			$where = array(
				'id' => $row->id,
			);
			$dt = array(
				'process_status'=>2,
				'whatsapp_message_id'=>$whatsapp_id,
				'find_whatsapp_chemist'=>$whatsapp_id,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}

	public function bank_processing_done(){
	
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where status='1' and done_status=0 limit 1");
		$result = $result->result();
		foreach($result as $row){
			$find_chemist_id2 = "";
			$find_chemist_id_array = explode(",", $row->find_chemist_id);
			$find_chemist_id_array = array_unique($find_chemist_id_array);
			foreach($find_chemist_id_array as $rows){
				$find_chemist_id2 = $rows;
			}

			$find_invoice_chemist_id2 = "";
			$find_invoice_chemist_id_array = explode(",", $row->find_invoice_chemist_id);
			foreach($find_invoice_chemist_id_array as $rows){
				$arr = explode(":-",$rows);
				$find_invoice_chemist_id2 = $arr[0];
			}
			
			$done_status = "2";
			$done_chemist_id = "";
			if((strtolower($find_chemist_id2)==strtolower($find_invoice_chemist_id2)) && (!empty($find_invoice_chemist_id2) && !empty($find_chemist_id2))){
				$done_status = "1";
				$done_chemist_id = $find_chemist_id2;
			}

			$where = array(
				'id' => $row->id,
			);
			$dt = array(
				'done_chemist_id'=>$done_chemist_id,
				'done_status'=>$done_status,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
		}
	}
}