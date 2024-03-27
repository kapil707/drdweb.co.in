<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class StockDifferentModel extends CI_Model  
{
	public $db_medicine;
	public function __construct(){

		parent::__construct();

		$this->db_medicine = $this->load->database('default2', TRUE);
	}
	
	function copy_records()
	{
		$db_medicine = $this->db_medicine;
		
		$db_medicine->query("TRUNCATE TABLE tbl_medicine_compare");

		$db_medicine->query("INSERT tbl_medicine_compare (i_code,item_code,item_name,batchqty,mrp,salescm1,salescm2) SELECT i_code,item_code,item_name,batchqty,mrp,salescm1,salescm2 FROM tbl_medicine");
		
		$date = date("Y-m-d");
		$db_medicine->query("update tbl_medicine_compare set date='$date'");
		echo "Data Copy Successfully";
	}
	
	function check_different_qty()
	{
		$db_medicine = $this->db_medicine;
		
		$date = date("Y-m-d");
		$type = "batchqty";
		$result = $db_medicine->query("SELECT tbl_medicine_compare.i_code, tbl_medicine_compare.batchqty AS deff1, tbl_medicine.batchqty AS deff2 FROM tbl_medicine_compare JOIN tbl_medicine ON tbl_medicine_compare.id = tbl_medicine.id WHERE (tbl_medicine_compare.batchqty <> tbl_medicine.batchqty and tbl_medicine_compare.batchqty=0)")->result();
		foreach($result as $row){
			
			$i_code = $row->i_code;
			$deff1  = $row->deff1;
			$deff2  = $row->deff2;
			$row1 = $db_medicine->query("select id from tbl_medicine_compare_final where i_code='$i_code' and deff1='$deff1' and deff2='$deff2'")->row();
			if(empty($row1->id))
			{
				$db_medicine->query("INSERT tbl_medicine_compare_final (i_code,date,type,deff1,deff2) values ('$i_code','$date','$type','$deff1','$deff2')");
			}
		}
	}
	
	function check_different_mrp()
	{
		$db_medicine = $this->db_medicine;
		
		$date = date("Y-m-d");
		$type = "mrp";
		$result = $db_medicine->query("SELECT tbl_medicine_compare.i_code, tbl_medicine_compare.mrp AS deff1, tbl_medicine.mrp AS deff2 FROM tbl_medicine_compare JOIN tbl_medicine ON tbl_medicine_compare.id = tbl_medicine.id WHERE (tbl_medicine_compare.mrp > tbl_medicine.mrp);")->result();
		foreach($result as $row){
			
			$i_code = $row->i_code;
			$deff1  = $row->deff1;
			$deff2  = $row->deff2;
			$row1 = $db_medicine->query("select id from tbl_medicine_compare_final where i_code='$i_code' and deff1='$deff1' and deff2='$deff2'")->row();
			if(empty($row1->id))
			{
				$db_medicine->query("INSERT tbl_medicine_compare_final (i_code,date,type,deff1,deff2) values ('$i_code','$date','$type','$deff1','$deff2')");
			}
		}
	}

	function check_different_scheme()
	{
		$db_medicine = $this->db_medicine;
		
		$date = date("Y-m-d");
		$type = "scheme";
		$result = $db_medicine->query("SELECT tbl_medicine_compare.i_code, tbl_medicine_compare.mrp AS deff1, tbl_medicine.mrp AS deff2 FROM tbl_medicine_compare JOIN tbl_medicine ON tbl_medicine_compare.id = tbl_medicine.id WHERE (tbl_medicine_compare.salescm1 < tbl_medicine.salescm1)")->result();
		foreach($result as $row){
			
			$i_code = $row->i_code;
			$deff1  = $row->deff1;
			$deff2  = $row->deff2;
			$row1 = $db_medicine->query("select id from tbl_medicine_compare_final where i_code='$i_code' and deff1='$deff1' and deff2='$deff2'")->row();
			if(empty($row1->id))
			{
				$db_medicine->query("INSERT tbl_medicine_compare_final (i_code,date,type,deff1,deff2) values ('$i_code','$date','$type','$deff1','$deff2')");
			}
		}
	}
	
	function send_report_on_whatsapp()
	{
		$db_medicine = $this->db_medicine;
		
		$date = date("Y-m-d");
		
		$items = "";
		$result = $db_medicine->query("select tbl_medicine_compare_final.i_code,tbl_medicine.item_name,deff1,deff2 from tbl_medicine_compare_final right join tbl_medicine on tbl_medicine.i_code = tbl_medicine_compare_final.i_code  where tbl_medicine_compare_final.date='$date' and type='batchqty'")->result();
		$i = 0;
		foreach($result as $row){
			$i++;
			$i_code 	= $row->i_code;
			$item_name 	= $row->item_name;
			$deff1 		= $row->deff1;
			$deff2 		= $row->deff2;
			
			$items.="$i. $item_name ($i_code) :- Y. $deff1 / N. $deff2 \\n";
		}
		return $items;
	}
}