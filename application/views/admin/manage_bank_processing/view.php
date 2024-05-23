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
								"orderid" => split_csv_values($item->orderid)[$index] ?? ''
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
								"orderid" => split_csv_values($item->orderid)[$index] ?? ''
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
					$fine_user_info = preg_replace('/(' . $process_name . ')/i', '<span style="background-color: yellow;">$1</span>', $process_value);

					if(empty($process_value)){
						$fine_user_info = "N/a";
					}

					/********************************************** */
					$find_invoice_chemist_id = "";
					if(!empty($entry['sms']['find_invoice_chemist_id'])){
						$find_invoice_chemist_id = $entry['sms']['find_invoice_chemist_id'];
					}
					if(!empty($entry['statement']['find_invoice_chemist_id'])){
						$find_invoice_chemist_id = $entry['statement']['find_invoice_chemist_id'];
					}

					$find_chemist_id = "";
					if(!empty($entry['sms']['find_chemist_id'])){
						$find_chemist_id = $entry['sms']['find_chemist_id'];
					}
					if(!empty($entry['statement']['find_chemist_id'])){
						$find_chemist_id = $entry['statement']['find_chemist_id'];
					}
					
					$find = "find by ";
					if(!empty($find_invoice_chemist_id)){
						$find.= "<b>invoice</b>,";
					}
					if(!empty($find_chemist_id)){
						$find.= "<b>chemist</b>";
					}
					if(empty($find_invoice_chemist_id) && empty($find_chemist_id)){
						$find = "N/A";
					}
					
					$find_chemist_id2 = "";
					$find_chemist_id_array = explode("||", $find_chemist_id);
					$find_chemist_id_array = array_unique($find_chemist_id_array);					
					if(count($find_chemist_id_array)==1){
						$find_chemist_id2 = $find_chemist_id_array[0];
					}

					$get_all_chemist_id = "";
					if(!empty($find_chemist_id_array)){
						foreach($find_chemist_id_array as $rows){
							$get_all_chemist_id.= $rows;
							if(!empty($chemist_fafa[$rows])){
								$get_all_chemist_id.= $chemist_fafa[$rows];
							}
							$get_all_chemist_id.= " || ";
						}
					}

					if(!empty($get_all_chemist_id)){
						$get_all_chemist_id = substr($get_all_chemist_id, 0, -4);
					}

					if(empty($find_chemist_id_array[0])){
						$get_all_chemist_id = "N/a";
					}

					$find_invoice_chemist_id_array = explode("||", $find_invoice_chemist_id);

					$get_all_invoice = $get_all_invoice_chemist = "";
					foreach($find_invoice_chemist_id_array as $rows){
						$get_all_invoice.= $rows."<br>";

						$arr = explode(":-",$rows);
						$get_all_invoice_chemist.= $arr[0]." || ";
					}

					if(empty($find_invoice_chemist_id)){
						$get_all_invoice = "N/a";
					}

					if(!empty($get_all_invoice_chemist)){
						$get_all_invoice_chemist = substr($get_all_invoice_chemist, 0, -4);
					}
					
					$done_chemist_id = "";
					$find_all = "";
					$done_status = "";
					if(!empty($entry['sms']['done_status'])){
						$done_status = $entry['sms']['done_status'];
					}
					if(!empty($entry['statement']['done_status'])){
						$done_status = $entry['statement']['done_status'];
					}

					$my_done_chemist_id = "";
					if(!empty($entry['sms']['done_chemist_id'])){
						$my_done_chemist_id = $entry['sms']['done_chemist_id'];
					}
					if(!empty($entry['statement']['done_chemist_id'])){
						$my_done_chemist_id = $entry['statement']['done_chemist_id'];
					}

					if($done_status==1){
						$tr_style = "background-color: #e8ffe2;";
						$done_chemist_id = $my_done_chemist_id;
					}
					
					/****************************************************** */
					$status = "<b>Status</b><br>";
					if(!empty($entry['statement']['status'])){
						$status.= "Bank : ".$entry['statement']['status']."<br>";
					}
					if(!empty($entry['sms']['status'])){
						$status.= "SMS  : ".$entry['sms']['status'];
					}

					/****************************************************** */
					$date = "<b>Date</b><br>";
					if(!empty($entry['statement']['date'])){
						$date.= "Bank : ".$entry['statement']['date']."<br>";
					}
					if(!empty($entry['sms']['date'])){
						$date.= "SMS  : ".$entry['sms']['date'];
					}

					/****************************************************** */
					$time = "<b>Time</b><br>";
					if(!empty($entry['statement']['time'])){
						$time.= "Bank : ".$entry['statement']['time']."<br>";
					}
					if(!empty($entry['sms']['time'])){
						$time.= "SMS  : ".$entry['sms']['time'];
					}
					/****************************************************** */
					$upi_no = $entry['upi_no'];
					/****************************************************** */
					$orderid = "<b>Orderid</b> <br>";
					if(!empty($entry['statement']['orderid'])){
						$orderid.= "Bank : ".$entry['statement']['orderid']."<br>";
					}
					if(!empty($entry['sms']['time'])){
						$orderid.= "SMS  : ".$entry['sms']['orderid'];
					}
					/****************************************************** */
					$amount = "<b>Amount</b> <br>";
					if(!empty($entry['statement']['amount'])){
						$amount.= "Bank : ".$entry['statement']['amount']."<br>";
					}
					if(!empty($entry['sms']['amount'])){
						$amount.= "SMS  : ".$entry['sms']['amount'];
					}

					/****************************************************** */
					$received_from = "";
					if(!empty($entry['statement']['received_from'])){
						$received_from.= "Bank : ".$entry['statement']['received_from']."<br>";
					}
					if(!empty($entry['sms']['received_from'])){
						$received_from.= "SMS  : ".$entry['sms']['received_from'];
					}

					$received_from1 = "";
					if(!empty($entry['statement']['received_from']) && !empty($entry['sms']['received_from'])){
						if(strtolower($entry['statement']['received_from']) == strtolower($entry['sms']['received_from'])){
							$received_from1 = $entry['sms']['received_from'];
						}
					}else{
						if(!empty($entry['statement']['received_from'])){
							$received_from1 = $entry['statement']['received_from'];
						}else{
							if(!empty($entry['sms']['received_from'])){
								$received_from1 = $entry['sms']['received_from'];
							}
						}
					}

					$find_by_logic = "";
					if(!empty($entry['sms']['find_by'])){
						$find_by_logic = $entry['sms']['find_by'];
					}
					if(!empty($entry['statement']['find_by'])){
						$find_by_logic = $entry['statement']['find_by'];
					}

					if(empty($find_by_logic)){
						$find_by_logic = "N/a";
					}

					$whatsapp_body = "";
					if(!empty($entry['sms']['whatsapp_body'])){
						$whatsapp_body = $entry['sms']['whatsapp_body'];
					}
					if(!empty($entry['statement']['whatsapp_body'])){
						$whatsapp_body = $entry['statement']['whatsapp_body'];
					}

					$whatsapp_body_1 = str_replace(' ', '', $whatsapp_body);
					$whatsapp_body_1 = str_replace('-', '', $whatsapp_body_1);

					$whatsapp_body2 = "";
					if(!empty($entry['sms']['whatsapp_body2'])){
						$whatsapp_body2 = $entry['sms']['whatsapp_body2'];
					}
					if(!empty($entry['statement']['whatsapp_body'])){
						$whatsapp_body2 = $entry['statement']['whatsapp_body2'];
					}

					if((!empty($find_chemist_id2))){
						$tr_style = "background-color: cornsilk";
					}

					if((!empty($get_all_invoice_chemist))){
						$tr_style = "background-color: khaki";
					}

					if((!empty($whatsapp_body_1)) && $whatsapp_body_1!="N/a"){
						$tr_style = "background-color: lemonchiffon";
					}

					if(($whatsapp_body_1=="N/a" || empty($whatsapp_body_1)) && $get_all_chemist_id=="N/a" && empty($get_all_invoice_chemist)){
						$tr_style = "background-color: darksalmon";
					}

					if((strtolower($find_chemist_id2)==strtolower($get_all_invoice_chemist)) && (!empty($get_all_invoice_chemist) && !empty($find_chemist_id2))){
						$find_all = "done";
						$tr_style = "background-color: darkseagreen;";

						$done_chemist_id = $find_chemist_id2;
					}

					if((strtolower($find_chemist_id2)==strtolower($whatsapp_body_1)) && (!empty($whatsapp_body_1) && !empty($find_chemist_id2))){
						$find_all = "done";
						$tr_style = "background-color: lightseagreen;";

						$done_chemist_id = $find_chemist_id2;
					}

					if((strtolower($find_chemist_id2)==strtolower($get_all_invoice_chemist)) && (strtolower($find_chemist_id2)==strtolower($whatsapp_body_1)) && (!empty($get_all_invoice_chemist) && !empty($find_chemist_id2) && !empty($whatsapp_body_1))){
						$find_all = "done-all";
						$tr_style = "background-color: darkkhaki;";

						$done_chemist_id = $find_chemist_id2;
					}

					if(empty($find_all) && !empty($find_invoice_chemist_id_array) &&  !empty($find_chemist_id_array)){
						foreach($find_invoice_chemist_id_array as $rows){
							foreach($find_chemist_id_array as $rows1){
								$arr = explode(":-",$rows);
								if($arr[0]==$rows1 && !empty($arr[0]) && !empty($rows1)){
									$find_all = "new-done";
									$done_chemist_id = $rows1;

									$chemist_fafa[$done_chemist_id] = '<i class="fa fa-check-circle" aria-hidden="true" style="color: green;font-size: 20px;"></i>';

									$tr_style = "background-color: #D9C0FF;";
								}
							}
						}
					}
					if(empty($get_all_invoice_chemist)){
						$get_all_invoice_chemist = "N/a";
					}
					?>
					<tr class="tr_css_<?php echo $row_id; ?>" style="<?php echo $tr_style ?>">
						<td><?php echo $row_id; ?> </td>
						<td>
							<?= ($status); ?>
							<br><br>
							<?= ($date); ?>
							<br><br>
							<?= ($time); ?>
							<br><br>
								Upi No : <?= $upi_no; ?>
							<br><br>
								
							<div style="word-wrap:break-word;width:200px;">
								<?= $orderid; ?>
							</div>
							<br><br>
							<b><?= $amount; ?></b>
						</td>
						<td>
							<?= ($received_from); ?>

							<input type="hidden" value="<?php echo $received_from1 ?>" class="text_received_from_<?= ($row_id); ?>">

							<input type="text" value="<?php echo $find_chemist_id; ?>" class="text_received_from_chemist_id_<?= ($row_id); ?>" style="display:none">

							<i class="fa fa-check add_received_from_chemist_id_<?= ($row_id); ?>" aria-hidden="true" onclick="add_received_from_chemist_id('<?= ($row_id); ?>')" style="display:none"></i>

							<i class="fa fa-times cancel_received_from_chemist_id_<?= ($row_id); ?>" aria-hidden="true" onclick="cancel_received_from_chemist_id('<?= ($row_id); ?>')" style="display:none"></i>
							
							<i class="fa fa-pencil edit_received_from_chemist_id_<?= ($row_id); ?>" aria-hidden="true" onclick="edit_received_from_chemist_id('<?= ($row_id); ?>')"></i>
							<br><br>
							<div style="word-wrap:break-word;width:250px;">
								<b>Find : </b> 
								<?= ($fine_user_info); ?> 
							</div>
							<br>
							<div style="word-wrap:break-word;width:250px;">
								<b>Find by : </b> 
								<?= $find_by_logic; ?>
							</div>
							<br>
							<div style="word-wrap:break-word;width:250px;">
								<b>Chemist : </b>
								<?= $get_all_chemist_id;?>
							</div>
							<br>
							<div style="word-wrap:break-word;width:250px;">
								<b>Invoice : </b>
								<?= $get_all_invoice_chemist ?>
							</div>
							<br>
							<div style="word-wrap:break-word;width:250px;">
								<b>WhatsApp : </b>
								<?= ($whatsapp_body); ?>
							</div>
						</td>
						<td><?= ($get_all_invoice); ?></td>
						<td><?= ($whatsapp_body2); ?></td>
						<td class="display: flex;">
							<b><?= ($find); ?></b>
							<br>
							<input type="text" value="<?php echo $done_chemist_id ?>" class="text_done_chemist_id_<?= ($row_id); ?>" style="<?php if($done_status==1) { ?>display:none;<?php } ?> float: left; width: 100px;">
							
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