<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sms extends CI_Controller {
	public function __construct(){

		parent::__construct();

		$this->load->model("model-drdweb/BankModel");
	}
	
	public function index()	{	
		$this->load->view('sms');
	}
}