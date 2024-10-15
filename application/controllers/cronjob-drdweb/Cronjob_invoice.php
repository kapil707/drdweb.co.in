<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_invoice extends CI_Controller 
{
	public function __construct(){

		parent::__construct();

		// Load model
		$this->load->model("model-drdweb/InvoiceModel");
	}
	
	public function invoice_send()
	{
		//$this->InvoiceModel->invoice_send_email_whatsapp();
		echo "Invoice Send Working";
	}
}