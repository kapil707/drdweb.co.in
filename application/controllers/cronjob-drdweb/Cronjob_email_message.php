<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_email_message extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		// Load model
		$this->load->model("model-drdweb/EmailModel");
	}
	
	public function send_email()
	{
		$this->EmailModel->send_email();
		echo "Send Email Working";
	}
}