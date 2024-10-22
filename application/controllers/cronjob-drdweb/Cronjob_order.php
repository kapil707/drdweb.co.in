<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_order extends CI_Controller 
{
    public function __construct(){
		parent::__construct();
    }
	public function run_job()
	{
		$where = array('tbl_cart_order.status'=>0);
		$this->db->select('tbl_cart_order.*, tbl_chemist.name, tbl_chemist.email, tbl_chemist.mobile');
        $this->db->from('tbl_cart_order');
        $this->db->join('tbl_chemist', 'tbl_cart_order.chemist_id = tbl_chemist.altercode', 'left');
		$this->db->where($where);
        $this->db->limit(25);
        $query = $this->db->get()->result();
		foreach($query as $row)
		{
            $order_id   = $row->id;
			$remarks 	= $row->remarks;
			$user_type 	= $row->user_type;
			$chemist_id = $row->chemist_id;
			$salesman_id= $row->salesman_id;
			$total_rs 	= $row->total;
            $total_rs = round($total_rs);

            $this->db->query("update tbl_cart_order set status=1 where id='$order_id'");

            /**************************************************** */
            $acm_name		= ucwords(strtolower($row->name));
            $acm_email 		= $row->email;
            $acm_mobile 	= $row->mobile;	
            
            /*****************Excel file ke liya*****************************
            // yha code band kiya ha ku ki url diya ha file nahi bj rhay no need file ko download ka url bj rahy ha osi say sara kam ho raha ha	
            $file_name_1 = $this->Order_Model->excel_save_order_to_server($query,$chemist_excle,"cronjob_download");
            /****************************************************************/	
		
            /*****************whtsapp message*****************************/	            
            $whatsapp_email_how_to_use_dt = "";
            $whatsapp_table_formet_dt 	= "";// $whatsapp_email_how_to_use_dt[0];
            $email_table_formet_dt 		= "";// $whatsapp_email_how_to_use_dt[1];
            $html_table_formet_dt 		= "";// $whatsapp_email_how_to_use_dt[2];
            
            if($remarks){
                $remarks = "<br>Remarks : ".$remarks;
            }

            $default_place_order_text = $this->Scheme_Model->get_website_data("default_place_order_text");

            $whatsapp_footer_text = $this->Scheme_Model->get_website_data("whatsapp_footer_text");

            $txt_msg = "Hello $acm_name ($chemist_id)<br><br>".$default_place_order_text."<br><br>Order No. : $order_id<br>Total Rs. : $total_rs/- $remarks $whatsapp_table_formet_dt <br><br><b>You can check your order by clicking on </b><br><br>https://www.drdistributor.com/view_order/".$chemist_id."/".$order_id." <br><br><b>You can download your order by clicking on</b> <br><br>https://www.drdistributor.com/order_download/".$chemist_id."/".$order_id." ".$whatsapp_footer_text;

            $email_footer_text = $this->Scheme_Model->get_website_data("email_footer_text");
            $txt_msg_email = "Hello $acm_name ($chemist_id)<br><br>".$default_place_order_text."<br><br>Order No. : $order_id<br>Total Rs. : $total_rs/- $remarks $email_table_formet_dt <br><br><b>You can check your order by clicking on </b><br><br>https://www.drdistributor.com/view_order/".$chemist_id."/".$order_id." <br><br><b>You can download your order by clicking on</b> <br><br>https://www.drdistributor.com/order_download/".$chemist_id."/".$order_id." ".$email_footer_text."<br><br>".$html_table_formet_dt;
			
            /************************************************/
            $q_title 		= "New Order - $order_id";
            $q_message		= $txt_msg;
            $this->Message_Model->insert_android_notification("4",$q_title,$q_message,$chemist_id,"chemist");
            /************************************************/
            if(!empty($acm_mobile))
            {
                $w_number 		= "+91".$acm_mobile;
                echo $w_message = $txt_msg;
                $this->Message_Model->insert_whatsapp_message($w_number,$w_message,$chemist_id);
            }
            else
            {
                $err = "Number not Available";
                $mobile = "";
                $this->Message_Model->tbl_whatsapp_email_fail($mobile,$err,$chemist_id);
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
                    $w_message 		= "New Order Placed - $order_id for $acm_name for amount $total_rs";
                    $this->Message_Model->insert_whatsapp_message($w_number,$w_message,$chemist_id);
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
            
            $subject = "DRD Order || ($order_id) || $acm_name ($chemist_id)";            
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
                    'update_at'=>'0',
                );
                $this->Scheme_Model->insert_fun("tbl_email_send",$dt);				
            }
        }
	}

	public function whatsapp_email_how_to_use_dt($order_id){
		
		$tbl = $tbl_w = $tbl_html = "";
		$i = $t = 0;
        
        $i++;
        $view = "";
        $result = $this->db->query("SELECT tbl_cart.i_code,tbl_cart.item_name FROM tbl_medicine_use left join tbl_cart on tbl_cart.i_code = tbl_medicine_use.item_code where order_id='$order_id'")->result();
        foreach($result as $row)
        {
            $t++;
            $item_code = $row->i_code;

            $view = "<b>How to use this medicine : </b><a href='https://www.drdistributor.com/medicine_use/".$item_code."'>View</a>";
            $tbl_w.= $t.". ".$row->item_name."<br>".base_url()."medicine_use/".$item_code."<br>";

            $tbl_html.= "<br><br><hr><h2><center>How to use this medicine :<b>".$row->item_name."</b></center></h2><br>";
            $php_files = glob('./medicine_use/'.$item_code.'/*');
            foreach($php_files as $file) {
                $file = str_replace("./","",$file);
                //$file = base_url().$file;
                
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if($ext=="jpg"){
                    $tbl_html.= "<img src='".base_url().$file."' width='250px' style='object-fit: contain;height: 200px;margin-right:15px;margin-bottom:15px;border-radius:10px;'>";
                }
                if($ext=="mp4"){
                    $tbl_html.= "<a href='".base_url().$file."'><img src='https://www.drdistributor.com/img_v51/default-video-thumbnail.jpg' width='250px' style='object-fit: contain;height: 200px;margin-right:15px;margin-bottom:15px;border-radius:10px;'></a>";
                }
            }
		}
			
		$tbl.= "<tr><td>".$i."</td><td>".$row->item_code."</td><td>".$row->item_name." ".$view."</td><td>".$row->quantity."</td><td>".$row->sale_rate."</td><td>".$row->sale_rate * $row->quantity."</td></tr>";
		
		
		$tbl = "<br><br><table width='100%' border='1'><tr><th>SrNo.</th><th>Item Code</th><th>Item Name</th><th>Item quantity</th><th>Price</th><th>Total</th></tr> ".$tbl."</table>";
		if($tbl_w){
			$tbl_w = "<br><br><b>How to use this medicine</b><br><br>".$tbl_w;
		}
		
		$x[0] = $tbl_w;
		$x[1] = $tbl;
		$x[2] = $tbl_html;
		
		//return $x;
        print_r($x);
	}
}