<?php 
header("Content-type: application/json; charset=utf-8");
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Api01 extends CI_Controller {	

	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankInvoiceModel");
		$this->load->model("model-bank/BankWhatsAppModel");
	}

	public function get_invoice_api(){
		$this->BankInvoiceModel->get_invoice_api();
	}

	public function get_whatsapp_api(){
		$this->BankWhatsAppModel->get_Whatsapp_api();
	}
}