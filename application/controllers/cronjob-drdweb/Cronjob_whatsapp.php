<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_whatsapp extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		// Load model
		$this->load->model("model-drdweb/WhatsAppModel");
	}
	
	public function send_whatsapp()
	{
		$this->WhatsAppModel->send_whatsapp();
		$this->WhatsAppModel->send_whatsapp_group();
		echo "send Whatsapp message";
	}
}