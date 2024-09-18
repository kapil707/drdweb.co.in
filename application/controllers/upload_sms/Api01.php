<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Api01 extends CI_Controller {	

	public function __construct(){

		parent::__construct();

		$this->load->model("model-drdweb/BankModel");
	}

	public function get_upload_sms() {
		
		if(!empty($_REQUEST)){
			$from_date 	= $_REQUEST["from_date"];
			$to_date	= $_REQUEST['to_date'];

			$jsonArray = array();

			$items = "";
			if(!empty($from_date) && !empty($to_date)){
				$parmiter = '';
				$curl = curl_init();
					
					curl_setopt_array(
						$curl,
						array(
							CURLOPT_URL =>"http://122.160.139.36:7272/drd_local_server/upload_sms/api01/get_upload_sms?from_date=2024-09-18&to_date=2024-09-18",
							CURLOPT_RETURNTRANSFER => true,
							CURLOPT_ENCODING => '',
							CURLOPT_MAXREDIRS => 0,
							CURLOPT_TIMEOUT => 300,
							CURLOPT_FOLLOWLOCATION => true,
							CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							CURLOPT_CUSTOMREQUEST => 'GET',
							CURLOPT_POSTFIELDS => $parmiter,
							CURLOPT_HTTPHEADER => array(
								'Content-Type: application/json',
								'Authorization: Bearer THIRTEENWOLVESWENTHUNTINGBUT10CAMEBACK'
							),
						)
					);

					$response = curl_exec($curl);
					//print_r($response);
					curl_close($curl);

					$data1 = json_decode($response, true); // Convert JSON string to associative array
			}

			$items = $data1["items"];
			$response = array(
				'success' => "1",
				'message' => 'Data load successfully',
				'items' => $items,
			);
		}else{
			$response = array(
				'success' => "0",
				'message' => '502 error',
			);
		}

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}