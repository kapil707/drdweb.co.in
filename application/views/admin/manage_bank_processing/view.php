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
			<table class="table table-striped table-bordered table-hover" id="example-table">
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
					}					
				} ?>
				<tr>
					<td><?= ($row->status); ?> / <?= ($row->type); ?></td>
					<td><?= ($row->date); ?></td>
					<td><?= ($row->upi_no); ?><br><?= ($row->orderid); ?></td>
					<td><?= ($row->amount); ?></td>
					<td><?= ($row->received_from); ?></td>
					<td><?= ($highlighted_text); ?></td>
					<td><?= ($chemist_dt); ?></td>
					<td><?= ($process_invoice); ?></td>
					<td><?= ($row->find_by); ?><br><?= ($find); ?></td>
					<td><?= ($find_all); ?></td>
					<td><input type="text" value="<?php echo $done_chemist ?>"></td>
				</tbody>
			</table>
		</div>
    </div>
</div>
<div class="myhiden_data_for_modal"></div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title main_modal_title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <div class="main_modal_p"></div>
		<input type="hidden" class="hidden_id">
		<input type="text" class="hidden_received_from">
		<input type="text" class="add_new_chemist" onchange="onchange_add_new_chemist()">
		<button type="button" class="btn btn-default" onclick="add_chemist_id_by_link_name()">save</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>