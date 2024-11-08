<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_notification extends CI_Controller 
{
	public function __construct(){
		parent::__construct();
		// Load model
		$this->load->model("model-drdweb/NotificationModel");
	}
	
	public function send_notification()
	{
		$this->NotificationModel->send_notification();
	}
}