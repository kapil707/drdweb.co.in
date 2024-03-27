<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_Stock_Different extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		// Load model
		$this->load->model('model-drdweb/StockDifferentModel');
		$this->load->model('model/WhatsAppModel');
	}
	
	public function copy_records()
	{
		$this->StockDifferentModel->copy_records();
	}
	
	public function check_different_records()
	{
		$this->StockDifferentModel->check_different_qty();
		$this->StockDifferentModel->check_different_mrp();
		$this->StockDifferentModel->check_different_scheme();
		$this->send_report_on_whatsapp();
		echo "Check Different Records Working";
	}
	
	public function send_report_on_whatsapp()
	{
		$message = "Report:-".date('d-M h:i A');
		
		$message.= "\\n\\nItems Now available\\n\\n";
		$message.= $this->StockDifferentModel->send_report_on_whatsapp();
		
		/***************only for group message***********************/
		$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");		
		$this->WhatsAppModel->insert_whatsapp_group_message($whatsapp_group2,$message);
		/*************************************************************/
		
		/***************only for group message***********************/
		$whatsapp_group3 = $this->Scheme_Model->get_website_data("whatsapp_group3");		
		$this->WhatsAppModel->insert_whatsapp_group_message($whatsapp_group3,$message);
		/*************************************************************/
	}
}