<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_medicine_report extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		// Load model
		$this->load->model('model/WhatsAppModel');
	}
	
	public function medicine_report()
	{
		$massage = "Report:-".date('d-M h:i A');

		$massage.= "\\n*Medicine duplicate report*";

		$i = 0;
		$result = $this->db->query("SELECT item_name, item_code, i_code FROM tbl_medicine GROUP BY item_name, item_code, i_code HAVING COUNT(*) >= 2;")->result();
		foreach($result as $row)
		{
			$i++;
			$massage.= "\\n$i :- ".$row->item_name." code(".$row->item_code.") -- id(".$row->i_code.")";
		}


		$massage1 = "\\n\\n*Medicine not added item code*";

		$i = 0;
		$result = $this->db->query("SELECT item_name,item_code,i_code FROM tbl_medicine where item_code=''")->result();
		foreach($result as $row)
		{
			$i++;
			$massage1.= "\\n$i :- ".$row->item_name." code(".$row->item_code.") -- id(".$row->i_code.")";
		}

		/***************only for group message***********************/
		$group2_message 	= $massage.$massage1;
		$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");
		$this->WhatsAppModel->insert_whatsapp_group_message($whatsapp_group2,$group2_message);
		/*************************************************************/
		
		/***************only for group message***********************/
		$group3_message 	= $massage.$massage1;
		$whatsapp_group3 = $this->Scheme_Model->get_website_data("whatsapp_group3");
		$this->WhatsAppModel->insert_whatsapp_group_message($whatsapp_group3,$group3_message);
		/*************************************************************/
		
		echo "Duplicate Medicine Report Working";
	}
}