<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeLowStockManage extends CI_Controller
{
	public function download_low_stock()
	{
		$items 	= "";
		
		if (empty($items)) {
			$result = $this->db->query("SELECT tbl_low_stock_alert.id,tbl_low_stock_alert.date,tbl_low_stock_alert.time,tbl_chemist.code,tbl_low_stock_alert.i_code FROM tbl_low_stock_alert,tbl_chemist where tbl_chemist.altercode=tbl_low_stock_alert.chemist_id and tbl_low_stock_alert.user_type='chemist' and tbl_low_stock_alert.download_status=0 order by id asc limit 1")->result();
			foreach ($result as $row) {

				$id 	= $row->id;
				$slcd  	= "CL";
				$uid   	= "DRD";
				$vdt 	= $row->date." ".$row->time;
				$acno 	= $row->code;
				$itemc 	= $row->i_code;
	
$items .= '{"id":"'.$id.'","slcd":"'.$slcd.'","uid":"'.$uid.'","vdt":"'.$vdt.'","acno":"'.$acno.'","itemc":"'.$itemc.'"},';
			}
		}

		if (!empty($items)) {

			if ($items != '') {
				$items = substr($items, 0, -1);
			}
echo $parmiter = '{"items": [' . $items . ']}';
		}
	}
	
	public function update_low_stock_status($id,$status)
	{
		if(!empty($id) && !empty($status)){
			echo $qry = "update tbl_low_stock_alert set download_status='$status' where id='$id'";	
			$this->db->query($qry);
			//echo "done";
		}
	}
}