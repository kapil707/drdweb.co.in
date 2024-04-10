<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeMasterManage extends CI_Controller
{
	public function insert_all_time_of_the_day(){
		$db_master = $this->load->database('db_master', TRUE);

		$date = date("Y-m-d");
		for($i=0;$i<=23;$i++){
			if($i<10){
				$i = "0$i";
			}
			for($j=0;$j<=59;$j++){
				if($j<10){
					$j = "0$j";
				}
				$time = "$i:$j";

				$db_master->query("insert into tbl_cronjob_time_for_exe (date,time) values ('$date','$time')");
			}
		}
	}
	public function upload_delivery_order()
	{
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
				$chemist_code 	= $row["chemist_code"];
				$chemist_name 	= $row["chemist_name"];
				$chemist_mobile = $row["chemist_mobile"];
				$chemist_id 	= $row["chemist_id"];
				$amount 		= $row["amount"];
				$user_altercode = $row["user_altercode"];
				$items 			= $row["items"];

				$db_master->query("insert into drd_master_tbl_delivery (dispatchtime,tagno,mtime,vdt,vno,pickedby,checkedby,deliverby,vtype,gstvno,chemist_code,chemist_name,chemist_mobile,chemist_id,amount,user_altercode,items) values ('$dispatchtime','$tagno','$mtime','$vdt','$vno','$pickedby','$checkedby','$deliverby','$vtype','$gstvno','$chemist_code','$chemist_name','$chemist_mobile','$chemist_id','$amount','$user_altercode','$items')");
				$isdone = "yes";
			}
		}
		if ($isdone == "yes") {
			echo "done";
		}
	}
}