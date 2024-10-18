<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeDownloadOrder extends CI_Controller
{
	//new code bye 2024-07-12
	public function download_order($order_id)
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
			}
			foreach ($result as $row) {
				
				$dt = array(
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

			$row1 = $this->db->query("SELECT code,slcd FROM `tbl_chemist` WHERE `altercode`='" . $chemist_id . "'")->row();
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

	public function download_order2($order_id)
	{
		$jsonArray_lines = array();
		$jsonArray = array();

		$total_line = 0;
		$date = date("Y-m-d");
		$download_time = date("YmdHi");
		$result = $this->db->query("SELECT	MAX(id) AS id,order_id,
		i_code,	item_code,SUM(quantity) AS quantity,user_type,	chemist_id,selesman_id,temp_rec,sale_rate,MAX(remarks) AS remarks, MAX(date) AS date,MAX(time) AS time FROM tbl_order WHERE order_id='$order_id' and download_time<='$download_time' GROUP BY item_code,order_id,i_code,user_type,chemist_id,selesman_id,temp_rec,sale_rate order by id asc")->result();
		// Count total lines
		$total_line = count($result);

		// Process each row from the result set
		foreach ($result as $row) {
			// Populate item details into the array
			$dt = array(
				'online_id' => $row->id,
				'i_code' => $row->i_code,
				'item_code' => $row->item_code,
				'quantity' => $row->quantity,
				'sale_rate' => $row->sale_rate,
			);
			$jsonArray_lines[] = $dt;

			// These values will represent the last processed row's details
			$order_id = $row->order_id;
			$user_type = $row->user_type;
			$chemist_id = $row->chemist_id;
			$salesman_id = $row->selesman_id;
			$remarks = $row->remarks;
			$date = $row->date;
			$time = $row->time;
		}

		// If result is not empty, proceed to fetch chemist details
		if (!empty($result)) {
			$row1 = $this->db->query("SELECT code, slcd FROM `tbl_chemist` WHERE `altercode`='" . $chemist_id . "'")->row();
			$acno = !empty($row1->code) ? $row1->code : null;  // Use null if code not found
			$slcd = !empty($row1->slcd) ? $row1->slcd : null; // Use null if slcd not found

			// Final order details
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
			);
			$jsonArray[] = $dt;

			// Prepare the response structure
			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $jsonArray,
				'items_other' => $jsonArray_lines,
			);
		} else {
			// If no result found, prepare an empty response
			$response = array(
				'success' => "0",
				'message' => 'No data found',
				'items' => "",
				'items_other' => "",
			);
		}
		// Send JSON response
		header('Content-Type: application/json');
		echo json_encode($response);
	}
	public function update_order_gstvno($order_id,$gstvno,$insert_total_line,$download_total_line)
	{
		if(!empty($order_id) && !empty($gstvno))
		{
			$row = $this->db->query("SELECT count(id) as total1 FROM `tbl_order` WHERE `order_id`='$order_id'")->row();
			$total = $row->total1;
			$this->db->query("update tbl_order set gstvno='$gstvno',download_status=1,download_line='$insert_total_line' where order_id='$order_id'");
			/***************only for group message***********************/
			$group2_message = "Order No. $order_id download Line Items (Total:$total/Download:$download_total_line/Insert:$insert_total_line) - Easysol No. $gstvno inserted at : ".date("d-M-y H:i");
			$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");
			$this->Message_Model->insert_whatsapp_group_message($whatsapp_group2,$group2_message);

			/***********************************************************/ 
			if($total!=$insert_total_line){
				$group1_message = "Order No. $order_id download Line Items (Total:$total/Download:$download_total_line/Insert:$insert_total_line) - Easysol No. $gstvno inserted at : ".date("d-M-y H:i");
				$whatsapp_group1 = $this->Scheme_Model->get_website_data("whatsapp_group1");
				$this->Message_Model->insert_whatsapp_group_message($whatsapp_group1,$group1_message);
			}
			/*************************************************************/
		}
	}

	public function update_order_gstvno_error($order_id,$gstvno,$insert_total_line,$download_total_line)
	{
		if(!empty($order_id) && !empty($gstvno))
		{
			$row = $this->db->query("SELECT count(id) as total1 FROM `tbl_order` WHERE `order_id`='$order_id'")->row();
			$total = $row->total1;

			$group1_message = $group2_message = "Problem Order No. $order_id (Total:$total/Download:$download_total_line/Insert:$insert_total_line) - at : ".date("d-M-y H:i");

			/******************************************************* */
			$whatsapp_group1 = $this->Scheme_Model->get_website_data("whatsapp_group1");
			$this->Message_Model->insert_whatsapp_group_message($whatsapp_group1,$group1_message);
			
			/******************************************************* */
			$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");
			$this->Message_Model->insert_whatsapp_group_message($whatsapp_group2,$group2_message);
		}
	}

	/********************test********************************** */
	//new code bye 2024-07-12
	public function download_order_test($order_id)
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
			}
			foreach ($result as $row) {
				
				$dt = array(
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

			$row1 = $this->db->query("SELECT code,slcd FROM `tbl_chemist` WHERE `altercode`='" . $chemist_id . "'")->row();
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

	public function update_order_gstvno_test($order_id,$gstvno,$insert_total_line,$download_total_line)
	{
		if(!empty($order_id) && !empty($gstvno))
		{
			$row = $this->db->query("SELECT count(id) as total1 FROM `tbl_order` WHERE `order_id`='$order_id'")->row();
			$total = $row->total1;
			//$this->db->query("update tbl_order set gstvno='$gstvno',download_status=1,download_line='$total' where order_id='$order_id'");
			/***************only for group message***********************/
			$group2_message = "Test Order No. $order_id download Line Items (Total:$total/Download:$download_total_line/Insert:$insert_total_line) - Easysol No. $gstvno inserted at : ".date("d-M-y H:i");
			$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");
			$this->Message_Model->insert_whatsapp_group_message($whatsapp_group2,$group2_message);

			/*********************************************************** 
			if($total!=$insert_total_line){
				$group1_message = "Test Order No. $order_id download Line Items (Total:$total/Download:$download_total_line/Insert:$insert_total_line) - Easysol No. $gstvno inserted at : ".date("d-M-y H:i");
				$whatsapp_group1 = $this->Scheme_Model->get_website_data("whatsapp_group1");
				$this->Message_Model->insert_whatsapp_group_message($whatsapp_group1,$group1_message);
			}
			/*************************************************************/
		}
	}

	public function update_order_gstvno_error_test($order_id,$gstvno,$insert_total_line,$download_total_line)
	{
		if(!empty($order_id) && !empty($gstvno))
		{
			$row = $this->db->query("SELECT count(id) as total1 FROM `tbl_order` WHERE `order_id`='$order_id'")->row();
			$total = $row->total1;

			$group_message = $group2_message = "Test Problem Order No. $order_id (Total:$total/Download:$download_total_line/Insert:$insert_total_line) - at : ".date("d-M-y H:i");

			/******************************************************* *
			$whatsapp_group1 = $this->Scheme_Model->get_website_data("whatsapp_group1");
			$this->Message_Model->insert_whatsapp_group_message($whatsapp_group1,$group1_message);
			
			/******************************************************* */
			$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");
			$this->Message_Model->insert_whatsapp_group_message($whatsapp_group2,$group2_message);
		}
	}
}