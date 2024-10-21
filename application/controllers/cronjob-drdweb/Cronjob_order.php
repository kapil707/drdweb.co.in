<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_order extends CI_Controller 
{
    public function __construct(){
		parent::__construct();
    }
	public function start_job()
	{
		$where = array('tbl_cart_order.status'=>0);
		$this->db->select('tbl_cart_order.*, tbl_chemist.name, tbl_chemist.email, tbl_chemist.mobile');
        $this->db->from('tbl_cart_order');
        $this->db->join('tbl_chemist', 'tbl_cart_order.chemist_id = tbl_chemist.altercode', 'left');
		$this->db->where($where);
        $this->db->limit(10);
        $query = $this->db->get()->result();
		foreach($query as $row)
		{
			$remarks 	= $row->remarks;
			$user_type 	= $row->user_type;
			$chemist_id = $row->chemist_id;
			$salesman_id= $row->salesman_id;
			$total_rs 	= $row->total;
            $total_rs = round($total_rs);

            /**************************************************** */
            $acm_altercode 	= $row->chemist_id;
            $acm_name		= ucwords(strtolower($row->name));
            $acm_email 		= $row->email;
            $acm_mobile 	= $row->mobile;	
            
            /*****************Excel file ke liya*****************************
            // yha code band kiya ha ku ki url diya ha file nahi bj rhay no need file ko download ka url bj rahy ha osi say sara kam ho raha ha	
            $file_name_1 = $this->Order_Model->excel_save_order_to_server($query,$chemist_excle,"cronjob_download");
            /****************************************************************/	
		
            /*****************whtsapp message*****************************/	            
            $whatsapp_email_how_to_use_dt = $this->whatsapp_email_how_to_use_dt($query);
            $whatsapp_table_formet_dt 	= $whatsapp_email_how_to_use_dt[0];
            $email_table_formet_dt 		= $whatsapp_email_how_to_use_dt[1];
            $html_table_formet_dt 		= $whatsapp_email_how_to_use_dt[2];
            
            if($remarks){
                $remarks = "<br>Remarks : ".$remarks;
            }

            $default_place_order_text = $this->Scheme_Model->get_website_data("default_place_order_text");

            $whatsapp_footer_text = $this->Scheme_Model->get_website_data("whatsapp_footer_text");
            $txt_msg = "Hello $acm_name ($acm_altercode)<br><br>".$default_place_order_text."<br><br>Order No. : $order_id<br>Total Rs. : $total_rs/- $remarks $whatsapp_table_formet_dt <br><br><b>You can check your order by clicking on </b><br><br>https://www.drdistributor.com/view_order/".$acm_altercode."/".$order_id." <br><br><b>You can download your order by clicking on</b> <br><br>https://www.drdistributor.com/order_download/".$acm_altercode."/".$order_id." ".$whatsapp_footer_text;

            $email_footer_text = $this->Scheme_Model->get_website_data("email_footer_text");
            $txt_msg_email = "Hello $acm_name ($acm_altercode)<br><br>".$default_place_order_text."<br><br>Order No. : $order_id<br>Total Rs. : $total_rs/- $remarks $email_table_formet_dt <br><br><b>You can check your order by clicking on </b><br><br>https://www.drdistributor.com/view_order/".$acm_altercode."/".$order_id." <br><br><b>You can download your order by clicking on</b> <br><br>https://www.drdistributor.com/order_download/".$acm_altercode."/".$order_id." ".$email_footer_text."<br><br>".$html_table_formet_dt;
			
            /************************************************/
            $q_altercode 	= $acm_altercode;
            $q_title 		= "New Order - $order_id";
            $q_message		= $txt_msg;
            $this->Message_Model->insert_android_notification("4",$q_title,$q_message,$q_altercode,"chemist");
            /************************************************/
            if(!empty($acm_mobile))
            {
                $w_number 		= "+91".$acm_mobile;
                $w_altercode 	= $acm_altercode;
                $w_message 		= $txt_msg;
                $this->Message_Model->insert_whatsapp_message($w_number,$w_message,$w_altercode);
            }
            else
            {
                $err = "Number not Available";
                $mobile = "";
                $this->Message_Model->tbl_whatsapp_email_fail($mobile,$err,$acm_altercode);
            }
            if($user_type=="sales")
            {
                $where = array('customer_code'=>$salesman_id);
                $users = $this->Scheme_Model->select_row("tbl_users",$where);
                $salesman_name 		= $users->firstname." ".$users->lastname;
                $salesman_mobile	= $users->cust_mobile;
                $salesman_altercode	= $users->customer_code;
                if($salesman_mobile!="")
                {
                    $w_number 		= "+91".$salesman_mobile;//$c_cust_mobile;
                    $w_altercode 	= $acm_altercode;
                    $w_message 		= "New Order Placed - $order_id for $acm_name for amount $total_rs";
                    $this->Message_Model->insert_whatsapp_message($w_number,$w_message,$w_altercode);
                }
            }
            /***************only for group message***********************/
            $txt_msg1  = str_replace("Hello","",$txt_msg);
            $group2_message 	= "New order recieved from ".$txt_msg1;
            $whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");
            $this->Message_Model->insert_whatsapp_group_message($whatsapp_group2,$group2_message);
            /*************************************************************/
            
            /******************group message******************************/
            $group1_message 	= "New Order Recieved from ".$txt_msg1."Please check in Easy Sol";
            $whatsapp_group1 = $this->Scheme_Model->get_website_data("whatsapp_group1");
            $this->Message_Model->insert_whatsapp_group_message($whatsapp_group1,$group1_message);
            /**********************************************************/
            
            $subject = "DRD Order || ($order_id) || $acm_name ($acm_altercode)";
            
            $message = "";
            if($user_type == "sales"){
                $message ="Salesman : ".$salesman_name." (".$salesman_altercode.")<br>";
            }		
            $message.=$txt_msg_email;
            
            $user_email_id = $acm_email;
            if (filter_var($user_email_id, FILTER_VALIDATE_EMAIL)) {
                //$user_email_id = "drdwebmail1@gmail.com";	
            }
            else{			
                $err = $user_email_id." is Wrong Email";
                $mobile = "";
                $this->Message_Model->tbl_whatsapp_email_fail($mobile,$err,$acm_altercode);
                $user_email_id = "kapildrd@gmail.com";			
            }
            if(!empty($user_email_id))
            {
                //$file_name1 = $order_id;
                $file_name_1 = $file_name1 = "";
                
                $subject = ($subject);
                $message = ($message);
                $email_function = "new_order";
                $mail_server = "";
                /************************************************/
                $row1 = $this->db->query("select * from tbl_email where email_function='$email_function'")->row();
                /***********************************************/
                $email_other_bcc = $row1->email;
                $date = date('Y-m-d');
                $time = date("H:i",time());
                $dt = array(
                    'user_email_id'=>$user_email_id,
                    'subject'=>$subject,
                    'message'=>$message,
                    'email_function'=>$email_function,
                    'file_name1'=>$file_name1,
                    'file_name_1'=>$file_name_1,
                    'mail_server'=>$mail_server,
                    'email_other_bcc'=>$email_other_bcc,
                    'date'=>$date,
                    'time'=>$time,
                );
                $this->Scheme_Model->insert_fun("tbl_email_send",$dt);				
            }
        }
	}
}