<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_Stock_Low extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		// Load model
		$this->load->model('model-drdweb/StockLowModel');
		$this->load->model('model/WhatsAppModel');
	}
	
	public function send_stock_low_report()
	{
		$message = "Report:-".date('d-M h:i A');
		
		$message.= "\\n\\nShortage Depth Report\\n\\n";
		$message.= $this->StockLowModel->get_stock_low_report();
		
		/***************only for group message***********************/
		$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");		
		$this->WhatsAppModel->insert_whatsapp_group_message($whatsapp_group2,$message);
		/*************************************************************/
		
		/***************only for group message************************/
		$whatsapp_group3 = $this->Scheme_Model->get_website_data("whatsapp_group3");		
		$this->WhatsAppModel->insert_whatsapp_group_message($whatsapp_group3,$message);		/*************************************************************/
		
		echo "Send Report On Whatsapp Working";
	}
	
	public function send_stock_low_now_available_report()
	{
		$message = "Report:-".date('d-M h:i A');
		
		$message.= "\\n\\nShortage Now Available\\n\\n";
		$message.= $this->StockLowModel->get_stock_low_now_available_report();
		
		/***************only for group message***********************/
		$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");		
		$this->WhatsAppModel->insert_whatsapp_group_message($whatsapp_group2,$message);
		/*************************************************************/
		
		/***************only for group message************************
		$whatsapp_group3 = $this->Scheme_Model->get_website_data("whatsapp_group3");		
		$this->WhatsAppModel->insert_whatsapp_group_message($whatsapp_group3,$message);		/*************************************************************/
		
		echo "Stock Low Now Available Report Working";
	}
}