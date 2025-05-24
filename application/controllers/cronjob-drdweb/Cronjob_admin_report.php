<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_admin_report extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		// Load model
		$this->load->model("model-drdweb/WhatsAppModel");
	}
	
	public function admin_report()
	{
		$massage = "Report:-".date('d-M h:i A');
		
		$massage1 = "<br>";		
		$massage1.= "<br>**************Main part**************";
		
		$result = $this->db->query("select count(id) as total from tbl_medicine")->row();
		$massage1.= "<br>Total Medicine :- ".$result->total;
		
		$result = $this->db->query("select count(id) as total from tbl_chemist where slcd='CL'")->row();
		$massage1.= "<br>Total Chemist :- ".$result->total;
		
		$result = $this->db->query("select count(id) as total from tbl_users")->row();
		$massage1.= "<br>Total Salesman :- ".$result->total;
		
		$result = $this->db->query("select count(id) as total from tbl_corporate")->row();
		$massage1.= "<br>Total Corporate :- ".$result->total;
		
		$result = $this->db->query("select count(id) as total from tbl_master where slcd='SM' and altercode!=''")->row();
		$massage1.= "<br>Total master :- ".$result->total;
		
		/************************************************/
		
		$date = date("Y-m-d");

		$row = $this->db->query("select count(id) as total, sum(amt) as total_amt, sum(taxamt) as total_taxamt from tbl_invoice where date='$date'")->row();
		if(!empty($row))
		{
			$today_total_taxamt = round($row->total_taxamt);
			$today_total_sales = round($row->total_amt);
			$today_invoice = $row->total;
		}
		
		setlocale(LC_MONETARY, 'en_IN');
		$today_total_sales = money_format('%!i', $today_total_sales - $today_total_taxamt);
		
		$today_total_sales = substr($today_total_sales, 0, -3);
		
		$massage2 = "<br>";
		$massage2.= "<br> **************Sales part**************";
		$massage2.= "<br>Total Invoice :- ".$today_invoice;
		$massage2.= "<br>Total Sale :- ".$today_total_sales;
		
		/***************************************************/
		
		$result = $this->db->query("select count(id) as total from tbl_cart_order where date='$date'")->row();
		$today_orders1 = $result->total;
		
		$result = $this->db->query("select count(id) as pending_order,sum(total) as pending_order_value from tbl_cart_order where download_status='0'")->row();
		$pending_order = $result->pending_order;
		$pending_order_value = $result->pending_order_value;

		setlocale(LC_MONETARY, 'en_IN');
		$pending_order_value = money_format('%!i', $pending_order_value);
		$pending_order_value = substr($pending_order_value, 0, -3);
		
		$massage3 = "<br>";
		$massage3.= "<br> **************Order part**************";
		$massage3.= "<br>Pending Order :- ".$pending_order;	
		$massage3.= "<br>Pending Order Value :- ".$pending_order_value;
		$massage3.= "<br>";	
		$massage3.= "<br>Today Total Order :- ".$today_orders1;
		
		$result = $this->db->query("select count(id) as total from tbl_cart_order where date='$date' ")->row();
		$massage3.= "<br>Unique Orders :- ".$result->total;
		
		$result = $this->db->query("select count(id) as total from tbl_cart_order where date='$date' and order_type='pc_mobile'")->row();
		$massage3.= "<br>Total Website Orders :- ".$result->total;
		
		$result = $this->db->query("select count(id) as total from tbl_cart_order where date='$date' and order_type='android'")->row();
		$massage3.= "<br>Total Android Orders :- ".$result->total;

		$massage4 = "<br>";
		$massage4.= "<br> **************Email part**************";
		$result = $this->db->query("SELECT * FROM `tbl_email`")->result();
		foreach ($result as $row){
			$server_email_name = $row->server_email_name;
			$email_function = $row->email_function;

			$row1 = $this->db->query("select count(id) as total from tbl_email_send where date='$date' and email_function='$email_function'")->row();

			$row2 = $this->db->query("select count(id) as total from tbl_email_send where date='$date' and email_function='$email_function' and status=1")->row();

			$massage4.= "<br>Total ".$server_email_name." :- ".$row1->total."/".$row2->total;
		}

		$result = $this->db->query("select sum(total) as today_orders_price,sum(items_total) as today_orders_items from tbl_cart_order where date='$date'")->row();
		$today_orders_price = $result->today_orders_price;
		$today_orders_items = $result->today_orders_items;
		
		setlocale(LC_MONETARY, 'en_IN');
		$today_orders_price = money_format('%!i', $today_orders_price);
		$today_orders_price = substr($today_orders_price, 0, -3);
		
		$massage3.= "<br>Total Order Value :- ".$today_orders_price;
		$massage3.= "<br>Total Order Item :- ".$today_orders_items;

		/***************only for group message***********************/
		$group2_message 	= $massage.$massage1.$massage2.$massage3.$massage4;
		$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");
		$this->WhatsAppModel->insert_whatsapp_group($whatsapp_group2,$group2_message,'','0');
		/*************************************************************/
		
		echo "Admin Report Working";
	}
}