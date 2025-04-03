<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_bank_whatsapp extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-drdweb/BankWhatsAppModel");		
	}

	public function get_bank_whatsapp_or_insert(){
		echo "get_bank_whatsapp_or_insert";
		$this->BankWhatsAppModel->get_bank_whatsapp_or_insert();
	}
}