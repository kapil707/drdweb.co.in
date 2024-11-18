<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjob_chemist extends CI_Controller 
{
	public function __construct(){

		parent::__construct();

		// Load model
	}
	
	public function active_inactive_chemist()
	{
		$this->db->query("update tbl_chemist_other set block='0'");

		$result = $this->db->query("SELECT tbl_chemist.code,tbl_chemist.altercode FROM `tbl_chemist` join tbl_chemist_other on tbl_chemist
		.code=tbl_chemist_other.code WHERE tbl_chemist.STATUS='*'")->result();
		foreach($result as $row){
			echo $row->code. " :-> " .$row->altercode;
			echo "<br>";
			$this->db->query("update tbl_chemist_other set block='1' where code='$row->code'");
			$this->db->query("update tbl_android_device_id set logout='1' where user_type='chemist' and chemist_id='$row->altercode'");
		}
	}

	public function backup_table($tableName = 'tbl_chemist_other') {
        // Backup file name with date and time
        $backupFile = FCPATH . 'backup_sql/backup_' . $tableName . '_' . date('Y-m-d_H-i-s') . '.sql';

        // Open file for writing
        $fileHandle = fopen($backupFile, 'w');
        if (!$fileHandle) {
            echo 'Unable to create backup file';
            return;
        }

        // Step 1: Get table structure
        $structureQuery = $this->db->query("SHOW CREATE TABLE `$tableName`");
        if ($structureQuery->num_rows() > 0) {
            $row = $structureQuery->row_array();
            fwrite($fileHandle, "-- Table structure for `$tableName`\n\n");
            fwrite($fileHandle, $row['Create Table'] . ";\n\n");
        }

        // Step 2: Get table data
        $dataQuery = $this->db->query("SELECT * FROM `$tableName`");
        if ($dataQuery->num_rows() > 0) {
            fwrite($fileHandle, "-- Dumping data for `$tableName`\n\n");

            foreach ($dataQuery->result_array() as $row) {
                $columns = array_keys($row);
                $values = array_values($row);

                // Prepare SQL insert statement
                $insertQuery = "INSERT INTO `$tableName` (`" . implode('`, `', $columns) . "`) VALUES ('" . implode("', '", array_map([$this->db, 'escape_str'], $values)) . "');\n";
                fwrite($fileHandle, $insertQuery);
            }
        }

        // Close file
        fclose($fileHandle);

        echo "Backup successfully created in file: $backupFile";
    }
}