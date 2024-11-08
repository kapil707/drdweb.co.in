<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_email extends CI_Controller 
{
	public function __construct(){
		parent::__construct();
		// Load model
		$this->load->model("model-drdweb/EmailModel");
	}
	
	public function send_email() {
		//$this->EmailModel->send_email_message();
		echo "Send Email Working";
	}
}