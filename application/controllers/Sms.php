<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sms extends CI_Controller {		
	public function index()	{	
		$this->load->view('sms');
	}

	public function split_function(){

		$result = $this->db->query("select * from tbl_upload_sms where status='0' limit 100")->result();
		foreach($result as $row){
			$message_body = $row->message_body;
			
			$pattern = '/INR (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$amount = $matches[1];
			} else {
				$amount = "Amount not found";
			}

			$pattern = '/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/';
			if (preg_match($pattern, $message_body, $matches)) {
				$getdate = $matches[1];
			} else {
				$getdate = "Date not found";
			}

			$pattern = '/received from (\S+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$received_from = $matches[1];
				$status = 1;
			} else {
				$received_from = "Received from information not found";
				$status = 2;
			}

			$pattern = '/UPI Ref No\. (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$upi_no = $matches[1];
			} else {
				$upi_no = "UPI reference number not found";
			}

			$pattern = '/OrderId (\w+)/';
			if (preg_match($pattern, $message_body, $matches)) {
				$orderid = $matches[1];
			} else {
				$orderid = "orderid not found";
			}
			
			$id = $row->id;
			$where = array('id'=>$id);
			$dt = array(
				'status'=>$status,
				'amount'=>$amount,
				'getdate'=>$getdate,
				'received_from'=>$received_from,
				'upi_no'=>$upi_no,
				'orderid'=>$orderid,
			);
			$this->Scheme_Model->edit_fun("tbl_upload_sms",$dt,$where);
		}
	}

	public function split_function2(){

		$result = $this->db->query("select * from tbl_upload_sms where status='1' and id='20' limit 100")->result();

		$chemist = "D";
		$itemname = "E";
		$filename = "kapilji.xlsx";
		$upload_path = "./uploads/";
		$excelFile = $upload_path.$filename;
		if(file_exists($excelFile))
		{
			$this->load->library('excel');
			$objPHPExcel = PHPExcel_IOFactory::load($excelFile);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
			{
				foreach($result as $row1){
					$received_from = strtolower($row1->received_from);

					$chemist_id = "Record not found";
					$highestRow = $worksheet->getHighestRow();
					for ($row=2; $row<=$highestRow; $row++)
					{
						$string = $worksheet->getCell($itemname.$row)->getValue();
						$string = strtolower($string);
						
						$pattern = '/\b' . preg_quote($received_from, '/') . '\b/';

						if (preg_match($pattern, $string)) {
							$chemist_id = $worksheet->getCell($chemist.$row)->getValue();
						}
					}
					echo $chemist_id;
				}
			}
		}
		
	}

	public function split_function3(){

		$itemname = "E";
		$filename = "kapilji.xlsx";
		$upload_path = "./uploads/";
		$excelFile = $upload_path.$filename;
		if(file_exists($excelFile))
		{
			$this->load->library('excel');
			$objPHPExcel = PHPExcel_IOFactory::load($excelFile);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
			{

				$highestRow = $worksheet->getHighestRow();
				for ($row=2; $row<=$highestRow; $row++)
				{
					echo $string = $worksheet->getCell($itemname.$row)->getValue();
					echo "<br>";
				}
			}
		}		
	}
}