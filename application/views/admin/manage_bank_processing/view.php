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
							Status / Type
						</th>
						<th width="80">
							Date
                        </th>
						<th width="150px"> 
							Upi No /
							Orderid
                        </th>
						<th>
							Amount
						</th>
						<th>
							From 
						</th>
						<th>
                        	Find By
                        </th>
						<th>
                        	Chemist
                        </th>
						<th width="225px">
                        	Invoice
                        </th>
						<th>
                        	Find
                        </th>
						<th>
                        	Find
                        </th>
						<th>
                        	Edit
                        </th>
                    </tr>
                </thead>
				<tbody>
				<?php
				foreach ($result as $row) {
					$tr_style = "";
					$chemist_fafa[] = "";
					// if($row->process_status=="1"){
					// 	$chemist_fafa = '<i class="fa fa-check-circle" aria-hidden="true" style="color: green;font-size: 20px;"></i>';
					// }
					// if($row->process_status=="0"){
					// 	$chemist_fafa = '<i class="fa fa-question-circle" aria-hidden="true" style="color: orange;font-size: 20px;"></i>';
					// }
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
					?>

					<tr style="<?php echo $tr_style ?>">
						<td><?= ($row->status); ?> / <?= ($row->type); ?></td>
						<td><?= ($row->date); ?></td>
						<td>
							<div style="word-wrap:break-word;width:95px;">
								<?= ($row->upi_no); ?>
								<br>
								<?= ($row->orderid); ?>
							</div>
						</td>
						<td><?= ($row->amount); ?></td>
						<td>
							<?= ($row->received_from); ?>

							<input type="hidden" value="<?php echo $row->received_from ?>" class="text_received_from_<?php echo $row->id; ?>">

							<input type="text" value="<?php echo $row->find_chemist_id; ?>" class="text_received_from_chemist_id_<?php echo $row->id; ?>" style="display:none">

							<i class="fa fa-check add_received_from_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="add_received_from_chemist_id('<?php echo $row->id; ?>')" style="display:none"></i>
							
							<i class="fa fa-pencil edit_received_from_chemist_id_<?php echo $row->id; ?>" aria-hidden="true" onclick="edit_received_from_chemist_id_('<?php echo $row->id; ?>')"></i>
						</td>
						<td><?= ($highlighted_text); ?></td>
						<td>
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
						</td>
						<td><?= ($find_invoice_chemist_id1); ?></td>
						<td><?= ($row->find_by); ?><br><?= ($find); ?></td>
						<td><?= ($find_all); ?></td>
						<td class="display: flex;">
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
			},
			success: function(data){
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
		$.ajax({
			type : "POST",
			data : {chemist_id:chemist_id,received_from:received_from,},
			url  : "<?= base_url()?>admin/<?= $Page_name?>/add_received_from_chemist_id",
			cache: true,
			error: function(){
			},
			success: function(data){
			}
		});
	}
}

function edit_received_from_chemist_id(id){
	$(".text_received_from_chemist_id_"+id).show();
	$(".add_received_from_chemist_id_"+id).show();

	$(".edit_received_from_chemist_id_"+id).hide();
}

</script>