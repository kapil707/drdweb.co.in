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
		$result = $db_master("select * from tbl_cronjob_time_for_exe where time<='$time'")->result();
		foreach($result as $row){
			echo $row->time;
			echo "<br>";
		}
	}
}