<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CronjobBank extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-drdweb/BankWhatsAppModel");		
	}

	public function get_whatsapp_or_insert(){
		echo "get_whatsapp_or_insert";
		$this->BankWhatsAppModel->get_whatsapp_or_insert();
	}
}