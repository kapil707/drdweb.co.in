<?php 
header("Content-type: application/json; charset=utf-8");
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Api01 extends CI_Controller {	

	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
		$this->load->model("model-bank/BankWhatsAppModel");
	}

	public function get_Whatsapp_api(){
		//echo "get_Whatsapp_api";
		$this->BankWhatsAppModel->get_Whatsapp_api();
	}
}