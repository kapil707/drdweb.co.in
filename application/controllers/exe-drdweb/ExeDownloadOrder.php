<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeDownloadOrder extends CI_Controller
{
	public function download_order_test($order_id)
	{
		$jsonArray_lines = array();
		$jsonArray = array();

		$total_line = 0;
		$date = date("Y-m-d");
		$q = $this->db->query("select temp_rec,date from tbl_order where order_id='$order_id' order by id asc limit 1")->row(); 
		// $q = $this->db->query("select temp_rec,date from tbl_order where download_status='0' and date<'$date' order by id asc limit 1")->row(); 
		// // yha ek din old order download karta ha 
		// if (empty($q->temp_rec)) {
		// 	$time = date("H:i",strtotime('-1 Min')); 
		// 	// taki same time pr order na utray
		// 	$q = $this->db->query("select temp_rec from tbl_order where download_status='0' and time<'$time' order by id asc limit 1")->row(); 
		// 	// yha same day ka order download karta ha 
		// }
		if (!empty($q->temp_rec)) {
			$temp_rec = $q->temp_rec;

			$result = $this->db->query("select id,order_id,i_code,item_code,quantity,user_type,chemist_id,selesman_id,temp_rec,sale_rate,remarks,date,time from tbl_order where temp_rec='" . $temp_rec . "'")->result();
			foreach ($result as $row) {

				$total_line++;

				$dt = array(
					'total_line' => $total_line,
					'online_id' => $row->id,
					'i_code' => $row->i_code,
					'item_code' => $row->item_code,
					'quantity' => $row->quantity,
					'sale_rate' => $row->sale_rate,
				);
				$jsonArray_lines[] = $dt;
				
				$order_id 		= $row->order_id;
				$user_type 		= $row->user_type;
				$chemist_id 	= $row->chemist_id;
				$salesman_id 	= $row->selesman_id;
				$remarks 		= $row->remarks;
				$date 			= $row->date;
				$time 			= $row->time;
			}

			$row1 = $this->db->query("SELECT code,slcd FROM `tbl_acm` WHERE `altercode`='" . $chemist_id . "'")->row();
			if (!empty($row1->code)) {
				$acno = $row1->code;
				$slcd = $row1->slcd;
			}

			$new_temp_rec = time();
			//$remarks = $this->new_clean(htmlentities($remarks));

			$dt = array(
				'order_id' => $order_id,
				'chemist_id' => $chemist_id,
				'salesman_id' => $salesman_id,
				'user_type' => $user_type,
				'acno' => $acno,
				'slcd' => $slcd,
				'remarks' => $remarks,
				'date' => $date,
				'time' => $time,
				'total_line' => $total_line,
				'temp_rec' => $temp_rec,
				'new_temp_rec' => $new_temp_rec,
				'order_status' => "0",
			);
			$jsonArray[] = $dt;

			$items = $jsonArray;
			$items_other = $jsonArray_lines;

			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $items,
				'items_other' => $items_other,
			);
	
			// Send JSON response
			header('Content-Type: application/json');
			echo json_encode($response);
		}
	}

	//download_order_in_sever
	public function download_order()
	{
		$jsonArray_lines = array();
		$jsonArray = array();

		$total_line = 0;
		$date = date("Y-m-d");
		$q = $this->db->query("select temp_rec,date from tbl_order where download_status='0' and date<'$date' order by id asc limit 1")->row(); 
		// yha ek din old order download karta ha 
		if (empty($q->temp_rec)) {
			$time = date("H:i",strtotime('-1 Min')); 
			// taki same time pr order na utray
			$q = $this->db->query("select temp_rec from tbl_order where download_status='0' and time<'$time' order by id asc limit 1")->row(); 
			// yha same day ka order download karta ha 
		}
		if (!empty($q->temp_rec)) {
			$temp_rec = $q->temp_rec;

			$result = $this->db->query("select id,order_id,i_code,item_code,quantity,user_type,chemist_id,selesman_id,temp_rec,sale_rate,remarks,date,time from tbl_order where temp_rec='" . $temp_rec . "'")->result();
			foreach ($result as $row) {

				$total_line++;

				$dt = array(
					'total_line' => $total_line,
					'online_id' => $row->id,
					'i_code' => $row->i_code,
					'item_code' => $row->item_code,
					'quantity' => $row->quantity,
					'sale_rate' => $row->sale_rate,
				);
				$jsonArray_lines[] = $dt;
				
				$order_id 		= $row->order_id;
				$user_type 		= $row->user_type;
				$chemist_id 	= $row->chemist_id;
				$salesman_id 	= $row->selesman_id;
				$remarks 		= $row->remarks;
				$date 			= $row->date;
				$time 			= $row->time;
			}

			$row1 = $this->db->query("SELECT code,slcd FROM `tbl_acm` WHERE `altercode`='" . $chemist_id . "'")->row();
			if (!empty($row1->code)) {
				$acno = $row1->code;
				$slcd = $row1->slcd;
			}

			$new_temp_rec = time();
			//$remarks = $this->new_clean(htmlentities($remarks));

			$dt = array(
				'order_id' => $order_id,
				'chemist_id' => $chemist_id,
				'salesman_id' => $salesman_id,
				'user_type' => $user_type,
				'acno' => $acno,
				'slcd' => $slcd,
				'remarks' => $remarks,
				'date' => $date,
				'time' => $time,
				'total_line' => $total_line,
				'temp_rec' => $temp_rec,
				'new_temp_rec' => $new_temp_rec,
				'order_status' => "0",
			);
			$jsonArray[] = $dt;

			$items = $jsonArray;
			$items_other = $jsonArray_lines;

			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $items,
				'items_other' => $items_other,
			);
	
			// Send JSON response
			header('Content-Type: application/json');
			echo json_encode($response);
		}
	}
	
	public function status_update()
	{
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
	
	public function download_order_again()
	{
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

	public function order_error_download()
	{
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
		$isdone="";
		$data  = json_decode(file_get_contents('php://input'), true);
		$items = $data["items"];
		foreach ($items as $row) {
			if (!empty($row["gstvno"]) && !empty($row["order_id"])) {
				$gstvno 	= $row["gstvno"];
				$order_id 	= $row["order_id"];
				$this->db->query("update tbl_order set gstvno='$gstvno' where order_id='$order_id'");
				$isdone="yes";
			}
		}		
		if($isdone=="yes")
		{
			echo "done";
		}
	}
	
	
	public function download_order_in_sever_test($order_id)
	{
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

			$row1 = $this->db->query("SELECT code,slcd FROM `tbl_acm` WHERE `altercode`='" . $chemist_id . "'")->row();
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

	//new code bye 2024-07-12
	public function download_order_new($order_id)
	{
		$jsonArray_lines = array();
		$jsonArray = array();

		$total_line = 0;
		$date = date("Y-m-d");
		$q = $this->db->query("select temp_rec,date from tbl_order where order_id='$order_id' order by id asc limit 1")->row(); 
		if (!empty($q->temp_rec)) {
			$temp_rec = $q->temp_rec;

			$result = $this->db->query("select id,order_id,i_code,item_code,quantity,user_type,chemist_id,selesman_id,temp_rec,sale_rate,remarks,date,time from tbl_order where temp_rec='" . $temp_rec . "'")->result();
			foreach ($result as $row) {

				$total_line++;

				$dt = array(
					'total_line' => $total_line,
					'online_id' => $row->id,
					'i_code' => $row->i_code,
					'item_code' => $row->item_code,
					'quantity' => $row->quantity,
					'sale_rate' => $row->sale_rate,
				);
				$jsonArray_lines[] = $dt;
				
				$order_id 		= $row->order_id;
				$user_type 		= $row->user_type;
				$chemist_id 	= $row->chemist_id;
				$salesman_id 	= $row->selesman_id;
				$remarks 		= $row->remarks;
				$date 			= $row->date;
				$time 			= $row->time;
			}

			$row1 = $this->db->query("SELECT code,slcd FROM `tbl_acm` WHERE `altercode`='" . $chemist_id . "'")->row();
			if (!empty($row1->code)) {
				$acno = $row1->code;
				$slcd = $row1->slcd;
			}

			$new_temp_rec = time();
			//$remarks = $this->new_clean(htmlentities($remarks));

			$dt = array(
				'order_id' => $order_id,
				'chemist_id' => $chemist_id,
				'salesman_id' => $salesman_id,
				'user_type' => $user_type,
				'acno' => $acno,
				'slcd' => $slcd,
				'remarks' => $remarks,
				'date' => $date,
				'time' => $time,
				'total_line' => $total_line,
				'temp_rec' => $temp_rec,
				'new_temp_rec' => $new_temp_rec,
				'order_status' => "0",
			);
			$jsonArray[] = $dt;

			$items = $jsonArray;
			$items_other = $jsonArray_lines;

			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $items,
				'items_other' => $items_other,
			);
	
			// Send JSON response
			header('Content-Type: application/json');
			echo json_encode($response);
		}
	}

	public function download_order_gstvno($order_id,$gstvno)
	{
		if(!empty($order_id) && !empty($gstvno))
		{
			$this->db->query("update tbl_order set gstvno='$gstvno' where order_id='$order_id'");
			/***************only for group message***********************/
			$group2_message = "Order No. $order_id downloaded and inserted to easysol properly. Easysol Order No. $gstvno insert time is : ".date("d-M-y H:i");
			$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");
			$this->Message_Model->insert_whatsapp_group_message($whatsapp_group2,$group2_message);
			/*************************************************************/
		}
	}
}