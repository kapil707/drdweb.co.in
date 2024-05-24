<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a>
   	</div>
	<?php
	$date_range = "";
	if(isset($_GET["date-range"])){
		$date_range = $_GET["date-range"];
	}
	?>
	<form method="get" class="mb-5" style="margin-bottom:10px;">
		<div class="col-md-3">
			<label for="date-range">Select Date Range:</label>
		</div>
		<div class="col-md-3">
			<input type="text" id="date-range" class="form-control" name="date-range" value="<?php echo $date_range ?>">
		</div>
		<div class="col-md-3">
			
		</div>
		<div class="col-md-3">
			<button type="submit" class="btn btn-info submit_button" name="Submit">
				<i class="ace-icon fa fa-check bigger-110"></i>
				Submit
			</button>
		</div>
	</form>
	<div class="col-xs-12">
		<div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example">
                <thead>
                    <tr>
						<th width="50">
							Id
						</th>
						<th>

                        </th>
						<th>
							From 
						</th>
						<th width="225px">
                        	Invoice
                        </th>
						<th width="225px">
							WhatsApp 
						</th>
						<th>
                        	Edit
                        </th>
                    </tr>
                </thead>
				<tbody>
				<?php
				function split_csv_values($value) {
					return array_map('trim', explode(',', $value));
				}
				
				// Process the data
				$processed_data = [];
				
				foreach ($result as $item) {
					$types = split_csv_values($item->type);
					$sms_data = [];
					$statement_data = [];

					foreach ($types as $index => $type) {
						$type = strtolower(trim($type));
						if ($type == 'sms') {
							$sms_data = [
								"id" => split_csv_values($item->id)[$index] ?? '',
								"find_by" => split_csv_values($item->find_by)[$index] ?? '',
								"whatsapp_body" => split_csv_values($item->whatsapp_body)[$index] ?? '',
								"whatsapp_body2" => split_csv_values($item->whatsapp_body2)[$index] ?? '',
								"process_name" => split_csv_values($item->process_name)[$index] ?? '',
								"date" => split_csv_values($item->date)[$index] ?? '',
								"time" => split_csv_values($item->time)[$index] ?? '',
								"process_value" => split_csv_values($item->process_value)[$index] ?? '',
								"find_chemist_id" => split_csv_values($item->find_chemist_id)[$index] ?? '',
								"find_invoice_chemist_id" => split_csv_values($item->find_invoice_chemist_id)[$index] ?? '',
								"done_status" => split_csv_values($item->done_status)[$index] ?? '',
								"status" => split_csv_values($item->status)[$index] ?? '',
								"received_from" => split_csv_values($item->received_from)[$index] ?? '',
								"amount" => split_csv_values($item->amount)[$index] ?? '',
								"orderid" => split_csv_values($item->orderid)[$index] ?? '',
								"done_chemist_id" => split_csv_values($item->done_chemist_id)[$index] ?? ''
							];
						} elseif ($type == 'statment') {
							$statement_data = [
								"id" => split_csv_values($item->id)[$index] ?? '',
								"find_by" => split_csv_values($item->find_by)[$index] ?? '',
								"whatsapp_body" => split_csv_values($item->whatsapp_body)[$index] ?? '',
								"whatsapp_body2" => split_csv_values($item->whatsapp_body2)[$index] ?? '',
								"process_name" => split_csv_values($item->process_name)[$index] ?? '',
								"date" => split_csv_values($item->date)[$index] ?? '',
								"time" => split_csv_values($item->time)[$index] ?? '',
								"process_value" => split_csv_values($item->process_value)[$index] ?? '',
								"find_chemist_id" => split_csv_values($item->find_chemist_id)[$index] ?? '',
								"find_invoice_chemist_id" => split_csv_values($item->find_invoice_chemist_id)[$index] ?? '',
								"done_status" => split_csv_values($item->done_status)[$index] ?? '',
								"status" => split_csv_values($item->status)[$index] ?? '',
								"received_from" => split_csv_values($item->received_from)[$index] ?? '',
								"amount" => split_csv_values($item->amount)[$index] ?? '',
								"orderid" => split_csv_values($item->orderid)[$index] ?? '',
								"done_chemist_id" => split_csv_values($item->done_chemist_id)[$index] ?? ''
							];
						}
					}

					$processed_data[] = [
						"upi_no" => $item->upi_no,
						"sms" => $sms_data,
						"statement" => $statement_data
					];
				}
				
				foreach ($processed_data as $entry) {

					$tr_style = "";
					$chemist_fafa[] = "";

					/********************************************** */
					$row_id = "";
					if(!empty($entry['sms']['id'])){
						$row_id = $entry['sms']['id'];
					}
					if(!empty($entry['statement']['id'])){
						$row_id = $entry['statement']['id'];
					}

					/****************************************************** */
					$row_status = "<b>Status</b><br>";
					if(!empty($entry['sms']['status'])){
						$row_status.= "SMS  : ".$entry['sms']['status']."<br>";
					}
					if(!empty($entry['statement']['status'])){
						$row_status.= "Bank : ".$entry['statement']['status'];
					}

					/****************************************************** */
					$row_date = "<b>Date</b><br>";
					if(!empty($entry['sms']['date'])){
						$row_date.= "SMS  : ".$entry['sms']['date']."<br>";
					}
					if(!empty($entry['statement']['date'])){
						$row_date.= "Bank : ".$entry['statement']['date'];
					}

					/****************************************************** */
					$row_time = "<b>Time</b><br>";
					if(!empty($entry['sms']['time'])){
						$row_time.= "SMS  : ".$entry['sms']['time']."<br>";
					}
					if(!empty($entry['statement']['time'])){
						$row_time.= "Bank : ".$entry['statement']['time'];
					}
					/****************************************************** */
					$row_upi_no = $entry['upi_no'];
					/****************************************************** */
					$row_orderid = "<b>Orderid</b> <br>";
					if(!empty($entry['sms']['time'])){
						$row_orderid.= "SMS  : ".$entry['sms']['orderid']."<br>";
					}
					if(!empty($entry['statement']['orderid'])){
						$row_orderid.= "Bank : ".$entry['statement']['orderid'];
					}
					/****************************************************** */
					$row_amount = "<b>Amount</b> <br>";
					if(!empty($entry['sms']['amount'])){
						$row_amount.= "SMS  : ".$entry['sms']['amount']."<br>";
					}
					if(!empty($entry['statement']['amount'])){
						$row_amount.= "Bank : ".$entry['statement']['amount'];
					}

					/****************************************************** */
					$hidden_text_received_from = "";
					$row_received_from = "<b>From</b> <br>";
					if(!empty($entry['sms']['received_from'])){
						$row_received_from.= "SMS  : ".$entry['sms']['received_from'];
						$hidden_text_received_from = $entry['sms']['received_from'];
					}
					if(!empty($entry['statement']['received_from'])){
						$row_received_from.= "Bank : ".$entry['statement']['received_from']."<br>";
						$hidden_text_received_from = $entry['statement']['received_from'];
					}
					
					/********************************************** */
					$process_name = "";
					if(!empty($entry['sms']['process_name'])){
						$process_name = $entry['sms']['process_name'];
					}
					if(!empty($entry['statement']['process_name'])){
						$process_name = $entry['statement']['process_name'];
					}

					/********************************************** */
					$process_value = "";
					if(!empty($entry['sms']['process_value'])){
						$process_value = $entry['sms']['process_value'];
					}
					if(!empty($entry['statement']['process_value'])){
						$process_value = $entry['statement']['process_value'];
					}

					/********************************************** */
					$process_name = preg_quote($process_name, '/');
					$row_received_from_find = preg_replace('/(' . $process_name . ')/i', '<span style="background-color: yellow;">$1</span>', $process_value);

					if(empty($process_value)){
						$row_received_from_find = "N/a";
					}

					/********************************************** */
					$row_received_from_logic = "";
					if(!empty($entry['sms']['find_by'])){
						$row_received_from_logic = $entry['sms']['find_by'];
					}
					if(!empty($entry['statement']['find_by'])){
						$row_received_from_logic = $entry['statement']['find_by'];
					}

					if(!empty($row_received_from_logic)){
						$row_received_from_logic = " || ($row_received_from_logic)";
					}

					// chemist find karta ha yha logic
					/********************************************** */
					$find_chemist_id = "";
					if(!empty($entry['sms']['find_chemist_id'])){
						$find_chemist_id = $entry['sms']['find_chemist_id'];
					}
					if(!empty($entry['statement']['find_chemist_id'])){
						$find_chemist_id = $entry['statement']['find_chemist_id'];
					}

					$find_chemist_id_array = explode("||", $find_chemist_id);
					$find_chemist_id_array = array_unique($find_chemist_id_array);					
					// if(count($find_chemist_id_array)==1){
					// 	$singal_chemist_id = $find_chemist_id_array[0];
					// }
					
					$row_find_by_chemist_id = "";
					if(!empty($find_chemist_id_array)){
						foreach($find_chemist_id_array as $rows){
							$row_find_by_chemist_id.= $rows;
							if(!empty($chemist_fafa[$rows])){
								$row_find_by_chemist_id.= $chemist_fafa[$rows];
							}
							$row_find_by_chemist_id.= " || ";
						}
					}

					if(!empty($row_find_by_chemist_id)){
						$row_find_by_chemist_id = substr($row_find_by_chemist_id, 0, -4);
					}

					if(empty($find_chemist_id_array[0])){
						$row_find_by_chemist_id = "";
					}

					// invoice say chemist find karta ha yha logic
					/********************************************** */
					$find_invoice_chemist_id = "";
					if(!empty($entry['sms']['find_invoice_chemist_id'])){
						$find_invoice_chemist_id = $entry['sms']['find_invoice_chemist_id'];
					}
					if(!empty($entry['statement']['find_invoice_chemist_id'])){
						$find_invoice_chemist_id = $entry['statement']['find_invoice_chemist_id'];
					}

					$find_invoice_chemist_id_array = explode("||", $find_invoice_chemist_id);

					$row_find_invoice_all = $row_find_by_invoice_chemist_id = "";
					foreach($find_invoice_chemist_id_array as $rows){
						$row_find_invoice_all.= $rows."<br>";

						$arr = explode(":-",$rows);
						$row_find_by_invoice_chemist_id.= $arr[0]." || ";
					}

					if(empty($find_invoice_chemist_id)){
						$row_find_invoice_all = "N/a";
					}

					if(!empty($row_find_by_invoice_chemist_id)){
						$row_find_by_invoice_chemist_id = substr($row_find_by_invoice_chemist_id, 0, -4);
					}

					/********************************************** */
					$row_find_by_whatsapp_chemist_id = "";
					if(!empty($entry['sms']['whatsapp_body'])){
						$row_find_by_whatsapp_chemist_id = $entry['sms']['whatsapp_body'];
					}
					if(!empty($entry['statement']['whatsapp_body'])){
						$row_find_by_whatsapp_chemist_id = $entry['statement']['whatsapp_body'];
					}

					/********************************************** */
					$whatsapp_body2 = "";
					if(!empty($entry['sms']['whatsapp_body2'])){
						$whatsapp_body2 = $entry['sms']['whatsapp_body2'];
					}
					if(!empty($entry['statement']['whatsapp_body'])){
						$whatsapp_body2 = $entry['statement']['whatsapp_body2'];
					}

					/********************************************** */
					$done_status = "";
					if(!empty($entry['sms']['done_status'])){
						$done_status = $entry['sms']['done_status'];
					}
					if(!empty($entry['statement']['done_status'])){
						$done_status = $entry['statement']['done_status'];
					}

					/********************************************** */
					$done_chemist_id = "";
					if(!empty($entry['sms']['done_chemist_id'])){
						$done_chemist_id = $entry['sms']['done_chemist_id'];
					}
					if(!empty($entry['statement']['done_chemist_id'])){
						$done_chemist_id = $entry['statement']['done_chemist_id'];
					}

					/********************************************** */	
					$textbox_done_chemist_id = "";
					$row_find_by = "";
					$row_find_i = 0;
					if(!empty($row_find_by_chemist_id)){
						$row_find_by.= "chemist,";
						$row_find_i++;
					}
					if(!empty($row_find_by_invoice_chemist_id)){
						$row_find_by.= "invoice,";
						$row_find_i++;
					}
					if(!empty($row_find_by_whatsapp_chemist_id)){
						$row_find_by.= "whatsapp";
						$row_find_i++;
					}
					if(empty($row_find_by_chemist_id) && empty($row_find_by_invoice_chemist_id) && empty($row_find_by_whatsapp_chemist_id)){
						$row_find_by = "N/A";
						$tr_style = "background-color: salmon";
					}
					if($row_find_i==1){
						$tr_style = "background-color: khaki";
					}
					if($row_find_i==2){
						$tr_style = "background-color: darkseagreen";
					}
					/********************************************** */
					$row_find_by_whatsapp_chemist_id1 = 
					$row_find_by_whatsapp_chemist_id1 = str_replace(' ', '', $row_find_by_whatsapp_chemist_id);
					$row_find_by_whatsapp_chemist_id1 = str_replace('-', '', $row_find_by_whatsapp_chemist_id1);

					if((strtolower($row_find_by_chemist_id)==strtolower($row_find_by_invoice_chemist_id)) && (strtolower($row_find_by_chemist_id)==strtolower($row_find_by_whatsapp_chemist_id1)) && (!empty($row_find_by_chemist_id) && !empty($row_find_by_invoice_chemist_id) && !empty($row_find_by_whatsapp_chemist_id1))){

						$tr_style = "background-color: #1ab394;";
						$textbox_done_chemist_id = $row_find_by_chemist_id;
					}
					
					// jab koi be chemist find na ho to
					/********************************************** */
					if(empty($row_find_by_chemist_id)){
						$row_find_by_chemist_id = "N/a";
					}

					// jab koi be invoice say chemist find na ho to
					/********************************************** */
					if(empty($row_find_by_invoice_chemist_id)){
						$row_find_by_invoice_chemist_id = "N/a";
					}

					// jab koi be whatapp say chemist find na ho to
					/********************************************** */
					if(empty($row_find_by_whatsapp_chemist_id)){
						$row_find_by_whatsapp_chemist_id = "N/a";
					}

					// jab koi be whatapp say chemist find na ho to
					/********************************************** */
					if(empty($whatsapp_body2)){
						$whatsapp_body2 = "N/a";
					}

					// jab user done kar dayta ha to color change hota ha iss say
					/********************************************** */
					if($done_status==1){
						$tr_style = "background-color: #e8ffe2;";					
					}
					?>
					<tr class="tr_css_<?php echo $row_id; ?>" style="<?php echo $tr_style ?>">
						<td><?php echo $row_id; ?> </td>
						<td>
							<?= $row_status; ?>
							<br><br>
							<?= $row_date; ?>
							<br><br>
							<?= $row_time; ?>
							<br><br>
								Upi No : <?= $row_upi_no; ?>
							<br><br>								
							<div style="word-wrap:break-word;width:200px;">
								<?= $row_orderid; ?>
							</div>
							<br><br>
							<b><?= $row_amount; ?></b>
						</td>
						<td>
							<?= ($row_received_from); ?>

							<input type="hidden" value="<?php echo $hidden_text_received_from ?>" class="text_received_from_<?= ($row_id); ?>">

							<input type="text" value="<?php echo $find_chemist_id; ?>" class="text_received_from_chemist_id_<?= ($row_id); ?>" style="display:none">

							<i class="fa fa-check add_received_from_chemist_id_<?= ($row_id); ?>" aria-hidden="true" onclick="add_received_from_chemist_id('<?= ($row_id); ?>')" style="display:none"></i>

							<i class="fa fa-times cancel_received_from_chemist_id_<?= ($row_id); ?>" aria-hidden="true" onclick="cancel_received_from_chemist_id('<?= ($row_id); ?>')" style="display:none"></i>
							
							<i class="fa fa-pencil edit_received_from_chemist_id_<?= ($row_id); ?>" aria-hidden="true" onclick="edit_received_from_chemist_id('<?= ($row_id); ?>')"></i>
							<br><br>
							<div style="word-wrap:break-word;width:250px;">
								<b>Find : </b> 
								<?= ($row_received_from_find); ?> <?= $row_received_from_logic; ?>
							</div>
							<br>
							<div style="word-wrap:break-word;width:250px;">
								<b>Find by : </b> 
								<?= $row_find_by; ?>
							</div>
							<br>
							<div style="word-wrap:break-word;width:250px;">
								<b>Chemist : </b>
								<?= $row_find_by_chemist_id;?>
							</div>
							<br>
							<div style="word-wrap:break-word;width:250px;">
								<b>Invoice : </b>
								<?= $row_find_by_invoice_chemist_id ?>
							</div>
							<br>
							<div style="word-wrap:break-word;width:250px;">
								<b>WhatsApp : </b>
								<?= $row_find_by_whatsapp_chemist_id; ?>
							</div>
						</td>
						<td><?= ($row_find_invoice_all); ?></td>
						<td><?= ($whatsapp_body2); ?></td>
						<td class="display: flex;">

							<input type="text" value="<?php echo $textbox_done_chemist_id ?>" class="text_done_chemist_id_<?= ($row_id); ?>" style="<?php if($done_status==1) { ?>display:none;<?php } ?> float: left; width: 100px;">
							
							<i class="fa fa-check add_done_chemist_id_<?= ($row_id); ?>" aria-hidden="true" onclick="add_done_chemist_id('<?= ($row_id); ?>')" style="<?php if($done_status==1) { ?>display:none;<?php } ?> float: left;font-size: 20px;"></i>

							<span class="span_done_chemist_id_<?= ($row_id); ?>" <?php if($done_status==0 || $done_status==2) { ?>style="display:none" <?php } ?>><?php echo $done_chemist_id ?></span>

							<i class="fa fa-pencil edit_done_chemist_id_<?= ($row_id); ?>" aria-hidden="true" onclick="edit_done_chemist_id('<?= ($row_id); ?>')" <?php if($done_status==0 || $done_status==2) { ?>style="display:none" <?php } ?>></i>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
    </div>
</div>
<script>
function add_done_chemist_id(id){
	var received_from = $(".text_received_from_"+id).val();

	var done_chemist_id = $(".text_done_chemist_id_"+id).val();
	if(done_chemist_id.trim()==""){
		alert("Etner any chemist id")
	}else{
		$(".text_done_chemist_id_"+id).hide();
		$(".add_done_chemist_id_"+id).hide();

		$(".span_done_chemist_id_"+id).html(done_chemist_id);
		$(".span_done_chemist_id_"+id).show();
		$(".edit_done_chemist_id_"+id).show();
		$.ajax({
			type : "POST",
			data : {id:id,done_chemist_id:done_chemist_id,received_from:received_from,},
			url  : "<?= base_url()?>admin/<?= $Page_name?>/add_done_chemist_id",
			cache: true,
			error: function(){
				toastr.error('Error');
			},
			success: function(data){
				toastr.info('Save successfully');
				$(".tr_css_"+id).css("background-color", "#e8ffe2");
			}
		});
	}
}
function edit_done_chemist_id(id){
	$(".text_done_chemist_id_"+id).show();
	$(".add_done_chemist_id_"+id).show();

	$(".span_done_chemist_id_"+id).hide();
	$(".edit_done_chemist_id_"+id).hide();
}

function add_received_from_chemist_id(id){
	var received_from = $(".text_received_from_"+id).val();

	var chemist_id = $(".text_received_from_chemist_id_"+id).val();
	if(chemist_id.trim()==""){
		alert("Etner any chemist id")
	}else{
		cancel_received_from_chemist_id(id);
		$.ajax({
			type : "POST",
			data : {chemist_id:chemist_id,received_from:received_from,},
			url  : "<?= base_url()?>admin/<?= $Page_name?>/add_received_from_chemist_id",
			cache: true,
			error: function(){
				toastr.error('Error');
			},
			success: function(data){
				toastr.info('Chemist set successfully');
			}
		});
	}
}

function edit_received_from_chemist_id(id){
	$(".text_received_from_chemist_id_"+id).show();
	$(".add_received_from_chemist_id_"+id).show();
	$(".cancel_received_from_chemist_id_"+id).show();

	$(".edit_received_from_chemist_id_"+id).hide();
}

function cancel_received_from_chemist_id(id){
	$(".text_received_from_chemist_id_"+id).hide();
	$(".add_received_from_chemist_id_"+id).hide();
	$(".cancel_received_from_chemist_id_"+id).hide();

	$(".edit_received_from_chemist_id_"+id).show();
}

</script>