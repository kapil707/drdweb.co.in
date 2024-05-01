<div class="row">
	<div class="col-xs-12">
		<button type="button" class="btn btn-w-m btn-info" onclick="goBack();"><< Back</button>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
			<div class="form-group">			
           		<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            File
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="form-field-1" placeholder="Name" name="myfile" value="<?= set_value('myfile'); ?>" required="required" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">  
                            <?= form_error('name'); ?>
                        </span>
                    </div>
                </div>
          	</div>               
			<div class="form-group">
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Status
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <select name="status" id="status" data-placeholder="Select Status" class="chosen-select" >
							<option value="1" <?php if(set_value('status')==1) { ?> selected <?php } ?>>
								Active
							</option>
							<option value="0" <?php if(set_value('status')==0) { ?> selected <?php } ?>>
								Inactive
							</option>
						</select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">  
                            <?= form_error('status'); ?>
                        </span>
                    </div>
                </div>
			</div>
            <div class="space-4"></div>
            <br /><br />
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn btn-info submit_button" name="Submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>
                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Reset
                    </button>
                </div>
            </div>
        </form>
        <!-- PAGE CONTENT ENDS -->
		<?php

		/*$chemist = "B";
		$itemname = "C";
		$filename1 = "kapilji.xlsx";
		$upload_path = "./uploads/";
		$excelFile = $upload_path.$filename1;
		$rows = array();
		if(file_exists($excelFile))
		{
			$this->load->library('excel');
			$objPHPExcel = PHPExcel_IOFactory::load($excelFile);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				for ($row=2; $row<=$highestRow; $row++)
				{
					$itemname1 = $worksheet->getCell($itemname.$row)->getValue();
					$chemist1 = $worksheet->getCell($chemist.$row)->getValue();
					//$rows[$chemist1] = $itemname1;

					$dt = array(
						'string_value'=>$itemname1,
						'chemist_id'=>$chemist1,
					);
					//$this->Scheme_Model->insert_fun("tbl_upload_sms",$dt);
					$this->BankModel->insert_fun("tbl_chemist", $dt);
				}
			}
		}*/
		//print_r($rows);

		/*foreach($rows as $mydt){
			echo $mydt;
			echo "<br>";
		}*/

		$account_no 			= "A";
		$branch_no 				= "B";
		$statment_date 			= "C";
		$amount 				= "F";
		$enter_date 			= "G";
		$value_date 			= "H";
		$bank_reference 		= "I";
		$customer_reference 	= "J";
		$narrative 				= "K";
		$transaction_description= "L";

		$start_row = "13";

		$upload_path = "uploads/manage_bank/myfile/";
		$excelFile = $upload_path.$filename;
		$i=1;
		if(file_exists($excelFile))
		{
			$this->load->library('excel');
			$objPHPExcel = PHPExcel_IOFactory::load($excelFile);
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
			{
				$highestRow = $worksheet->getHighestRow();
				for ($row=$start_row; $row<=$highestRow; $row++)
				{
					$amount1 = $worksheet->getCell($amount.$row)->getValue();
					$statment_date1 = $worksheet->getCell($statment_date.$row)->getValue();
					$text = $worksheet->getCell($narrative.$row)->getValue();
					$text = trim($text);
					//$text = str_replace("'", "", $text);
					//$text = "+91-9899067942 411801191476 FROM GUPTAMEDICALSTORE 9300966180 CITI0000 9026 NA UBIN0579203";

					$transaction_description1 = $worksheet->getCell($transaction_description.$row)->getValue();
					
					//$mydate = date('Y-m-d', strtotime($statment_date1));
					$start_date = date('Y-m-d', strtotime($statment_date1 . ' -2 day'));
					$end_date = date('Y-m-d', strtotime($statment_date1 . ' -1 day'));
					

					echo $i.". ";
					$i++;
					echo $text;
					//$text = str_replace("@ ", "@", $text);
					//echo $text = preg_replace('/@\s/', "@", $text, 1);

					$received_from = "";
					// Use regular expression to extract text after "FROM"
					/*preg_match("/FROM\s+(\d+)@\s+(\w+)/", $text, $matches);
					if (!empty($matches)){
						$received_from = trim($matches[1])."@".trim($matches[2]);
						$received_from = str_replace("'", "", $received_from);
						$received_from = str_replace(" ", "", $received_from);
						$received_from = str_replace("\n", "", $received_from);
						echo "<b>find: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
					}

					
					preg_match("/FROM\s+(\d+)\s+@\s*(\w+)/", $text, $matches);
					if (!empty($matches)){
						$received_from = trim($matches[1])."@".trim($matches[2]);
						$received_from = str_replace("'", "", $received_from);
						$received_from = str_replace(" ", "", $received_from);
						$received_from = str_replace("\n", "", $received_from);
						echo "<b>find2: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
					}

					preg_match("/FROM\s+(\w+)\d+@\s*(\w+)/", $text, $matches);
					if (!empty($matches)){
						$received_from = trim($matches[1])."@".trim($matches[2]);
						$received_from = str_replace("'", "", $received_from);
						$received_from = str_replace(" ", "", $received_from);
						$received_from = str_replace("\n", "", $received_from);
						echo "<b>find3: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
					}

					preg_match("/FROM\s+([^\s@]+)\s+@\s*(\w+)/", $text, $matches);
					if (!empty($matches)){
						$received_from = trim($matches[1])."@".trim($matches[2]);
						$received_from = str_replace("'", "", $received_from);
						$received_from = str_replace(" ", "", $received_from);
						$received_from = str_replace("\n", "", $received_from);
						echo "<b>find4: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
					}

					preg_match("/FROM\s+([^\@]+)@\s*(\w+)/", $text, $matches);
					if (!empty($matches)){
						$received_from = trim($matches[1])."@".trim($matches[2]);
						$received_from = str_replace("'", "", $received_from);
						$received_from = str_replace(" ", "", $received_from);
						$received_from = str_replace("\n", "", $received_from);
						echo "<b>find5: ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
					}*/

					preg_match("/FROM\s+(.*?)\s+CITI0000/", $text, $matches);
					if (!empty($matches)){
						$received_from = trim($matches[1]);
						$received_from = str_replace("'", "", $received_from);
						$received_from = str_replace(" ", "", $received_from);
						$received_from = str_replace("\n", "", $received_from);
						echo "<b>find6:  ".$received_from."</b>"; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
					} else{
						echo "not find 6";
					}

					$chmist_id = "";
					// if(!empty($received_from)){
					// 	$rr = $this->BankModel->select_query("SELECT * FROM `tbl_bank_chemist` WHERE `string_value` LIKE '%$received_from%'");
					// 	$rr = $rr->result();
					// 	foreach($rr as $tt){
					// 		$chmist_id = $tt->chemist_id;
					// 		echo "<b>---chemist tbl---".$tt->chemist_id."</b>";
					// 	}
					// }

					// if(empty($chmist_id)){
					// 	$rr = $this->InvoiceModel->select_query("select * from tbl_invoice_new where amt='$amount1' and (vdt BETWEEN '$start_date' and '$end_date')");
					// 	$rr = $rr->result();
					// 	foreach($rr as $tt){
					// 		echo "---with invoice---".$tt->chemist_id;
					// 		echo ",";
					// 	}
					// }

					echo "<br><br>";

					/*************************** */					
				}
			}
		}
		?>
    </div><!-- /.col -->
</div><!-- /.row -->
<script>
function url_change()
{
	name = $(".name1").val();
	name = name.replace(/&/g,'and');
	name = name.trim(name).replace(/ /g,'-');
	name = encodeURI(name).replace(/[!\/\\#,+()$~%.'":*?<>{}]/g,'');
	$(".url1").html(name)
	$(".url").val(name)
	a_href_change(name)
}
function a_href_change(name)
{
	document.getElementById("url1").href= "<?= base_url(); ?>products/"+name+".html"; 
}
var error2 = 1;
function change_url()
{
	error2 = 0;
	disabled_submit_button();
	$('.url_error').html("");
	url1 = $('.url').val();
	name = url1;
	name = name.replace(/&/g,'and');
	name = name.trim(name).replace(/ /g,'-');
	name = encodeURI(name).replace(/[!\/\\#,+()$~%.'":*?<>{}]/g,'');
	$(".url1").html(name)
	$(".url").val(name)
	a_href_change(name)
	$.ajax({
	type       : "POST",
	data       :  { url1 : url1,id : '<?= $row->id; ?>',} ,
	url        : "<?= base_url()?>admin/<?= $Page_name?>/change_url",
	success    : function(data){
			if(data!="")
			{
				//java_alert_function("success","Delete Successfully");
				//$('.product_code_error').html("This Product SKU Already Taken");
				if(data=="Error")
				{
					java_alert_function("error","This Product Url Already Taken")
					$('.url_error').html("This Product Url Already Taken");
				}
				if(data=="ok")
				{
					java_alert_function("success","Product Url Ok");
					$('.url_error').html("Product Url Ok");
					error2 = 1;
					disabled_submit_button();
				}
			}					
			else
			{
				java_alert_function("error","Something Wrong")
				$('.url_error').html("Something Wrong");
			}
		}
	});
}
function disabled_submit_button()
{
	if(error2==1)
	{
		$(".submit_button").prop('disabled', false);
	}
	else
	{
		$(".submit_button").prop("disabled", true);
	}
}
</script>