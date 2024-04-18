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

$text = "UPI CREDIT REFERENCE 446604081862 FROM 97926121865@PAYTM SAMEER S O KALLU NA";

// Use regular expression to extract text after "FROM"
if (preg_match('/FROM\s+([^@]+)/', $text, $matches)) {
    $from_text = trim($matches[1]);
    echo $from_text; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
}


		$chemist = "B";
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
					$rows[$chemist1] = $itemname1;
				}
			}
		}
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
		if(file_exists($excelFile))
		{
			echo "working";
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
					$transaction_description1 = $worksheet->getCell($transaction_description.$row)->getValue();
					
					//$mydate = date('Y-m-d', strtotime($statment_date1));
					$start_date = date('Y-m-d', strtotime($statment_date1 . ' -2 day'));
					$end_date = date('Y-m-d', strtotime($statment_date1 . ' -1 day'));
					

					$text = str_replace("@ ", "@", $text);
					echo $text = preg_replace('/@\s/', "@", $text, 1);
					//echo $text = strtolower($text);

					//echo "select * from tbl_invoice_new where amt='$amount1' and (vdt BETWEEN $start_date and $end_date)";

					/*$rr = $this->InvoiceModel->select_query("select * from tbl_invoice_new where amt='$amount1' and (vdt BETWEEN '$start_date' and '$end_date')");
					$rr = $rr->result();
					foreach($rr as $tt){
						echo "---with invoice---".$tt->chemist_id;
						echo ",";
					}*/

					/*
					if (preg_match('/FROM\s+(\d+@\s*)([\w\s]+)\s+/', $text, $matches)) {
						$from_text = $matches[1];
						echo "--type0:".$from_text; // Output: 9792612185@ PAYTM SAMEER S O KALLU NA
					}

					if (preg_match('/FROM\s+([\d-]+@\s*)([\w\s]+)\s+PAYMENT/', $text, $matches)) {
						$from_text = $matches[1];
						echo "--type1:".$from_text; // Output: SUNILKJAIN2 7@OKAXIS NAMAN MEDICAL STORE
					}

					if (preg_match('/FROM\s+([\w\d\s.@]+)\s+PAYMENT FROM PHONEPE$/', $text, $matches)) {
						$from_text = $matches[1];
						echo "--type2:".$from_text; // Output: RAJENDER.SI NSINWAR@YBL SWAMIJI MEDICOS
					}*/
					// Find the position of "FROM" and "@"

					/*$from_pos = strpos($text, "FROM");
					$at_pos = strpos($text, "@");

					// Extract the text between "FROM" and "@"
					$from_at_text = substr($text, $from_pos + strlen("FROM"), $at_pos - $from_pos - strlen("FROM"));

					echo "--type11:".$from_at_text; // Output: SUNILKJAIN2 7
					echo "<br><br>";*/

					// Use regular expression to extract text after "FROM"
					if (preg_match('/FROM\s+([^@]+)/', $text, $matches)) {
						$received_from = trim($matches[1]);
						echo $received_from; // Output: 97926121865@PAYTM SAMEER S O KALLU NA
					}
					echo "<br><br>";

					/*************************** */

					$searchValue = strtolower($received_from);
					foreach($rows as $key => $value){
						$value = strtolower($value);
						if (strpos($value, $searchValue) !== false) {
							echo "<b>$searchValue found in value with key $key </b><br>";
						}
					}
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