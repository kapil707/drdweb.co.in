<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a>
   	</div>
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
				print_r($row);
				die();
				$combined_records = array();
				foreach ($result as $row) {

					$upi_no = $row->upi_no;
					$type = strtolower($row->type);
					
					if (!isset($combined_records[$upi_no])) {
						$combined_records[$upi_no] = array();
					}
					
					$combined_records[$upi_no][$type] = $row;
				}
					
					/*$tr_style = "";
					$chemist_fafa[] = "";
					$search = $row->process_name;
					
					$search_escaped = preg_quote($search, '/');
					$highlighted_text = preg_replace('/(' . $search_escaped . ')/i', '<span style="background-color: yellow;">$1</span>', $row->process_value);
					
					$find = "find by ";
					if(!empty($row->find_invoice_chemist_id)){
						$find.= "<b>invoice</b>,";
					}
					if(!empty($row->find_chemist_id)){
						$find.= "<b>chemist</b>";
					}
					if(empty($find_invoice_chemist_id) && empty($row->find_chemist_id)){
						$find = "N/A";
					}
					
					$find_chemist_id2 = "";
					$find_chemist_id_array = explode(",", $row->find_chemist_id);
					$find_chemist_id_array = array_unique($find_chemist_id_array);					
					if(count($find_chemist_id_array)==1){
						$find_chemist_id2 = $find_chemist_id_array[0];
					}

					$find_invoice_chemist_id1 = "";
					$find_invoice_chemist_id2 = "";
					$find_invoice_chemist_id_array = explode(",", $row->find_invoice_chemist_id);
					foreach($find_invoice_chemist_id_array as $rows){
						$find_invoice_chemist_id1.= $rows."<br>";

						$arr = explode(":-",$rows);
						$find_invoice_chemist_id2 = $arr[0];
					}
					
					$done_chemist_id = "";
					$find_all = "";
					
					if((strtolower($find_chemist_id2)==strtolower($find_invoice_chemist_id2)) && (!empty($find_invoice_chemist_id2) && !empty($find_chemist_id2))){
						$find_all = "done";
						$tr_style = "background-color: #ffe1c0;";

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

					if($row->done_status==1){
						$tr_style = "background-color: #e8ffe2;";
						$done_chemist_id = $row->done_chemist_id;
					}
					}*/
					?>

<?php 
print_r($combined_records);
foreach ($combined_records as $upi_no => $types): ?>
            <tr>
                <td><?php echo $upi_no; 
				
				echo $types['sms']['status'];?>
				<br><br>
				<?php print_r($types); ?></td>
                <td><?php echo isset($types->sms) ? $types->status : ''; ?></td>
                <td><?php echo isset($types->sms) ? $types->sms['amount'] : ''; ?></td>
                <td><?php echo isset($types->sms) ? $types->sms['date'] : ''; ?></td>
                <td><?php echo isset($types->sms) ? $types->sms['time'] : ''; ?></td>
                <td><?php echo isset($types->sms) ? $types->sms['orderid'] : ''; ?></td>
                <td><?php echo isset($types->sms) ? $types->sms['received_from'] : ''; ?></td>
                <td><?php echo isset($types->sms) ? $types->sms['process_name'] : ''; ?></td>
                <td><?php echo isset($types->sms) ? $types->sms['find_invoice_chemist_id'] : ''; ?></td>
                <td><?php echo isset($types->sms) ? $types->sms['whatsapp_body'] : ''; ?></td>
                
            </tr>
        <?php endforeach; ?>

<?php die(); /*?>
					<tr class="tr_css_<?php echo $row->id; ?>" style="<?php echo $tr_style ?>">
						<td><?php echo $row->id; ?> </td>
						<td>
							Status : <?= ($row->status); ?>
							<br><br>
							Date : <?= ($row->time); ?>
							<br><br>
							Upi No : <?= ($row->upi_no); ?>
							<br><br>
								
							<div style="word-wrap:break-word;width:200px;">
								Orderid : <?= ($row->orderid); ?>
							</div>

							<br><br>
							<b>Amount : <?= ($row->amount); ?></b>
						</td>
						<td>
							<?php print_r($main_sms); ?>
							<?php print_r($main_statment); ?>
							<?php /* foreach($newrow as $banktype){
								echo $banktype; 
							} */?>
							
							<?php /*
							: <?= ($row->received_from); ?>

							<input type="hidden" value="<?php echo $row->received_from ?>" class="text_received_from_<?php echo $row->id; ?>">

							<input type="text" value="<?php echo $row->find_chemist_id; ?>" class="text_received_from_chemist_id_<?php echo $row->id; ?>" style="display:none">

							<i class="fa fa-check add_received_from_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="add_received_from_chemist_id('<?php echo $row->id; ?>')" style="display:none"></i>

							<i class="fa fa-times cancel_received_from_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="cancel_received_from_chemist_id('<?php echo $row->id; ?>')" style="display:none"></i>
							
							<i class="fa fa-pencil edit_received_from_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="edit_received_from_chemist_id('<?php echo $row->id; ?>')"></i>
							<br><br>
							<div style="word-wrap:break-word;width:250px;">
								Find : <?= ($highlighted_text); ?> || <b>(<?= ($row->find_by); ?>)</b>
							</div>
							<br><br>
							<b>Chemist : </b>
							<?php 
							if(!empty($find_chemist_id_array)){
								foreach($find_chemist_id_array as $rows){
									echo $rows;
									if(!empty($chemist_fafa[$rows])){
										echo $chemist_fafa[$rows];
									}
									echo "<br>";
								}
							}
							?>
							<br>
							<b>Invoice : </b>
							<br><br>
							<b>WhatsApp : </b>
							<?php echo $row->whatsapp_body; ?>
						</td>
						<td><?= ($find_invoice_chemist_id1); ?></td>
						<td><?= ($row->whatsapp_body2); ?></td>
						<td class="display: flex;">
							<b><?= ($find); ?></b>
							<br>
							<input type="text" value="<?php echo $done_chemist_id ?>" class="text_done_chemist_id_<?php echo $row->id; ?>" style="<?php if($row->done_status==1) { ?>display:none;<?php } ?> float: left; width: 100px;">
							
							<i class="fa fa-check add_done_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="add_done_chemist_id('<?php echo $row->id; ?>')" style="<?php if($row->done_status==1) { ?>display:none;<?php } ?> float: left;font-size: 20px;"></i>

							<span class="span_done_chemist_id_<?php echo $row->id; ?>" <?php if($row->done_status==0 || $row->done_status==2) { ?>style="display:none" <?php } ?>><?php echo $done_chemist_id ?></span>

							<i class="fa fa-pencil edit_done_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="edit_done_chemist_id('<?php echo $row->id; ?>')" <?php if($row->done_status==0 || $row->done_status==2) { ?>style="display:none" <?php } ?>></i>
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