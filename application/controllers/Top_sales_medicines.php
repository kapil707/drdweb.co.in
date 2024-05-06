<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Top_sales_medicines extends CI_Controller {
	public function __construct(){

		parent::__construct();

		$this->load->model("model-drdweb/InvoiceModel");
	}
	
	public function index()	{	
		$this->load->view('top_sales_medicines');
	}
}