<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Test extends CI_Controller {
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->model("model-drdweb/InvoiceModel");
    }

	public function hello_g(){
		//echo "sdfsadf";
		
		//$file_name_dt  = $this->InvoiceModel->create_invoice_excle($vdt,$vno,$gstvno,$u_name,$chemist_id,"cronjob_download");
		
		$file_name_dt  = $this->InvoiceModel->create_invoice_excle("2024-01-19","566124","SB-23-566124","H185","cronjob_download");
	}
	
	public function create_invoice_excle_tt(){
		$this->InvoiceModel->create_invoice_excle_tt();
	}
}
?>