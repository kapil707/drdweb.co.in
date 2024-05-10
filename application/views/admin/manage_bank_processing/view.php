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
					$chemist_fafa = "";
					if($row->process_status=="1"){
						$chemist_fafa = '<i class="fa fa-check-circle" aria-hidden="true" style="color: green;font-size: 20px;"></i>';
					}
					if($row->process_status=="0"){
						$chemist_fafa = '<i class="fa fa-question-circle" aria-hidden="true" style="color: orange;font-size: 20px;"></i>';
					}
					$search = $row->process_name;
					
					$search_escaped = preg_quote($search, '/');
					$highlighted_text = preg_replace('/(' . $search_escaped . ')/i', '<span style="background-color: yellow;">$1</span>', $row->process_value);

					$invoice_chemist = "";
					$process_invoice = "";
					$fruits_array = explode(",", $row->process_invoice);
					foreach($fruits_array as $rows){
						$process_invoice.= $rows."<br>";

						$arr = explode(":-",$rows);
						$invoice_chemist = $arr[0];
					}
					
					$find = "find by ";
					if(!empty($row->process_invoice)){
						$find.= "<b>invoice</b>,";
					}
					if(!empty($row->chemist_id)){
						$find.= "<b>chemist</b>";
					}
					if(empty($process_invoice) && empty($row->chemist_id)){
						$find = "N/A";
					}
					
					$final_chemist = "";
					$chemist_id_array = explode(",", $row->chemist_id);
					$chemist_id_array = array_unique($chemist_id_array);
					foreach($chemist_id_array as $rows){
						$chemist_dt.= $rows."<br>"; 
						$final_chemist = $rows;
					}
					
					$done_chemist = "";
					$find_all = "";
					if((strtolower($final_chemist)==strtolower($invoice_chemist)) && (!empty($invoice_chemist) && !empty($final_chemist))){
						$find_all = "done";
						$done_chemist = $final_chemist; 
						$tr_style = "background-color: #e8ffe2;";
					}?>

					<tr style="<?php echo $tr_style ?>">
						<td><?= ($row->status); ?> / <?= ($row->type); ?></td>
						<td><?= ($row->date); ?></td>
						<td><?= ($row->upi_no); ?><br><?= ($row->orderid); ?></td>
						<td><?= ($row->amount); ?></td>
						<td><?= ($row->received_from); ?> <i class="fa fa-pencil"></i></td>
						<td><?= ($highlighted_text); ?></td>
						<td><?= ($chemist_dt); ?></td>
						<td><?= ($process_invoice); ?></td>
						<td><?= ($row->find_by); ?><br><?= ($find); ?></td>
						<td><?= ($find_all); ?></td>
						<td>
							<input type="text" value="<?php echo $done_chemist ?>" class="final_chemist_text_<?php $row->id; ?>">
							<i class="fa fa-check" aria-hidden="true" onchange="add_final_chemist('<?php $row->id; ?>')"></i>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
    </div>
</div>
<script>
function add_final_chemist(id){
	var final_chemist = $(".final_chemist_text_"+id).val();
	$(".final_chemist_text_"+id).hide();
	$.ajax({
		type : "POST",
		data : {id:id,final_chemist:final_chemist,},
		url  : "<?= base_url()?>admin/<?= $Page_name?>/add_final_chemist",
		cache: true,
		error: function(){
		},
		success    : function(data){
		}
	});
}
</script>