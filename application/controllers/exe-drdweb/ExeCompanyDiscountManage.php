<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeCompanyDiscountManage extends CI_Controller
{
	public function download_company_discount()
	{
		$items 	= "";
		
		if (empty($items)) {
			$result = $this->db->query("select * from tbl_company_discount where download_status=0 order by id asc limit 100")->result();
			foreach ($result as $row) {

				$id 			= $row->id;
				$compcode 		= $row->compcode;
				$division 		= $row->division;
				$discount 		= $row->discount;
				$status 		= $row->status;
	
$items .= '{"id":"'.$id.'","compcode":"'.$compcode.'","division":"'.$division.'","discount":"'.$discount.'","status":"'.$status.'"},';
			}
		}

		if (!empty($items)) {

			if ($items != '') {
				$items = substr($items, 0, -1);
			}
echo $parmiter = '{"items": [' . $items . ']}';
		}
	}
	
	public function update_company_discount_status($id,$status)
	{
		if(!empty($id) && !empty($status)){
			echo $qry = "update tbl_company_discount set download_status='$status' where id<='$id'";	
			$this->db->query($qry);
			//echo "done";
		}
	}
}