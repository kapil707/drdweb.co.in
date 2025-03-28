<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_profile extends CI_Controller {
	public function __construct(){
		parent::__construct();
	}
	
	public function upload_user_profile_api()
	{
        $user_image = "";
		$upload_image = "./user_profile";
		if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
			ini_set('upload_max_filesize', '10M');
			ini_set('post_max_size', '10M');
			ini_set('max_input_time', 300);
			ini_set('max_execution_time', 300);

			$new_filename = 'user_' . time() . '_' . uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

			$config['upload_path'] = $upload_image;  // Define the directory where you want to store the uploaded files.
			$config['allowed_types'] = 'jpg|jpeg|png|gif';  // Restrict to image file types.
			$config['max_size'] = 0;  // Set to 0 to allow any size.
			$config['file_name'] = $new_filename;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('image')) {  // Changed 'upload_image' to 'image'
				$error = array('error' => $this->upload->display_errors());
				$status = 0;
				$status_message = $error;
			} else {
				$data = $this->upload->data();
				$user_image = ($data['file_name']);

				if (strpos($user_image, "temp_image") !== false) {
					$user_image = "";  // Clear image if it contains "temp_image"
				}
				$status = 1;
				$status_message = "Uploaded successfully.";
			}
		} else {
			// Invalid file or no file uploaded
			$status = 0;
			$status_message = "Invalid file or no file uploaded.";
		}

        $response = array(
            'success' => $status,
            'message' => $status_message,
            'user_image' => $user_image,
        );

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
	}
}