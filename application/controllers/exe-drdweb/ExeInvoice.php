<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExeInvoice extends CI_Controller
{
	public function __construct(){
		parent::__construct();
	}

	public function not_found_invoice(){
		$id_array = array();
		$query = $this->db->query("SELECT t.vno FROM tbl_invoice AS t LEFT JOIN tbl_invoice_item AS ti ON t.vno = ti.vno WHERE ti.vno IS NULL limit 500");
		$result = $query->result();
		foreach($result as $row){
			$id_array[] 	= $row->vno;
		}
		if(!empty($id_array)){
			$commaSeparatedString = implode(',', $id_array);
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		}
		else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["return_values" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}

	public function upload_invoice()
	{
		//OPTIMIZE TABLE tbl_medicine;

		// Data ko read karna (input stream se)
		$inputData = file_get_contents("php://input");

		// JSON data ko PHP array me convert karna
		$data = json_decode($inputData, true);

		
		// Data ko check karna
		if ($data && is_array($data)) {
			// Aap yaha data ko process kar sakte hain, jaise ki database me save karna, logging karna, etc.
			
			//print_r($data);

			// Example: Data ko print karna (ya log karna)
			//file_put_contents("log.txt", print_r($data, true), FILE_APPEND);

			$id_array = array();
			foreach ($data as $record) {
				$id_array[] 	= $record['id'];
				
				$id 			= $record["id"];
				$mtime 			= $record["mtime"];
				$dispatchtime 	= $record["dispatchtime"];
				$date 			= $record["date"];
				$vno 			= $record["vno"];
				$tagno 			= $record["tagno"];
				$gstvno 		= $record["gstvno"];
				$pickedby 		= $record["pickedby"];
				$checkedby 		= $record["checkedby"];
				$deliverby 		= $record["deliverby"];
				$amt 			= $record["amt"];
				$taxamt 		= $record["taxamt"];
				$acno 			= $record["acno"];
				$chemist_id 	= $record["chemist_id"];
				
				$insert_time 	= date('Y-m-d,H:i');

				$dt = array(
					'mtime' => $mtime,
					'dispatchtime' => $dispatchtime,
					'date' => $date,
					'vno' => $vno,
					'tagno' => $tagno,
					'gstvno' => $gstvno,
					'pickedby' => $pickedby,
					'checkedby' => $checkedby,
					'deliverby' => $deliverby,
					'amt' => $amt,
					'taxamt' => $taxamt,
					'acno' => $acno,
					'chemist_id' => $chemist_id,
					'status' => 0,
					'insert_time' => $insert_time,
				);
				
				if (!empty($gstvno)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->select_row("tbl_invoice", array('gstvno' => $gstvno));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('gstvno' => $gstvno);
						$this->Scheme_Model->edit_fun("tbl_invoice", $dt, $where);

						// yha delete karta ha taki jo medicines delete hui ha wo sahi rahy 
						/************************************************* */
						$this->db->query("delete from tbl_invoice_item where vno='$vno' and date='$date'");
						/************************************************* */
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_invoice", $dt);
					}
				}
			}
			$commaSeparatedString = implode(',', $id_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["return_values" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}

	public function upload_invoice_item()
	{
		//OPTIMIZE TABLE tbl_medicine;

		// Data ko read karna (input stream se)
		$inputData = file_get_contents("php://input");

		// JSON data ko PHP array me convert karna
		$data = json_decode($inputData, true);

		
		// Data ko check karna
		if ($data && is_array($data)) {
			// Aap yaha data ko process kar sakte hain, jaise ki database me save karna, logging karna, etc.
			
			//print_r($data);

			// Example: Data ko print karna (ya log karna)
			//file_put_contents("log.txt", print_r($data, true), FILE_APPEND);

			//$id_array = array();
			foreach ($data as $record) {
				//$id_array[] 	= $record['id'];
				
				$id 			= $record["id"]; //yha exe say he comaspred value aa rahi ha
				$srlno 			= $record["srlno"];
				$vno 			= $record["vno"];
				$date 			= $record["date"];
				$psrlno			= $record["psrlno"];
				$itemc 			= $record["itemc"];
				$batch 			= $record["batch"];
				$qty 			= $record["qty"];
				$fqty 			= $record["fqty"];
				$ntrate 		= $record["ntrate"];
				$ftrate 		= $record["ftrate"];
				$dis 			= $record["dis"];
				$disamt 		= $record["disamt"];
				$netamt 		= $record["netamt"];
				$halfp 			= $record["halfp"];
				$mrp 			= $record["mrp"];
				$hsncode 		= $record["hsncode"];
				$expiry 		= $record["expiry"];
				$scm1 			= $record["scm1"];
				$scm2 			= $record["scm2"];
				$scmper 		= $record["scmper"];
				$localcent 		= $record["localcent"];
				$excise 		= $record["excise"];
				$cgst 			= $record["cgst"];
				$sgst 			= $record["sgst"];
				$igst 			= $record["igst"];
				$igst 			= $record["igst"];
				$adnlvat		= $record["adnlvat"];
				$gdn 			= $record["gdn"];

				
				$insert_time 	= date('Y-m-d,H:i');

				$dt = array(
					'srlno' => $srlno,
					'vno' => $vno,
					'date' => $date,
					'psrlno' => $psrlno,
					'itemc' => $itemc,
					'batch' => $batch,
					'qty' => $qty,
					'fqty' => $fqty,
					'ntrate' => $ntrate,
					'ftrate' => $ftrate,
					'dis' => $dis,
					'disamt' => $disamt,
					'netamt' => $netamt,
					'halfp' => $halfp,
					'mrp' => $mrp,
					'hsncode' => $hsncode,
					'expiry' => $expiry,
					'scm1' => $scm1,
					'scm2' => $scm2,
					'scmper' => $scmper,
					'localcent' => $localcent,
					'excise' => $excise,
					'cgst' => $cgst,
					'sgst' => $sgst,
					'igst' => $igst,
					'adnlvat' => $adnlvat,
					'gdn' => $gdn,
					'insert_time' => $insert_time,
				);
				
				if (!empty($srlno)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->select_row("tbl_invoice_item", array('srlno' => $srlno,'vno' => $vno));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('srlno' => $srlno,'vno' => $vno);
						$this->Scheme_Model->edit_fun("tbl_invoice_item", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_invoice_item", $dt);
					}
				}
			}
			$commaSeparatedString = $id; //yha exe say he comaspred value aa rahi ha
			//$commaSeparatedString = implode(',', $id_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["return_values" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}

	/*public function upload_invoice_item_delete()
	{
		//OPTIMIZE TABLE tbl_medicine;

		// Data ko read karna (input stream se)
		$inputData = file_get_contents("php://input");

		// JSON data ko PHP array me convert karna
		$data = json_decode($inputData, true);

		
		// Data ko check karna
		if ($data && is_array($data)) {
			// Aap yaha data ko process kar sakte hain, jaise ki database me save karna, logging karna, etc.
			
			//print_r($data);

			// Example: Data ko print karna (ya log karna)
			//file_put_contents("log.txt", print_r($data, true), FILE_APPEND);

			//$id_array = array();
			foreach ($data as $record) {
				//$id_array[] 	= $record['id'];
				
				$id 			= $record["id"]; //yha exe say he comaspred value aa rahi ha
				$itemc 			= $record["itemc"];
				$item_name 		= $record["item_name"];
				$vno 			= $record["vno"];
				$date			= $record["date"];
				$slcd 			= $record["slcd"];
				$amt 			= $record["amt"];
				$namt 			= $record["namt"];
				$remarks 		= $record["remarks"];
				$descp 			= $record["descp"];
				
				$insert_time 	= date('Y-m-d,H:i');

				$dt = array(
					'itemc' => $itemc,
					'item_name' => $item_name,
					'vno' => $vno,
					'date' => $date,
					'slcd' => $slcd,
					'amt' => $amt,
					'namt' => $namt,
					'remarks' => $remarks,
					'descp' => $descp,
					'insert_time' => $insert_time,
				);
				
				if (!empty($itemc)) {
					// Check karo agar record already exist karta hai
					$existing_record = $this->Scheme_Model->select_row("tbl_invoice_item_delete", array('itemc' => $itemc,'vno' => $vno,'date' => $date,'amt' => $amt,'namt' => $namt,'remarks' => $remarks));
			
					if ($existing_record) {
						// Agar record exist karta hai to update karo
						$where = array('itemc' => $itemc,'vno' => $vno,'date' => $date,'amt' => $amt,'namt' => $namt,'remarks' => $remarks);
						$this->Scheme_Model->edit_fun("tbl_invoice_item_delete", $dt, $where);
					} else {
						// Agar record exist nahi karta hai to insert karo
						$this->Scheme_Model->insert_fun("tbl_invoice_item_delete", $dt);
					}
				}
			}
			$commaSeparatedString = $id; //yha exe say he comaspred value aa rahi ha
			//$commaSeparatedString = implode(',', $id_array);
			// Response dena
			echo json_encode(["return_values" => $commaSeparatedString,"status" => "success", "message" => "Data received successfully"]);
		} else {
			// Agar data valid nahi hai to error response dena
			echo json_encode(["return_values" => "error","status" => "error", "message" => "Invalid data"]);
		}
	}*/

	public function upload_invoice_item_delete() {

		$inputData = $inputData1 = file_get_contents("php://input");

		$vno = $date = "";
		$data = json_decode($inputData, true);
		// Data ko check karna
		if ($data && is_array($data)) {
			// Aap yaha data ko process kar sakte hain, jaise ki database me save karna, logging karna, etc.
			
			//print_r($data);

			// Example: Data ko print karna (ya log karna)
			//file_put_contents("log.txt", print_r($data, true), FILE_APPEND);

			//$id_array = array();
			foreach ($data as $record) {
				$id 			= $record["id"];
				$vno			= $record["vno"];
				$date			= $record["date"];
			}
		}

		if(!empty($vno) && !empty($date)){
			// JSON data ko PHP array me convert karna
			$data = $inputData1;

			// Folder to save the JSON file (ensure this folder exists and is writable)
			$folder = './invoice_files/'.$date.'/';

			 // Check if the folder exists, if not create it
			 if (!is_dir($folder)) {
				mkdir($folder, 0755, true);  // Create the folder with read/write/execute permissions
			}

			// File name (based on current date/time to avoid overwriting)
			$filename =  $vno . '.json';

			// Full path to save the file
			$filepath = $folder . $filename;

			// Check if the file already exists, if so, delete it
			if (file_exists($filepath)) {
				unlink($filepath);  // Delete the existing file
			}

			// Write JSON data to the file
			if (file_put_contents($filepath, $data) !== false) {
				echo json_encode(["return_values" => $id,"status" => "success", "message" => "Data received successfully"]);
			} else {
				// Agar data valid nahi hai to error response dena
				echo json_encode(["return_values" => "error","status" => "error", "message" => "Invalid data"]);
			}
		}
    }

	public function insert_json_data_to_db($date="") {
		// Load the helper file for writing JSON files
        $this->load->helper('file');

        //$date = "2024-04-01";

        // Specify the path to the main JSON file
        $filePath = './invoice_files/'.$date.'.json';  // Adjust this path as needed

        // Check if the file exists
        if (!file_exists($filePath)) {
            echo 'File does not exist: ' . $filePath;
            return;
        }

        // Read the contents of the JSON file
        $jsonData = file_get_contents($filePath);

        // Decode JSON data into an array
        $data = json_decode($jsonData, true);

        // Check for errors during decoding
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'Error decoding JSON data: ' . json_last_error_msg();
            return;
        }

        // Prepare an empty array to hold data for each `vno`
        $vnoData = [];

        // Organize data by `vno`
        foreach ($data as $item) {
            $vno = $item['vno'];
            if (!isset($vnoData[$vno])) {
                $vnoData[$vno] = [];
            }
            $vnoData[$vno][] = $item;
        }

        // Create a folder for saving the split files based on the date
        $folder = './invoice_files/'.$date.'/';
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        // Create JSON files for each `vno`
        foreach ($vnoData as $vno => $items) {
            $filename = $vno . '.json';
            $filepath = $folder . $filename;

            // If the file already exists, delete it
            if (file_exists($filepath)) {
                unlink($filepath);
            }

            // Encode the `vno` items into JSON format
            $jsonContent = json_encode($items, JSON_PRETTY_PRINT);

            // Write the JSON content to the file
            if (file_put_contents($filepath, $jsonContent) !== false) {
                echo "File created successfully for vno: $vno at $filepath<br>";
            } else {
                echo "Error writing file for vno: $vno<br>";
            }
        }
	}
}