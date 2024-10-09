<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_corporate_report extends CI_Controller 
{
	public function __construct(){

		parent::__construct();
		// Load model
	}
	
	public function delete_folder()
	{
		// Usage
		$dirPath = './corporate_report/2023-07-01';
		if (deleteDirectory($dirPath)) {
			echo "Directory deleted successfully!";
		} else {
			echo "Failed to delete directory.";
		}
	}

	public function deleteDirectory($dir)
	{
		if (!is_dir($dir)) {
			return false; // Return false if not a directory
		}
	
		$items = scandir($dir); // Get all files and directories inside
		foreach ($items as $item) {
			if ($item == '.' || $item == '..') {
				continue; // Skip the current and parent directory pointers
			}
			$path = $dir . DIRECTORY_SEPARATOR . $item;
			if (is_dir($path)) {
				deleteDirectory($path); // Recursively delete subdirectory
			} else {
				unlink($path); // Delete file
			}
		}
	
		return rmdir($dir); // Finally, remove the directory
	}
}