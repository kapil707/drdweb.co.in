<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankProcessingModel extends CI_Model  
{
	public function __construct(){

		parent::__construct();
		$this->load->model("model-bank/BankModel");
	}

	public function get_processing(){
		
		$result = $this->BankModel->select_query("select * from tbl_bank_processing where process_status='0' limit 25");
		$result = $result->result();
		foreach($result as $row){

			echo $from_text = $row->from_text;

			$find_by = "";
			$find_chemist_id = "";
			$process_value = "";
			$process_name = "";

			if(!empty($from_text)){
				$result = $this->find_by_full_name($from_text);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name-done";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$result = $this->find_by_name($from_text);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = str_replace(' ', '%', $from_text);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist name1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			/************************************************* */
			if(empty($find_chemist_id)){
				$splitValues = explode('@', $from_text);
				$before_at = $splitValues[0];
				$result = $this->find_by_name($before_at);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $from_text);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -1);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $from_text);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -2);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 2";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $from_text);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -3);
				$result = $this->find_by_name($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 3";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$splitValues = explode('@', $from_text);
				$before_at = $splitValues[0];
				$newString = substr($before_at, 0, -4);
				$result = $this->find_by_name($before_at);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist remove @ 4";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			/************************************************* */
			if(empty($find_chemist_id)){
				$newString = substr($from_text, 0, -1);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table1";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($from_text, 0, -2);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table2";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($from_text, 0, -3);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table3";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			if(empty($find_chemist_id)){
				$newString = substr($from_text, 0, -4);
				$result = $this->find_by_title($newString);
				$find_chemist_id = $result["find_chemist_id"];
				$find_by = "Chemist Table4";
				$process_value = $result["process_value"];
				$process_name = $result["process_name"];
			}

			/************************************************* */
			if(empty($find_chemist_id)){
				$pattern = '/(\d{10})/';
				preg_match($pattern, $from_text, $matches);
				if (isset($matches[1])) {
					$result = $this->find_by_title($matches[1]);
					$find_chemist_id = $result["find_chemist_id"];
					$find_by = "Chemist mobile";
					$process_value = $result["process_value"];
					$process_name = $result["process_name"];
				}
			}

			if(empty($find_chemist_id)){
				$pattern = '/(\d{10})/';
				preg_match($pattern, $from_text, $matches);
				if (isset($matches[1])) {
					$result = $this->find_by_acm_tbl($matches[1]);
					$find_chemist_id = $result["find_chemist_id"];
					$find_by = "Acm mobile";
					$process_value = $result["process_value"];
					$process_name = $result["process_name"];
				}
			}
			echo $find_by;

			

			/************************************************* */
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'process_status'=>1,				
				'from_text_find'=>$process_value,
				'from_text_find_match'=>$process_name,
				'from_text_find_chemist'=>$find_chemist_id,
			);
			$this->BankModel->edit_fun("tbl_bank_processing", $dt,$where);
			/************************************************* */
		}
	}

	/*****************************************************************************/
	function find_by_full_name($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";
		
		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` = '$received_from'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
		}

		/*$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` = '$received_from'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$find_chemist_id = trim($tt->chemist_id);
			$process_value = $tt->string_value;
		}*/

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}

	function find_by_name($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";

		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
		}

		/*$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$find_chemist_id = trim($tt->chemist_id);
			$process_value = $tt->string_value;
		}*/

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}

	function find_by_title($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";

		$received_from = str_replace(' ', '', $received_from);
		
		$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `title` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->chemist_id;
			$process_value = $tt->string_value;
			//$chemist_id = $tt->chemist_id;
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
		}

		/*$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `title` LIKE '%$received_from%'");
		$rr = $rr->result();
		foreach($rr as $tt){
			$find_chemist_id = trim($tt->chemist_id);
			$process_value = $tt->string_value;
		}*/

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}

	function find_by_acm_tbl($received_from){

		$jsonArray = array();

		$find_chemist_id = "";
		$process_name = $received_from;
		$process_value = "";

		$rr = $this->db->query("SELECT * FROM `tbl_chemist` WHERE `telephone` like '%$received_from%' ");
		$rr = $rr->result();
		foreach($rr as $tt){
			$jsonArray[] = $tt->altercode;
			$process_value = $tt->telephone;
			//$chemist_id = $tt->chemist_id;
		}

		if(empty($process_value)){
			$rr = $this->db->query("SELECT * FROM `tbl_chemist` WHERE `telephone1` like '%$received_from%' ");
			$rr = $rr->result();
			foreach($rr as $tt){
				$jsonArray[] = $tt->altercode;
				$process_value = $tt->telephone1;
				//$chemist_id = $tt->chemist_id;
			}
		}

		if(empty($process_value)){
			$rr = $this->db->query("SELECT * FROM `tbl_chemist` WHERE `mobile` like '%$received_from%' ");
			$rr = $rr->result();
			foreach($rr as $tt){
				$jsonArray[] = $tt->altercode;
				$process_value = $tt->mobile;
				//$chemist_id = $tt->chemist_id;
			}
		}

		if(!empty($jsonArray)){
			$find_chemist_id = implode('||', $jsonArray);
		}

		$return["find_chemist_id"] = $find_chemist_id;
		$return["process_name"] = $process_name;
		$return["process_value"] = $process_value;

		return $return;
	}
}	