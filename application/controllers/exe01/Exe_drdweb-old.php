<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Exe_drdweb extends CI_Controller
{
	function new_clean($string)
	{
		$k = str_replace('\n', '<br>', $string);
		$k = preg_replace('/[^A-Za-z0-9\#]/', ' ', $k);
		return $k;
		//return preg_replace('/[^A-Za-z0-9\#]/', '', $string); // Removes special chars.
	}
	
	function remove_backslash($str)
	{
		$str = preg_replace('/\\\\/i', '', $str);
		$str = str_replace('/\/', '/', $str);
		$str = str_replace('\\', '/', $str);
		return $str;
	}
	
	public function insert_message_on_server()
	{
		error_reporting(0);
		/********************** */
		$isdone = "";
		$data = json_decode(file_get_contents('php://input'), true);
		$items = $data["items"];
		foreach ($items as $row) {
			if (!empty($row["mobile"]) && !empty($row["message"]) && $row["type_of_message"] == "whatsapp_message") {
				$mobile = $row["mobile"];
				$message = (base64_decode($row["message"]));
				$altercode = $row["altercode"];

				$this->Message_Model->insert_whatsapp_message($mobile, $message, $altercode);
				$isdone = "yes";
			}

			if (!empty($row["mobile"]) && !empty($row["message"]) && $row["type_of_message"] == "whatsapp_group") {
				$mobile = $row["mobile"];
				$message = (base64_decode($row["message"]));
				$altercode = "";

				$this->Message_Model->insert_whatsapp_group_message($mobile, $message);
				$isdone = "yes";
			}

			if (!empty($row["title"]) && !empty($row["message"]) && !empty($row["altercode"]) && !empty($row["funtype"]) && $row["type_of_message"] == "notification_message") {
				$title = $row["title"];
				$message = (base64_decode($row["message"]));
				$altercode = $row["altercode"];
				$funtype = $row["funtype"];

				$this->Message_Model->insert_android_notification($funtype, $title, $message, $altercode, "chemist");
				$isdone = "yes";
			}

			if (!empty($row["user_email_id"]) && !empty($row["subject"]) && !empty($row["message"]) && $row["type_of_message"] == "email_message") {
				$user_email_id = $row["user_email_id"];
				$subject = (base64_decode($row["subject"]));
				$message = (base64_decode($row["message"]));
				$file_name1 = $row["file_name1"];
				$file_name2 = $row["file_name2"];
				$file_name3 = $row["file_name3"];
				$file_name_1 = $row["file_name_1"];
				$file_name_2 = $row["file_name_2"];
				$file_name_3 = $row["file_name_3"];
				$mail_server = $row["mail_server"];
				$email_function  = $row["email_function"];
				$email_other_bcc = (base64_decode($row["email_other_bcc"]));

				$this->Message_Model->insert_email_message($user_email_id,$subject,$message,$file_name1,$file_name2,$file_name3,$file_name_1,$file_name_2,$file_name_3,$mail_server,$email_function,$email_other_bcc);
				$isdone = "yes";
			}
		}
		if ($isdone == "yes") {
			echo "done";
		}
	}

	public function download_order_again()
	{
		error_reporting(0);
		$data = json_decode(file_get_contents('php://input'), true);
		$items = $data["items"];
		foreach ($items as $row) {
			if (!empty($row["order_id"])) {
				echo $order_id = $row["order_id"];

				$this->db->query("update `tbl_order` set download_status=0 WHERE `order_id`='$order_id'");
			}
		}
		//$this->insert_message_on_server();
	}

	public function download_query_for_local_server()
	{
		$qry 	= "";
		$items 	= "";
		/*$result0 = $this->db->query("select DISTINCT(temp_rec) from tbl_order where download_status='0' GROUP by temp_rec limit $limit")->result();
		foreach ($result0 as $row0) {
			if (!empty($row0->temp_rec)) {
				$temp_rec = $row0->temp_rec;
				$new_temp_rec = time(); // yha temp rec nichay drd database ne temp rec banta ha

				$total_line = 0;
				$result = $this->db->query("select id,order_id,i_code,item_code,quantity,user_type,chemist_id,selesman_id,temp_rec,sale_rate,remarks,date,time from tbl_order where temp_rec='" . $temp_rec . "'")->result();
				foreach ($result as $row) {
					$total_line++;
					$chemist_id = $row->chemist_id;
				}

				$row2 = $this->db->query("SELECT code,slcd FROM `tbl_chemist` WHERE `altercode`='" . $chemist_id . "'")->row();
				if (!empty($row2->code)) {
					$acno = $row2->code;
					$slcd = $row2->slcd;
				}
				foreach ($result as $row) {
					$remarks = $this->new_clean(htmlentities($row->remarks));
					$remarks = base64_encode($remarks);

					$items .= '{"query_type":"order_download","online_id":"' . $row->id . '","order_id": "' . $row->order_id . '","i_code": "' . $row->i_code . '","item_code": "' . $row->item_code . '","quantity": "' . $row->quantity . '","user_type": "' . $row->user_type . '","chemist_id": "' . $row->chemist_id . '","salesman_id": "' . $row->selesman_id . '","acno": "' . $acno . '","slcd": "' . $slcd . '","sale_rate": "' . $row->sale_rate . '","remarks": "' . $remarks . '","date": "' . $row->date . '","time": "' . $row->time . '","total_line": "' . $total_line . '","temp_rec": "' . $row->temp_rec . '","new_temp_rec": "' . $new_temp_rec . '","order_status": "0"},';

					$qry .= "update tbl_order set download_status=1 where id='$row->id';";
				}
			}
		}*/
		
		if (empty($items)) {
			$result = $this->db->query("select * from tbl_company_discount where download_status=0 limit 100")->result();
			foreach ($result as $row) {

				$items .= '{"query_type":"company_discount","compcode":"'.$row->compcode.'","division":"'.$row->division.'","discount":"' . $row->discount.'","status":"'.$row->status.'"},';

				$qry .= "update tbl_company_discount set download_status=1 where id='$row->id';";
			}
		}
		
		if (empty($items)) {
			$result = $this->db->query("select * from tbl_medicine_image where download_status=0 limit 100")->result();
			foreach ($result as $row) {
				$description 	= htmlentities($row->description);
				$description 	= str_replace("'", "&prime;", $description);
				$description 	= base64_encode($description);
				$title 			= base64_encode($row->title);

				$items .= '{"query_type":"medicine_image","itemid":"'.$row->itemid.'","featured":"'.$row->featured.'","image":"'.$row->image.'","image2":"'.$row->image2.'","image3":"' . $row->image3.'","image4":"'.$row->image4.'","title":"'.$title.'","description":"'.$description.'","status":"'.$row->status.'","date":"'.$row->date.'","time":"'.$row->time.'"},';

				$qry .= "update tbl_medicine_image set download_status=1 where id='$row->id';";
			}
		}

		if (empty($items)) {
			$result = $this->db->query("select * from tbl_chemist_other where download_status=0 limit 100")->result();
			foreach ($result as $row) {

				$code 			= $row->code;
				$status 		= $row->status;
				$exp_date 		= $row->exp_date;
				$password 		= $row->password;
				$broadcast 		= $row->broadcast;
				$block 			= $row->block;
				$image 			= $row->image;
				$user_phone 	= $row->user_phone;
				$user_email 	= $row->user_email;
				$user_address 	= base64_encode($row->user_address);
				$user_update 	= $row->user_update;
				$order_limit 	= $row->order_limit;
				$new_request 	= $row->new_request;
				$website_limit 	= $row->website_limit;
				$android_limit 	= $row->android_limit;
	
				$items .= '{"query_type":"acm_other","code":"'.$code.'","status":"'.$status.'","exp_date":"'.$exp_date.'","password":"'.$password.'","broadcast":"'.$broadcast.'","block":"'.$block.'","image":"'.$image.'","user_phone":"'.$user_phone.'","user_email":"'.$user_email.'","user_address":"'.$user_address.'","user_update":"'.$user_update.'","order_limit":"'.$order_limit.'","new_request":"'.$new_request.'","website_limit":"'.$website_limit.'","android_limit":"'.$android_limit.'"},';
	
				$qry.= "update tbl_chemist_other set download_status=1 where id='$row->id';";				
			}
		}

		if (empty($items)) {
			$result = $this->db->query("select * from tbl_staffdetail_other where download_status=0 limit 200")->result();
			foreach ($result as $row) {

				$code 			= $row->code;
				$status 		= $row->status;
				$password 		= $row->password;
				$daily_date 	= $row->daily_date;
				$monthly 		= $row->monthly;
				$whatsapp_message = $row->whatsapp_message;
				$item_wise_report = $row->item_wise_report;
				$chemist_wise_report = $row->chemist_wise_report;
				$stock_and_sales_analysis = $row->stock_and_sales_analysis;
				$item_wise_report_daily_email = $row->item_wise_report_daily_email;
				$chemist_wise_report_daily_email = $row->chemist_wise_report_daily_email;
				$stock_and_sales_analysis_daily_email = $row->stock_and_sales_analysis_daily_email;
				$item_wise_report_monthly_email = $row->item_wise_report_monthly_email;
				$chemist_wise_report_monthly_email 		= $row->chemist_wise_report_monthly_email;
	
				$items .= '{"query_type":"staffdetail_other","code":"'.$code.'","status":"'.$status.'","password":"'.$password.'","daily_date":"'.$daily_date.'","monthly":"'.$monthly.'","whatsapp_message":"'.$whatsapp_message.'","item_wise_report":"'.$item_wise_report.'","chemist_wise_report":"'.$chemist_wise_report.'","stock_and_sales_analysis":"'.$stock_and_sales_analysis.'","item_wise_report_daily_email":"'.$item_wise_report_daily_email.'","chemist_wise_report_daily_email":"'.$chemist_wise_report_daily_email.'","stock_and_sales_analysis_daily_email":"'.$stock_and_sales_analysis_daily_email.'","website_limit":"'.$website_limit.'","item_wise_report_monthly_email":"'.$item_wise_report_monthly_email.'","chemist_wise_report_monthly_email":"'.$chemist_wise_report_monthly_email.'"},';
	
				$qry.= "update tbl_staffdetail_other set download_status=1 where id='$row->id';";				
			}
		}

		if (empty($items)) {
			$result = $this->db->query("select * from tbl_staffdetail_other where download_status=1 limit 4")->result();
			foreach ($result as $row) {

				$code 			= $row->code;
				$status2 		= "0";
	
				$items .= '{"query_type":"staffdetail_other_update","code":"'.$code.'","status2":"'.$status2.'"},';
	
				$qry.= "update tbl_staffdetail_other set download_status=2 where id='$row->id';";				
			}
		}

		if (!empty($items)) {

			if ($items != '') {
				$items = substr($items, 0, -1);
			}
			echo $parmiter = '{"items": [' . $items . ']}';

			$curl = curl_init();

			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL => 'http://61.246.17.250:7272/drd_local_server/exe01/download_query_for_local_server',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 0,
					CURLOPT_TIMEOUT => 600,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_POSTFIELDS => $parmiter,
					CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json',
					),
				)
			);

			$response = curl_exec($curl);
			curl_close($curl);
			echo $response;
			if ($response == "done") {
				$arr = explode(";", $qry);
				foreach ($arr as $row_q) {
					if ($row_q != "") {
						/*echo $row_q;
						echo "<br>";*/
						$this->db->query("$row_q");
					}
				}
			}
		}
	}
	
	public function order_error_download()
	{
		error_reporting(0);
		$items = "";
		$time = time();
		$day1 = date("Y-m-d", strtotime("-1 days", $time));
		$result = $this->db->query("select DISTINCT(order_id) from tbl_order where date>='$day1' GROUP by temp_rec")->result();
		foreach($result as $row){
			
			$items .= '{"order_id":"'.$row->order_id.'"},';
		}
		if(!empty($items)){
			if ($items != '') {
				$items = substr($items, 0, -1);
			}
			echo '[' . $items . ']';
		}
	}
	
	public function upload_order_to_gstvno()
	{
		error_reporting(0);
		$isdone="";
		$data  = json_decode(file_get_contents('php://input'), true);
		$items = $data["items"];
		foreach ($items as $row) {
			if (!empty($row["gstvno"]) && !empty($row["order_id"])) {
				$this->db->query("update tbl_order set gstvno='$gstvno' where order_id='$order_id'");
				$isdone="yes";
			}
		}		
		if($isdone=="yes")
		{
			echo "done";
		}
	}
	
	public function upload_medicine()
	{
		error_reporting(0);
		header("Content-type: application/json; charset=utf-8");
		$isdone="";
		$data = json_decode(file_get_contents('php://input'), true);
		$items = $data["items"];
		foreach ($items as $row) {
			if (!empty($row["i_code"])) {
				$i_code 	= $row["i_code"];
				$item_code 	= $row["item_code"];
				$item_name 	= $row["item_name"];
				$title 		= $row["title"];
				$packing 	= $row["packing"];
				
				$expiry 	= $row["expiry"];
				$batch_no 	= $row["batch_no"];
				$batchqty 	= $row["batchqty"];
				$salescm1 	= $row["salescm1"];
				$salescm2 	= $row["salescm2"];
				$sale_rate 	= $row["sale_rate"];
				$mrp 		= $row["mrp"];
				$final_price = $row["final_price"];
				$costrate 	= $row["costrate"];
				$margin 	= $row["margin"];
				$compcode 	= $row["compcode"];
				$comp_altercode = $row["comp_altercode"];
				$company_name 	= $row["company_name"];
				$company_full_name 	= $row["company_full_name"];
				$division 	= $row["division"];
				
				$qscm 		= $row["qscm"];
				$hscm 		= $row["hscm"];
				$misc_settings = $row["misc_settings"];
				$item_date 	= $row["item_date"];
				$itemcat 	= $row["itemcat"];
				$gstper 	= $row["gstper"];
				$itemjoinid = $row["itemjoinid"];
				$present 	= $row["present"];
				$featured 	= $row["featured"];
				if($featured=="0" || $featured==""){
					$featured = "0";
				}
				$discount 	= $row["discount"];
				
				$image1 	= $row["image1"];
				$image2 	= $row["image2"];
				$image3 	= $row["image3"];
				$image4 	= $row["image4"];
				$title2 	= $row["title2"];
				$description = $row["description"];
				$time 		= $row["time"];
				$status 	= $row["status"];
				if($status=="0" || $status==""){
					$status = "0";
				}
				
				$itemjoinid = base64_decode($itemjoinid);
				$title2 = base64_decode($title2);
				$description = base64_decode($description);
				
				$json_check = 0;
				
				$dt = array(
					'i_code'=>$i_code,
					'item_code'=>$item_code,
					'item_name'=>$item_name,
					'packing'=>$packing,
					'expiry'=>$expiry,
					'batch_no'=>$batch_no,
					'batchqty'=>$batchqty,
					'salescm1'=>$salescm1,
					'salescm2'=>$salescm2,
					'sale_rate'=>$sale_rate,
					'mrp'=>$mrp,
					'final_price'=>$final_price,
					'costrate'=>$costrate,
					'margin'=>$margin,
					'compcode'=>$compcode,
					'comp_altercode'=>$comp_altercode,
					'company_name'=>$company_name,
					'company_full_name'=>$company_full_name,
					'division'=>$division,
					'qscm'=>$qscm,
					'hscm'=>$hscm,
					'misc_settings'=>$misc_settings,
					'item_date'=>$item_date,
					'itemcat'=>$itemcat,
					'gstper'=>$gstper,
					'itemjoinid'=>$itemjoinid,
					'present'=>$present,
					'featured'=>$featured,
					'discount'=>$discount,
					'image1'=>$image1,
					'image2'=>$image2,
					'image3'=>$image3,
					'image4'=>$image4,
					'image4'=>$image4,
					'title2'=>$title2,
					'description'=>$description,
					'time'=>$time,
					'status'=>$status,
					'json_check'=>$json_check,
				);

				if (!empty($row["i_code"])) {
					$row1 = $this->db->query("select i_code from tbl_medicine_test where i_code='" . $i_code . "' order by id desc")->row();
					if (empty($row1->i_code)) {
						$this->Scheme_Model->insert_fun("tbl_medicine_test", $dt);
						$type = "insert";
					} else {
						$where = array('i_code'=>$i_code);
						$this->Scheme_Model->edit_fun("tbl_medicine_test", $dt, $where);
						$type = "update";
					}
					$isdone = "yes";
				}
			}
		}
		if($isdone=="yes")
		{
			$isdone = "done";
			echo '{"isdone":"'.$isdone.'","i_code":"'.$i_code.'","type":"'.$type.'"}';
		}
	}
	
	public function upload_chemist()
	{
		error_reporting(0);
		header("Content-type: application/json; charset=utf-8");
		$isdone="";
		$data = json_decode(file_get_contents('php://input'), true);
		$items = $data["items"];
		foreach ($items as $row) {
			if (!empty($row["code"])) {
				$code 		= $row["code"];
				$altercode 	= $row["altercode"];
				$groupcode 	= $row["groupcode"];
				$name 		= $row["name"];
				$type 		= $row["type"];
				
				$trimname 	= $row["trimname"];
				$address 	= $row["address"];
				$address1 	= $row["address1"];
				$address2 	= $row["address2"];
				$address3 	= $row["address3"];
				$telephone 	= $row["telephone"];
				$telephone1 = $row["telephone1"];
				$mobile 	= $row["mobile"];
				$email 		= $row["email"];
				$gstno 		= $row["gstno"];
				$status 	= $row["status"];
				$statecode 	= $row["statecode"];
				$invexport 	= $row["invexport"];
				$slcd 		= $row["slcd"];
								
				
				$sql = "insert into tbl_chemist_test (code,altercode,groupcode,name,type,trimname,address,address1,address2,address3,telephone,telephone1,mobile,email,gstno,status,statecode,invexport,slcd) values ('$code','$altercode','$groupcode','$name','$type','$trimname','$address','$address1','$address2','$address3','$telephone','$telephone1','$mobile','$email','$gstno','$status','$statecode','$invexport','$slcd')";
				$this->db->query("$sql");
				
				$isdone="yes";
			}
		}
		if($isdone=="yes")
		{
			$isdone = "done";
			echo '{"isdone":"'.$isdone.'","code":"'.$code.'"}';
		}
	}
	
	public function tttt(){
		error_reporting(0);
		$time = date("H:i",strtotime('-1 Min'));
		$date = date("Y-m-d");
		echo "select temp_rec from tbl_order where download_status='0' and time<'$time' order by id asc limit 1";
	}

	public function download_order_in_sever()
	{
		error_reporting(0);
		$items = $items_lines = "";
		$total_line = 0;
		$date = date("Y-m-d");
		$q = $this->db->query("select temp_rec,date from tbl_order where download_status='0' and date<'$date' order by id asc limit 1")->row(); 
		// yha ek din old order download karta ha 
		if (empty($q->temp_rec)) {
			$time = date("H:i",strtotime('-1 Min')); // taki same time pr order na utray
			$q = $this->db->query("select temp_rec from tbl_order where download_status='0' and time<'$time' order by id asc limit 1")->row(); 
			// yha same day ka order download karta ha 
		}

		//$q = $this->db->query("select temp_rec from tbl_order where temp_rec='313212_sales_RK1_V153' order by id asc limit 1")->row();
		if (!empty($q->temp_rec)) {
			$temp_rec = $q->temp_rec;

			$result = $this->db->query("select id,order_id,i_code,item_code,quantity,user_type,chemist_id,selesman_id,temp_rec,sale_rate,remarks,date,time from tbl_order where temp_rec='" . $temp_rec . "'")->result();
			foreach ($result as $row) {

				$total_line++;
				$items_lines .= '{"online_id":"'.$row->id.'","i_code":"'.$row->i_code.'","item_code":"'.$row->item_code.'","quantity":"'.$row->quantity.'","sale_rate":"'.$row->sale_rate.'"},';
				
				$order_id 		= $row->order_id;
				$user_type 		= $row->user_type;
				$chemist_id 	= $row->chemist_id;
				$salesman_id 	= $row->selesman_id;
				$remarks 		= $row->remarks;
				$date 			= $row->date;
				$time 			= $row->time;
			}

			$row1 = $this->db->query("SELECT code,slcd FROM `tbl_chemist` WHERE `altercode`='" . $chemist_id . "'")->row();
			if (!empty($row1->code)) {
				$acno = $row1->code;
				$slcd = $row1->slcd;
			}

			$new_temp_rec = time();
			$remarks = $this->new_clean(htmlentities($remarks));
			
			$items = '{"order_id":"'.$order_id.'","chemist_id":"'.$chemist_id.'","salesman_id":"'.$salesman_id.'","user_type":"'.$user_type.'","acno":"'.$acno.'","slcd":"'.$slcd.'","remarks":"'.$remarks.'","date":"'.$date.'","time":"'.$time.'","total_line":"'.$total_line.'","temp_rec":"'.$temp_rec.'","new_temp_rec":"'.$new_temp_rec.'","order_status":"0"}';
			
			if (!empty($items_lines)) {
				if ($items_lines != '') {
					$items_lines = substr($items_lines, 0, -1);
				}
				echo $parmiter = '{"items": [' . $items . '],"items_lines": [' . $items_lines . ']}';
				/*file_put_contents("json_order_download/" . $temp_rec . ".json", $parmiter);*/
			}
		}
	}
	
	public function download_order_in_sever_test($order_id)
	{
		error_reporting(0);
		$items = $items_lines = "";
		$total_line = 0;
		$q = $this->db->query("select temp_rec from tbl_order where order_id='$order_id' order by id asc limit 1")->row();

		//$q = $this->db->query("select temp_rec from tbl_order where temp_rec='313212_sales_RK1_V153' order by id asc limit 1")->row();
		if (!empty($q->temp_rec)) {
			$temp_rec = $q->temp_rec;

			$result = $this->db->query("select id,order_id,i_code,item_code,quantity,user_type,chemist_id,selesman_id,temp_rec,sale_rate,remarks,date,time from tbl_order where temp_rec='" . $temp_rec . "'")->result();
			foreach ($result as $row) {

				$total_line++;
				$items_lines .= '{"online_id":"'.$row->id.'","i_code":"'.$row->i_code.'","item_code":"'.$row->item_code.'","quantity":"'.$row->quantity.'","sale_rate":"'.$row->sale_rate.'"},';
				
				$order_id 		= $row->order_id;
				$user_type 		= $row->user_type;
				$chemist_id 	= $row->chemist_id;
				$salesman_id 	= $row->selesman_id;
				$remarks 		= $row->remarks;
				$date 			= $row->date;
				$time 			= $row->time;
			}

			$row1 = $this->db->query("SELECT code,slcd FROM `tbl_chemist` WHERE `altercode`='" . $chemist_id . "'")->row();
			if (!empty($row1->code)) {
				$acno = $row1->code;
				$slcd = $row1->slcd;
			}

			$new_temp_rec = time();
			$remarks = $this->new_clean(htmlentities($remarks));
			
			$items = '{"order_id":"'.$order_id.'","chemist_id":"'.$chemist_id.'","salesman_id":"'.$salesman_id.'","user_type":"'.$user_type.'","acno":"'.$acno.'","slcd":"'.$slcd.'","remarks":"'.$remarks.'","date":"'.$date.'","time":"'.$time.'","total_line":"'.$total_line.'","temp_rec":"'.$temp_rec.'","new_temp_rec":"'.$new_temp_rec.'","order_status":"0"}';
			
			if (!empty($items_lines)) {
				if ($items_lines != '') {
					$items_lines = substr($items_lines, 0, -1);
				}
				echo $parmiter = '{"items": [' . $items . '],"items_lines": [' . $items_lines . ']}';
				/*file_put_contents("json_order_download/" . $temp_rec . ".json", $parmiter);*/
			}
		}
	}

	public function download_order_status_update()
	{
		error_reporting(0);
		$isdone="";
		$data  = json_decode(file_get_contents('php://input'), true);
		$items = $data["items"];
		foreach ($items as $row) {
			if (!empty($row["order_id"])) {
				$order_id = $row["order_id"];
				$this->db->query("update tbl_order set download_status=1 where order_id='$order_id'");
			}
		}
	}
	
	/************************invoice upload********************************/
	public function upload_invoice_on_server()
	{
		error_reporting(0);
		$isdone = "";
		$db3 = $this->load->database('default3', TRUE);
		
		$data = json_decode(file_get_contents('php://input'),true);
		$items = $data["items"];
		foreach($items as $row)
		{
			if (!empty($row["id"])) {
				$id 		= $row["id"];
				$date 		= $row["date"];
				$acno 		= $row["acno"];
				$amt 		= $row["amt"];
				$taxamt 	= $row["taxamt"];
				$gstvno 	= $row["gstvno"];
				$name 		= base64_decode($row["name"]);
				$email 		= base64_decode($row["email"]);
				$altercode 	= $row["altercode"];
				$mobile 	= $row["mobile"];
				$delete_status = $row["delete_status"];
                $out_for_delivery = $row["out_for_delivery"];

				$row1 = $db3->query("select id from tbl_invoice where gstvno='$gstvno'")->row();
				if(empty($row1->id)){
					$db3->query("insert into tbl_invoice (acno,amt,taxamt,gstvno,name,email,altercode,date,delete_status,out_for_delivery) values ('$acno','$amt','$taxamt','$gstvno','$name','$email','$altercode','$date','$delete_status','$out_for_delivery')");
				}else{
					$db3->query("update tbl_invoice set acno='$acno',amt='$amt',taxamt='$taxamt',gstvno='$gstvno',name='$name',email='$email',altercode='$altercode',date='$date',delete_status='$delete_status',out_for_delivery='$out_for_delivery' where gstvno='$gstvno'");
				}
				$isdone = "yes";
			}
		}
		if ($isdone == "yes") {
			echo "done";
		}
	}

	public function upload_delivery()
	{
		error_reporting(0);
		//error_reporting(0);
		$data = json_decode(file_get_contents('php://input'),true);
		$items = $data["items"];
		foreach($items as $ks)
		{
			$qry = base64_decode($ks["qry"]);
			if($qry!="")
			{
				$db_master = $this->load->database('db_master', TRUE);
				$db_master->query("insert into tbl_delivery (gstvno,vdt,deliverby,vno,acno,chemist_id,user_altercode,amt) values ".$qry);
			}
			$qry = ($ks["qry2"]);
			if($qry!="")
			{
				$db3 = $this->load->database('default3', TRUE);
				$db3->query($qry);
			}
			echo $ks["_id"];
		}
	}
	
	// upload new delivery 2023-12-09
	public function upload_delivery_on_server()
	{
		error_reporting(0);
		$isdone = "";
		$db_master = $this->load->database('db_master', TRUE);
		
		$data = json_decode(file_get_contents('php://input'),true);
		$items = $data["items"];
		foreach($items as $row)
		{
			if (!empty($row["id"])) {
				$id 			= $row["id"];
				$dispatchtime 	= $row["dispatchtime"];
				$tagno 			= $row["tagno"];
				$mtime 			= $row["mtime"];
				$vdt 			= $row["vdt"];
				$vno 			= $row["vno"];
				$pickedby 		= $row["pickedby"];
				$checkedby 		= $row["checkedby"];
				$deliverby 		= $row["deliverby"];
				$vtype 			= $row["vtype"];
                $gstvno 		= $row["gstvno"];
				$altercode 		= $row["altercode"];
				$name 			= $row["name"];
				$mobile 		= $row["mobile"];
				$acno 			= $row["acno"];
				$amt 			= $row["amt"];
				$deliverby_altercode = $row["deliverby_altercode"];
				$items 			= $row["items"];

				$db_master->query("insert into drd_master_tbl_delivery (dispatchtime,tagno,mtime,vdt,vno,pickedby,checkedby,deliverby,vtype,gstvno,altercode,name,mobile,acno,amt,deliverby_altercode,items) values ('$dispatchtime','$tagno','$mtime','$vdt','$vno','$pickedby','$checkedby','$deliverby','$vtype','$gstvno','$altercode','$name','$mobile','$acno','$amt','$deliverby_altercode','$items')");
				$isdone = "yes";
			}
		}
		if ($isdone == "yes") {
			echo "done";
		}
	}
}