<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeDownloadOrder_test extends CI_Controller
{
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

	public function update_order_gstvno($order_id,$gstvno,$total)
	{
		if(!empty($order_id) && !empty($gstvno))
		{
			$row = $this->db->query("SELECT count(id) as total1 FROM `tbl_order` WHERE `order_id`='$order_id'")->row();
			$total1 = $row->total1;
			$this->db->query("update tbl_order set gstvno='$gstvno',download_status=1,download_line='$total' where order_id='$order_id'");
			/***************only for group message***********************/
			$group2_message = "Order No. $order_id download Line Items ($total/$total1) - Easysol No. $gstvno inserted at : ".date("d-M-y H:i");
			$whatsapp_group2 = $this->Scheme_Model->get_website_data("whatsapp_group2");
			$this->Message_Model->insert_whatsapp_group_message($whatsapp_group2,$group2_message);

			if($total!=$total1){
				$group1_message = "Order No. $order_id download Line Items ($total/$total1) - Easysol No. $gstvno inserted at : ".date("d-M-y H:i");
				$whatsapp_group1 = $this->Scheme_Model->get_website_data("whatsapp_group1");
				$this->Message_Model->insert_whatsapp_group_message($whatsapp_group1,$group1_message);
			}
			/*************************************************************/
		}
	}
}