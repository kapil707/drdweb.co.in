<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeMedicineImageManage extends CI_Controller
{
	public function download_medicine_image()
	{
		$items 	= "";
		
		if (empty($items)) {
			$result = $this->db->query("select * from tbl_medicine_image where download_status=0 order by id asc limit 1000")->result();
			foreach ($result as $row) {

				$id 			= $row->id;
				$itemid 		= $row->itemid;
				$featured 		= $row->featured;
				$image 			= $row->image;
				$image2 		= $row->image2;
				$image3 		= $row->image3;
				$image4 		= $row->image4;
				$status 		= $row->status;
				$date 			= $row->date;
				$time 			= $row->time;
					
				$description 	= htmlentities($row->description);
				$description 	= str_replace("'", "&prime;", $description);
				$description 	= base64_encode($description);
				$title 			= base64_encode($row->title);

$items .= '{"id":"'.$id.'","itemid":"'.$itemid.'","featured":"'.$featured.'","image":"'.$image.'","image2":"'.$image2.'","image3":"'.$image3.'","image4":"'.$image4.'","title":"'.$title.'","description":"'.$description.'","status":"'.$status.'","date":"'.$date.'","time":"'.$time.'"},';
			}
		}

		if (!empty($items)) {

			if ($items != '') {
				$items = substr($items, 0, -1);
			}
echo $parmiter = '{"items": [' . $items . ']}';
		}
	}
	
	public function update_medicine_image_status($id,$status)
	{
		if(!empty($id) && !empty($status)){
			echo $qry = "update tbl_medicine_image set download_status='$status' where id<='$id'";	
			$this->db->query($qry);
			//echo "done";
		}
	}
}