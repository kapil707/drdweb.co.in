<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BankModel extends CI_Model  
{
	public function add_whatsapp_messages($data) {
        // Check if the message already exists
		$db_bank = $this->load->database('bank_db', TRUE);

        $db_bank->where('message_id', $data['message_id']);
        $query = $db_bank->get('tbl_whatsapp_message');
        if ($query->num_rows() == 0) {
            // Insert new message
            return $db_bank->insert('tbl_whatsapp_message', $data);
        } else {
            return false; // Duplicate entry
        }
    }

	function select_query($query)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		return $db_bank->query($query);	
	}

	function select_fun($tbl,$where)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($where!="")
		{
			$db_bank->where($where);
		}
		return $db_bank->get($tbl);	
	}
	function insert_fun($tbl,$dt)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($db_bank->insert($tbl,$dt))
		{
			return $db_bank->insert_id();
		}
		else
		{
			return false;
		}
	}
	function edit_fun($tbl,$dt,$where)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($db_bank->update($tbl,$dt,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function delete_fun($tbl,$where)
	{
		$db_bank = $this->load->database('bank_db', TRUE);
		if($db_bank->delete($tbl,$where))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function select_fun_limit($tbl,$where,$get_limit='',$order_by='')
	{
		$db_bank = $this->load->database('db_bank', TRUE);
		if(!empty($where))
		{
			$db_bank->where($where);
		}
		if(!empty($order_by))
		{
			$db_bank->order_by($order_by[0],$order_by[1]);
		}
		if(!empty($get_limit))
		{
			$db_bank->limit($get_limit[0],$get_limit[1]);
		}
		return $db_bank->get($tbl);	
	}

	public function statment_excel_file($download_type)
	{	
		error_reporting(0);
		
		$this->load->library('excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		
		ob_clean();		

		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A10','Account Number')
		->setCellValue('B10','Branch Number')
		->setCellValue('C10','Statement Date')
		->setCellValue('D10','Closing Ledger Balance')
		->setCellValue('E10','Calculated Balances')
		->setCellValue('F10','Amount')
		->setCellValue('G10','Entry Date')
		->setCellValue('H10','Value Date')
		->setCellValue('I10','Bank Reference')
		->setCellValue('J10','Customer Reference')
		->setCellValue('K10','Narrative')
		->setCellValue('L10','Transaction Description')
		->setCellValue('M10','IBAN Number')
		->setCellValue('N10','Chemist Id');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray(array('font' => array('size' =>10,'bold' => TRUE,'name'  => 'Arial','color' => ['rgb' => '800000'],)));
		
		$objPHPExcel->getActiveSheet()
        ->getStyle('A1:N1')
        ->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()
        ->setRGB('ccffff');
		
		$BStyle = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		  )
		);
		$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($BStyle);
		
		
		$start_date = "2024-05-31";
		$end_date = "2024-05-31";

		$query = $this->BankModel->select_query("SELECT s.*,p.done_chemist_id as chemist_id from tbl_statment as s left JOIN tbl_bank_processing as p on p._id=s.id where p.type='Statment' and s.value_date BETWEEN '$start_date' AND '$end_date'");
		$result = $query->result();
		$rowCount = 11;
		$fileok=0;
		foreach($result as $row)
		{
			$fileok=1;
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,$account_no);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,$branch_no);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,$statment_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,$row->closing_ledger_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,$row->calculated_balances);
			//$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,(int)$row->amount);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,$row->amount);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,$row->enter_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount,$row->value_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount,$row->bank_reference);
			$objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount,$row->customer_reference);
			$objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount,$row->narrative);
			$objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount,$row->transaction_description);
			$objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount,$row->iban_number);
			$objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount,$row->chemist_id);
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount.':N'.$rowCount)->applyFromArray($BStyle);
			$rowCount++;
		}
		
		$name = $gstvno;
		if($download_type=="direct_download")
		{
			$file_name = $name.".xls";
			
			//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
			/*$objWriter->save('uploads_sales/kapilkifile.xls');*/
			
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename='.$file_name);
			header('Cache-Control: max-age=0');
			ob_start();
			$objWriter->save('php://output');
			$data = ob_get_contents();
		}
		
		if($download_type=="cronjob_download")
		{
			if($fileok==1)
			{
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
				$file_name = "test_folder/".$name.".xls";
				$objWriter->save($file_name);
				
				$file_name2 = "test_folder/xx".$name.".xls";
				$objWriter->save($file_name2);
				
				$x[0] = $file_name;
				$x[1] = $invoice_message_body;
 				return $x;
			}
			else
			{
				$file_name = "";
				return $file_name;
			}
		}
	}
}	