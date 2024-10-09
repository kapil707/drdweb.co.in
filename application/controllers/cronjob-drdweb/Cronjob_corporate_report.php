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
		$start_date = new DateTime('2023-06-01');
		$end_date = new DateTime('2024-06-31'); // today's date

		// Loop through each day
		while ($start_date <= $end_date) {
			// Output the date in yyyy-mm-dd format
			echo $dt = $start_date->format('Y-m-d');

			$dirPath = './corporate_report/'.$dt;
			if ($this->deleteDirectory($dirPath)) {
				echo "Directory deleted successfully!";
			} else {
				echo "Failed to delete directory.";
			}
			echo "<br>";
			// Move to the next day
			$start_date->modify('+1 day');
		}

		// Usage
		/*if($dt!=""){
			$dirPath = './corporate_report/'.$dt;
			if ($this->deleteDirectory($dirPath)) {
				echo "Directory deleted successfully!";
			} else {
				echo "Failed to delete directory.";
			}
		}*/
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