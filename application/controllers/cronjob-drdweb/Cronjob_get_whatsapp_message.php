<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_get_whatsapp_message extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
	}
	
	public function get_whatsapp_message()
	{
	
		$parmiter = '';
		$curl = curl_init();
		
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL =>"http://97.74.82.55:5000/messages?from=07/04/2024&to=07/04/2024",
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
				),
			)
		);

		$response = curl_exec($curl);
		//print_r($response);
		curl_close($curl);

		$data = json_decode($response, true); // Convert JSON string to associative array

		if (isset($data['messages'])) {
			foreach ($data['messages'] as $message) {
				$body = isset($message['body']) ? $message['body'] : "Body not found";
				$date = isset($message['date']) ? $message['date'] : "Date not found";
		
				echo "Body: " . $body . "\n";
				echo "Date: " . $date . "\n";
				echo "\n";
			}
		} else {
			echo "No messages found.\n";
		}
	}
}