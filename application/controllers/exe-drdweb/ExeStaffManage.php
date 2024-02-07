<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeStaffManage extends CI_Controller
{
	public function download_staff()
	{
		$items 	= "";
		
		if (empty($items)) {
			$result = $this->db->query("select * from tbl_staffdetail_other where download_status=0 order by id asc limit 100")->result();
			foreach ($result as $row) {

				$id 			= $row->id;
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
				$website_limit = "";
	
$items .= '{"query_type":"staffdetail_other_insert","id":"'.$id.'","code":"'.$code.'","status":"'.$status.'","password":"'.$password.'","daily_date":"'.$daily_date.'","monthly":"'.$monthly.'","whatsapp_message":"'.$whatsapp_message.'","item_wise_report":"'.$item_wise_report.'","chemist_wise_report":"'.$chemist_wise_report.'","stock_and_sales_analysis":"'.$stock_and_sales_analysis.'","item_wise_report_daily_email":"'.$item_wise_report_daily_email.'","chemist_wise_report_daily_email":"'.$chemist_wise_report_daily_email.'","stock_and_sales_analysis_daily_email":"'.$stock_and_sales_analysis_daily_email.'","website_limit":"'.$website_limit.'","item_wise_report_monthly_email":"'.$item_wise_report_monthly_email.'","chemist_wise_report_monthly_email":"'.$chemist_wise_report_monthly_email.'"},';	
			}
		}

		if (empty($items)) {
			$result = $this->db->query("select * from tbl_staffdetail_other where download_status=1 order by id asc limit 4")->result();
			foreach ($result as $row) {

				$id 			= $row->id;
				$code 			= $row->code;
	
$items .= '{"query_type":"staffdetail_other_update","id":"'.$id.'","code":"'.$code.'"},';			
			}
		}

		if (!empty($items)) {

			if ($items != '') {
				$items = substr($items, 0, -1);
			}
echo $parmiter = '{"items": [' . $items . ']}';
		}
	}
	
	public function update_staff_status($id,$status)
	{
		if(!empty($id) && !empty($status)){
			echo $qry = "update tbl_staffdetail_other set download_status='$status' where id<='$id'";	
			$this->db->query($qry);
			//echo "done";
		}
	}
}