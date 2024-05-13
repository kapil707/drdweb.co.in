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
						<th width="150"> 
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
						<th width="150px">
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
					$chemist_dt = "";
					// $chemist_fafa = "";
					// if($row->process_status=="1"){
					// 	$chemist_fafa = '<i class="fa fa-check-circle" aria-hidden="true" style="color: green;font-size: 20px;"></i>';
					// }
					// if($row->process_status=="0"){
					// 	$chemist_fafa = '<i class="fa fa-question-circle" aria-hidden="true" style="color: orange;font-size: 20px;"></i>';
					// }
					$search = $row->process_name;
					
					$search_escaped = preg_quote($search, '/');
					$highlighted_text = preg_replace('/(' . $search_escaped . ')/i', '<span style="background-color: yellow;">$1</span>', $row->process_value);

					$invoice_chemist = "";
					$find_invoice_chemist_id = "";
					$fruits_array = explode(",", $row->find_invoice_chemist_id);
					foreach($fruits_array as $rows){
						$find_invoice_chemist_id.= $rows."<br>";

						$arr = explode(":-",$rows);
						$invoice_chemist = $arr[0];
					}
					
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
					
					$chemist_done = "";
					$find_chemist_id_array = explode(",", $row->find_chemist_id);
					$find_chemist_id_array = array_unique($find_chemist_id_array);
					foreach($find_chemist_id_array as $rows){
						$chemist_dt.= $rows."<br>"; 
						$chemist_done = $rows;
					}
					
					$chemist_done1 = "";
					$find_all = "";
					if((strtolower($chemist_done)==strtolower($invoice_chemist)) && (!empty($invoice_chemist) && !empty($chemist_done))){
						$find_all = "done";
						$chemist_done1 = $chemist_done; 
						$tr_style = "background-color: #ffe1c0;";
					}
					if($row->status==5){
						$tr_style = "background-color: #e8ffe2;";
					}
					?>

					<tr style="<?php echo $tr_style ?>">
						<td><?= ($row->status); ?> / <?= ($row->type); ?></td>
						<td><?= ($row->date); ?></td>
						<td><?= ($row->upi_no); ?><br><?= ($row->orderid); ?></td>
						<td><?= ($row->amount); ?></td>
						<td>
							<span class="received_from_<?php echo $row->id; ?>">
								<?= ($row->received_from); ?> 
							</span>

							<i class="fa fa-pencil edit_received_from_btn_<?php echo $row->id; ?>" aria-hidden="true" onclick="edit_received_from('<?php echo $row->id; ?>')" style=""></i>						
						</td>
						<td><?= ($highlighted_text); ?></td>
						<td><?= ($chemist_dt); ?></td>
						<td><?= ($find_invoice_chemist_id); ?></td>
						<td><?= ($row->find_by); ?><br><?= ($find); ?></td>
						<td><?= ($find_all); ?></td>
						<td>
							<input type="hidden" value="<?php echo $row->received_from ?>" class="received_from_text_<?php echo $row->id; ?>">

							<input type="text" value="<?php echo $chemist_done1 ?>" class="chemist_done_text_<?php echo $row->id; ?>" <?php if($row->status==5) { ?>style="display:none" <?php } ?>>
							
							<i class="fa fa-check add_chemist_done_btn_<?php echo $row->id; ?>" aria-hidden="true" onclick="add_chemist_done('<?php echo $row->id; ?>')" <?php if($row->status==5) { ?>style="display:none" <?php } ?>></i>

							<span class="chemist_done_<?php echo $row->id; ?>" <?php if($row->status!=5) { ?>style="display:none" <?php } ?>><?php echo $chemist_done ?></span>

							<i class="fa fa-pencil edit_chemist_done_btn_<?php echo $row->id; ?>" aria-hidden="true" onclick="edit_chemist_done('<?php echo $row->id; ?>')" <?php if($row->status!=5) { ?>style="display:none" <?php } ?>></i>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
    </div>
</div>
<script>
function add_chemist_done(id){
	var received_from = $(".received_from_text_"+id).val();

	var chemist_done = $(".chemist_done_text_"+id).val();
	$(".chemist_done_text_"+id).hide();
	$(".add_chemist_done_btn_"+id).hide();

	$(".chemist_done_done_"+id).html(chemist_done);
	$(".chemist_done_done_"+id).show();
	$(".edit_chemist_done_btn_"+id).show();
	$.ajax({
		type : "POST",
		data : {id:id,chemist_done:chemist_done,received_from:received_from,},
		url  : "<?= base_url()?>admin/<?= $Page_name?>/add_chemist_done",
		cache: true,
		error: function(){
		},
		success: function(data){
		}
	});
}
function edit_chemist_done(id){
	$(".chemist_done_text_"+id).show();
	$(".add_chemist_done_btn_"+id).show();

	$(".chemist_done_done_"+id).hide();
	$(".edit_chemist_done_btn_"+id).hide();
}
</script>