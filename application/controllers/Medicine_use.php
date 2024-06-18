<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Medicine_use extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}
	
	public function get_medicine_use(){
		if($_POST["item_code"]){
			$item_code = $_POST["item_code"];
		}
		$jsonArray = array();

		$php_files = glob('./uploads/manage_medicine_use/'.$item_code.'/*');
		foreach($php_files as $file) {
			$file = str_replace("./","",$file);
			$file = base_url().$file;		

			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if($ext=="jpg"){
				$file_type = "image";
			}
			if($ext=="mp4"){
				$file_type = "video";
			}

			$dt = array(
				'file' => $file,
				'file_type' => $file_type,
			);
			$jsonArray[] = $dt;
		}
		
		$medicine_details = "";
		
		$response = array(
            'success' => "1",
            'message' => 'Data load successfully',
            'medicine_details' => $medicine_details,
            'medicine_use' => $jsonArray
        );

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}