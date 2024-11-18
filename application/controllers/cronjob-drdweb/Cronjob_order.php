<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_order extends CI_Controller 
{
    public function __construct(){
		parent::__construct();
        $this->load->model("model-drdweb/OrderModel");
    }
    
	public function run_job() {
        $this->OrderModel->run_job();
    }
}