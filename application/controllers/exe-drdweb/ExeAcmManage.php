<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeAcmManage extends CI_Controller
{
	public function download_acm_other()
	{
		$items 	= "";
		
		if (empty($items)) {
			$result = $this->db->query("select * from tbl_chemist_other where download_status=0 order by id asc limit 100")->result();
			foreach ($result as $row) {

				$id 			= $row->id;
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
	
$items .= '{"id":"'.$id.'","code":"'.$code.'","status":"'.$status.'","exp_date":"'.$exp_date.'","password":"'.$password.'","broadcast":"'.$broadcast.'","block":"'.$block.'","image":"'.$image.'","user_phone":"'.$user_phone.'","user_email":"'.$user_email.'","user_address":"'.$user_address.'","user_update":"'.$user_update.'","order_limit":"'.$order_limit.'","new_request":"'.$new_request.'","website_limit":"'.$website_limit.'","android_limit":"'.$android_limit.'"},';	
			}
		}

		if (!empty($items)) {

			if ($items != '') {
				$items = substr($items, 0, -1);
			}
echo $parmiter = '{"items": [' . $items . ']}';
		}
	}
	
	public function update_acm_other_status($id,$status)
	{
		if(!empty($id) && !empty($status)){
			echo $qry = "update tbl_chemist_other set download_status='$status' where id<='$id'";	
			$this->db->query($qry);
			//echo "done";
		}
	}
}