<?php 
header("Content-type: application/json; charset=utf-8");
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit','-1');
ini_set('post_max_size','500M');
ini_set('upload_max_filesize','500M');
ini_set('max_execution_time',36000);
header("Access-Control-Allow-Origin: *");
class Api01 extends CI_Controller {	
	
	public function my_order_api(){
		$get_record	 	= $_REQUEST["get_record"];

		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		
		if($user_type!="" && $user_altercode!="" && $get_record!="")
		{
			$result = $this->Chemist_Model->my_order_json_50($user_type,$user_altercode,$salesman_id,$get_record);

			$items  	= $result["items"];
			$title  	= $result["title"];
			$get_record = $result["get_record"];
		}
?>
{"get_result":[{"items":[<?= $items;?>]},{"title":"<?= $title;?>"},{"get_record":"<?= $get_record;?>"}]}<?php
	}
	
	public function my_order_details_api(){

		$item_id		= $_POST['item_id'];

		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		
		if($user_type!="" && $user_altercode!="" && $item_id!="")
		{
			$items = $this->Chemist_Model->my_order_details_api($user_type,$user_altercode,$salesman_id,$item_id);
		}
?>
{"items":[<?= $items;?>]}<?php
	}
	
	public function my_invoice_api(){
		$get_record	 	= $_REQUEST["get_record"];

		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		
		if($user_type!="" && $user_altercode!="" && $get_record!="")
		{
			$result = $this->Chemist_Model->my_invoice_json_50($user_type,$user_altercode,$salesman_id,$get_record);

			$items  	= $result["items"];
			$title  	= $result["title"];
			$get_record = $result["get_record"];
		}
?>
{"get_result":[{"items":[<?= $items;?>]},{"title":"<?= $title;?>"},{"get_record":"<?= $get_record;?>"}]}<?php
	}
	
	public function my_invoice_details_api(){
		$item_id	 	= $_REQUEST["item_id"];

		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		
		$items 			= "";
		$delete_items	= "";
		$download_url 	= "";
		if($user_type!="" && $user_altercode!="" && $item_id!="")
		{
			$val = $this->Chemist_Model->my_invoice_details_api($user_type,$user_altercode,$salesman_id,$item_id);

			$items			= $val[0];
			$edit_items 	= $val[4];
			$delete_items 	= $val[1];
			$download_url 	= $val[2];
			$header_title 	= $val[3];
		}
?>
{"items":[<?= $items;?>],"edit_items":[<?= $edit_items;?>],"delete_items":[<?= $delete_items;?>],"download_url":[<?= $download_url;?>],"header_title":[<?= $header_title;?>]}<?php
	}
	
	public function my_notification_api(){
		$get_record	 	= $_REQUEST["get_record"];

		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		
		if($user_type!="" && $user_altercode!="" && $get_record!="")
		{
			$result = $this->Chemist_Model->my_notification_json_50($user_type,$user_altercode,$salesman_id,$get_record);

			$items  	= $result["items"];
			$title  	= $result["title"];
			$get_record  = $result["get_record"];
		}
?>
{"get_result":[{"items":[<?= $items;?>]},{"title":"<?= $title;?>"},{"get_record":"<?= $get_record;?>"}]}<?php
	}
	
	public function my_notification_details_api(){
		$item_id		= $_REQUEST['item_id'];

		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		
		if($user_type!="" && $user_altercode!="" && $item_id!="")
		{			
			$items = $this->Chemist_Model->my_notification_details_api($user_type,$user_altercode,$salesman_id,$item_id);
		}		
?>
{"items":[<?= $items;?>]}<?php
	}
	
	public function medicine_add_to_cart_api()
	{
		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		
		$item_code				= $_REQUEST["item_code"];
		$item_order_quantity	= $_REQUEST["item_order_quantity"];
		
		$order_type 	= "pc_mobile";
		$mobilenumber 	= "";
		$modalnumber 	= "PC / Laptop";
		$device_id 		= "";

		if($user_type!="" && $user_altercode!="" && $item_code!=""){
			$excel_number = "";		
			$status = $this->Chemist_Model->medicine_add_to_cart_api($user_type,$user_altercode,$salesman_id,$order_type,$item_code,$item_order_quantity,$mobilenumber,$modalnumber,$device_id,$excel_number);
			
			/*****************************************************/
			$val = $this->Order_Model->my_cart_json_50($user_type,$user_altercode,$user_password,$salesman_id,"all");
			$items1 = $val[0];
			$items2 = $val[1];
			$user_cart_total = $val[2];
			
			//setcookie("user_cart_total", $user_cart_total, time() + (86400 * 30), "/");
		}

$items= <<<EOD
{"status":"{$status}","user_cart_total":"{$user_cart_total}"},
EOD;
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
{"items":[<?= $items;?>],"items1":[<?= $items1;?>],"items2":[<?= $items2;?>]}<?php
	}
	
	public function medicine_details_api()
	{
		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}

		$item_code 		= $_POST["item_code"];
		if($user_type!="" && $user_altercode!="" && $item_code!=""){
			$items = $this->Chemist_Model->medicine_details_api($user_type,$user_altercode,$salesman_id,$item_code);
		}
		
?>
{"items":[<?= $items;?>]}<?php
	}
	
	public function home_page_web()
	{
		$get_record	 	= "0";//$_REQUEST["get_record"];

		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}

		$session_yes_no = "no";
		if(!empty($user_altercode)){
			$session_yes_no = "yes";
		}
		
		$my_notification = "";
		if($user_type!="" && $user_altercode!="" && $get_record!="")
		{
			$result = $this->Chemist_Model->my_notification_json_50($user_type,$user_altercode,$salesman_id,$get_record);

			$my_notification  = $result["items"];
		}
		$my_notification = '{"my_notification":['.$my_notification.']}';

		/****************************************************** */
		$my_invoice = "";
		if($user_type!="" && $user_altercode!="" && $get_record!="")
		{
			$result = $this->Chemist_Model->my_invoice_json_50($user_type,$user_altercode,$salesman_id,$get_record);

			$my_invoice  = $result["items"];
		}
		$my_invoice = '{"my_invoice":['.$my_invoice.']}';
		
		$items = "";
		$tbl_home = $this->db->query("select * from tbl_home where status=1 order by seq_id asc")->result();
		foreach($tbl_home as $row){
			$category_id = $row->category_id;
			
			$result_row = "[]";
			if($row->type=="slider"){
			    if($row->category_id=="1"){
			        $top_flash = $this->Chemist_Model->top_flash();
			    }
			    if($row->category_id=="2"){
			        $top_flash = $this->Chemist_Model->top_flash2();
			    }
		        $result_row = '['.$top_flash.']';
			}
			
			if($row->type=="divisioncategory"){
			    $result = $this->Chemist_Model->medicine_division_wise_json_50($category_id);
				
				$row_title  = $result["title"];
		        $result_row     = '['.$result["items"].']';
			}
			
			
			if($row->type=="itemcategory"){
				$result = $this->Chemist_Model->medicine_item_wise_json_50($session_yes_no,$category_id,$user_type,$user_altercode,$salesman_id);

				$row_title  = $result["title"];
		        $result_row     = '['.$result["items"].']';
			}
            
            $items.= '{"result":"'.$row->type.'","result_id":"'.$row->id.'","result_category_id":"'.$row->category_id.'","result_title":"'.$row_title.'","result_row":'.$result_row.'},';
		}
		if ($items != '') {
			$items = substr($items, 0, -1);
		}
		
		/****************************************************** */
		echo $get_result = '{"get_result":['.$items.','.$my_notification.','.$my_invoice.']}'; 
	}
	
	public function my_cart_api(){
			
		$items = "";
		
		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		
		$other_items = "";
		if($user_altercode!="")
		{
			$val = $this->Order_Model->my_cart_json_50($user_type,$user_altercode,$user_password,$salesman_id,"all");
			$items = $val[0];
			$other_items = $val[1];
			$user_cart_total = $val[2];

			//setcookie("user_cart_total", $user_cart_total, time() + (86400 * 30), "/");
		}

?>
{"items":[<?= $items;?>],"other_items":[<?= $other_items;?>]}<?php
	}
	
	public function delete_medicine_api(){

		$items = "";
		
		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		
		$item_code 	= $_POST["item_code"];
		
		if($user_type!="" && $user_altercode!="" && $item_code!=""){
			$status = $this->Chemist_Model->delete_medicine_api($user_type,$user_altercode,$salesman_id,$item_code);
		}

$items= <<<EOD
{"status":"{$status}"},
EOD;
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
{"items":[<?= $items;?>]}<?php
	}
	
	public function delete_all_medicine_api(){
		
		$items = "";
		
		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		
		if($user_type!="" && $user_altercode!=""){
			$status = $this->Chemist_Model->delete_all_medicine_api($user_type,$user_altercode,$salesman_id);
		}

$items= <<<EOD
{"status":"{$status}"},
EOD;
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
{"items":[<?= $items;?>]}<?php
	}
	
	public function medicines_last_order_api(){

		$items = "";
		
		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		
		/*$result = $this->db->query("select DISTINCT image,item_name,id,i_code,quantity, COUNT(*) as ct FROM tbl_order where chemist_id='$user_altercode' and user_type='chemist' GROUP BY item_name HAVING COUNT(*) > 0 order by ct asc limit 10")->result();*/
		$result = $this->db->query("select DISTINCT i_code,image,item_name,quantity,COUNT(*) as ct FROM tbl_order where chemist_id='$user_altercode' and user_type='chemist' GROUP BY i_code,image,item_name,quantity HAVING COUNT(*) > 0 order by ct asc limit 10")->result();
		foreach($result as $row)
		{
			$item_code 		= ($row->i_code);
			$item_name 		= ucwords(strtolower($row->item_name));
			$quantity 		= ($row->quantity);
			$item_image 	= ($row->image);
			
$items.= <<<EOD
{"item_code":"{$item_code}","item_name":"{$item_name}","quantity":"{$quantity}","item_image":"{$item_image}"},
EOD;
        }
if ($items != '') {
	$items = substr($items, 0, -1);
}
?>
{"items":[<?= $items;?>]}<?php
    }
	
	public function medicine_search_api_51()
	{
		$items = "";
		$keyword   				= $_REQUEST['keyword'];
		$total_rec   			= $_REQUEST['total_rec'];
		$checkbox_medicine 		= $_REQUEST['checkbox_medicine_val'];
		$checkbox_company		= $_REQUEST['checkbox_company_val'];
		$checkbox_out_of_stock	= $_REQUEST['checkbox_out_of_stock_val'];
		$user_nrx  				= $_REQUEST["user_nrx"];
		if(!empty($keyword))
		{
			$items = $this->Chemist_Model->medicine_search_api_51($keyword,"","",$user_nrx,$total_rec,$checkbox_medicine,$checkbox_company,$checkbox_out_of_stock);
		}
?>
{"items":[<?= $items;?>]}<?php
	}

	public function get_medicine_use($item_code=""){
		if($_POST["item_code"]){
			$item_code = $_POST["item_code"];
		}
		$items = "";
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

$items.= <<<EOD
{"file":"{$file}","file_type":"{$file_type}"},
EOD;
		}
		
		/*********************************************/
		$user_type 		= $_POST["user_type"];
		$user_altercode = $_POST["user_altercode"];
		$user_password	= $_POST["user_password"];

		$chemist_id 	= $_POST["chemist_id"];

		$salesman_id = "";
		if($user_type=="sales")
		{
			$salesman_id 	= $user_altercode;
			$user_altercode = $chemist_id;
		}
		/*********************************************/
		if($item_code!=""){
			$medicine_details = $this->Chemist_Model->medicine_details_api($user_type,$user_altercode,$salesman_id,$item_code);
		}
		
		if ($items != '') {
			$items = substr($items, 0, -1);
		}?>
{"result":[{"medicine_details":[<?php echo $medicine_details ?>]},{"medicine_use":[<?php echo $items ?>]}]}
	<?php
	}
}