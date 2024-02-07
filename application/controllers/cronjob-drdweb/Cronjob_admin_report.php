<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_admin_report extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		// Load model
		$this->load->model('model/WhatsAppModel');
		$this->load->model('model/NotificationModel');
	}
	
	public function insert_whatsapp_test_message()
	{
		$altercode = "v153";
		$whatsapp_message = "hello test message";
		$chemist_mobile = "+919530005050";
		$this->WhatsAppModel->insert_whatsapp($chemist_mobile,$whatsapp_message,$altercode);
	}
	
	public function all_message_send_by()
	{
		$this->WhatsAppModel->send_whatsapp_message();
		$this->WhatsAppModel->send_whatsapp_group_message();
		$this->NotificationModel->send_android_notification();
		echo "All Message Send By Working";
	}
	
	public function admin_report()
	{
		$massage = "Report:-".date('d-M h:i A');
		
		$massage1 = "\\n";		
		$massage1.= "\\n **************Main part**************";
		
		$result = $this->db->query("select count(id) as total from tbl_medicine")->row();
		$massage1.= "\\nTotal Medicine :- ".$result->total;
		
		/*****************************************************/
		$this->insert_meta_data("total_medicine",$result->total);
		/*****************************************************/
		
		$result = $this->db->query("select count(id) as total from tbl_acm where slcd='CL'")->row();
		$massage1.= "\\nTotal Chemist :- ".$result->total;
		
		/*****************************************************/
		$this->insert_meta_data("total_chemist",$result->total);
		/*****************************************************/
		
		$result = $this->db->query("select count(id) as total from tbl_users")->row();
		$massage1.= "\\nTotal Salesman :- ".$result->total;
		
		/*****************************************************/
		$this->insert_meta_data("total_salesman",$result->total);
		/*****************************************************/
		
		$result = $this->db->query("select count(id) as total from tbl_staffdetail")->row();
		$massage1.= "\\nTotal Corporate :- ".$result->total;
		
		/*****************************************************/
		$this->insert_meta_data("total_corporate",$result->total);
		/*****************************************************/
		
		$result = $this->db->query("select count(id) as total from tbl_master where slcd='SM' and altercode!=''")->row();
		$massage1.= "\\nTotal master :- ".$result->total;
		
		/*****************************************************/
		$this->insert_meta_data("total_master",$result->total);
		/*****************************************************/
		
		/************************************************/
		
		$date = date("Y-m-d");

		$db3 = $this->load->database('default3', TRUE);
		
		$db3->select('amt,taxamt');
		$db3->where('date',$date);
		$query = $db3->get("tbl_invoice_new")->result();
		$today_invoice = $today_total_sales = $today_total_taxamt = 0;
		foreach($query as $row)
		{
			$today_invoice++;
			$today_total_sales = $today_total_sales + round($row->amt);
			$today_total_taxamt = $today_total_taxamt + round($row->taxamt);
		}
		
		setlocale(LC_MONETARY, 'en_IN');
		$today_total_sales = money_format('%!i', $today_total_sales - $today_total_taxamt);
		
		$today_total_sales = substr($today_total_sales, 0, -3);
		
		$massage2 = "\\n";
		$massage2.= "\\n **************Sales part**************";
		$massage2.= "\\nTotal Invoice :- ".$today_invoice;
		$massage2.= "\\nTotal Sale :- ".$today_total_sales;
		
		/*****************************************************/
		$this->insert_meta_data("total_invoice",$today_invoice);
		$this->insert_meta_data("total_sale",$today_total_sales);
		/*****************************************************/
		
		/***************************************************/
		
		$result = $this->db->query("select count(DISTINCT order_id) as total from tbl_order where date='$date'")->row();
		$today_orders1 = $result->total;
		
		$result = $this->db->query("select count(DISTINCT order_id) as total from tbl_order where download_status='0'")->row();
		$today_orders2 = $result->total;

		$result = $this->db->query("select sum(quantity*sale_rate) as total from tbl_order where download_status='0'")->row();
		$today_orders2_val = $result->total;

		setlocale(LC_MONETARY, 'en_IN');
		$today_orders2_val = money_format('%!i', $today_orders2_val);
		$today_orders2_val = substr($today_orders2_val, 0, -3);
		
		$massage3 = "\\n";
		$massage3.= "\\n **************Order part**************";
		$massage3.= "\\nPending Order :- ".$today_orders2;	
		$massage3.= "\\nPending Order Value :- ".$today_orders2_val;
		$massage3.= "\\n";	
		$massage3.= "\\nToday Total Order :- ".$today_orders1;
		
		/*****************************************************/
		$this->insert_meta_data("pending_order",$today_orders2);
		$this->insert_meta_data("pending_order_value",$today_orders2_val);
		$this->insert_meta_data("pending_order_order",$today_orders1);
		/*****************************************************/
		
		$result = $this->db->query("select count(DISTINCT chemist_id) as total from tbl_order where date='$date' ")->row();
		$massage3.= "\\nUnique Orders :- ".$result->total;
		
		/*****************************************************/
		$this->insert_meta_data("unique_orders",$result->total);
		/*****************************************************/
		
		$result = $this->db->query("select count(DISTINCT order_id) as total from tbl_order where date='$date' and order_type='pc_mobile'")->row();
		$massage3.= "\\nTotal Website Orders :- ".$result->total;
		
		/*****************************************************/
		$this->insert_meta_data("total_website_orders",$result->total);
		/*****************************************************/
		
		$result = $this->db->query("select count(DISTINCT order_id) as total from tbl_order where date='$date' and order_type='android'")->row();
		$massage3.= "\\nTotal Android Orders :- ".$result->total;
		
		/*****************************************************/
		$this->insert_meta_data("total_android_orders",$result->total);
		/*****************************************************/


		$massage4 = "\\n";
		$massage4.= "\\n **************Email part**************";
		$result = $this->db->query("SELECT * FROM `tbl_email`")->result();
		foreach ($result as $row){
			$server_email_name = $row->server_email_name;
			$email_function = $row->email_function;

			$row1 = $this->db->query("select count(id) as total from tbl_email_send where date='$date' and email_function='$email_function'")->row();

			$row2 = $this->db->query("select count(id) as total from tbl_email_send where date='$date' and email_function='$email_function' and status=1")->row();

			$massage4.= "\\nTotal ".$server_email_name." :- ".$row1->total."/".$row2->total;
			
			/*****************************************************/
			$this->insert_meta_data($email_function,$row1->total."/".$row2->total);
			/*****************************************************/
		}

		
		$this->db->select('quantity,sale_rate');
		$this->db->where('date',$date);
		$query = $this->db->get("tbl_order")->result();
		$today_orders_items = $today_orders_price = 0;
		foreach($query as $row)
		{
			$today_orders_price = $today_orders_price + ($row->quantity * $row->sale_rate);
			$today_orders_items++;
		}
		setlocale(LC_MONETARY, 'en_IN');
		$today_orders_price = money_format('%!i', $today_orders_price);
		$today_orders_price = substr($today_orders_price, 0, -3);
		
		$massage3.= "\\nTotal Order Value :- ".$today_orders_price;
		$massage3.= "\\nTotal Order Item :- ".$today_orders_items;
		
		/*****************************************************/
		$this->insert_meta_data("total_order_value",$today_orders_price);
		$this->insert_meta_data("total_order_item",$today_orders_items);
		/*****************************************************/

		/***************only for group message***********************/
		$group2_message 	= $massage.$massage1.$massage2.$massage3.$massage4;
		$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");
		$this->WhatsAppModel->insert_whatsapp_group_message($whatsapp_group2,$group2_message);
		/*************************************************************/
		
		echo "Admin Report Working";
	}
	
	public function insert_meta_data($meta_type="",$meta_data=""){
		$datatime  = time();
		$date  = date("Y-m-d",$datatime);
		$time  = date("H:i",$datatime);
		$dt = array('meta_type'=>$meta_type,'meta_data'=>$meta_data,'date'=>$date,'time'=>$time,'datatime'=>$datatime,);
		$this->Scheme_Model->insert_fun("tbl_meta_data",$dt);
	}
}