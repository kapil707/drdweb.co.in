<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_master extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
	}
	
	public function get_master_exe_call()
	{
		$db_master = $this->load->database('db_master', TRUE);

		$date = date("Y-m-d");
		$time = date("h:i a");
		$current_time = date("H:i:s");
		$time = date("H:i", strtotime($current_time . ' - 1 minute'));
		$result = $db_master->query("select * from tbl_cronjob_time_for_exe where time<='$time' and status=0 limit 100")->result();
		foreach($result as $row){
			$time = $row->time;

			$parmiter = '';

			$curl = curl_init();

			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL =>"http://122.160.139.36:7272/drd_local_server/cronjob-local/Cronjob_master/insert_delivery_order/".$time,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 0,
					CURLOPT_TIMEOUT => 300,
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
			print_r($response);
			curl_close($curl);

			$id = $row->id;
			$db_master->query("update tbl_cronjob_time_for_exe set status=1 where id='$id'");
		}
	}
}