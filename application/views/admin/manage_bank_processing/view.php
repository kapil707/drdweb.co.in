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
                    </tr>
                </thead>
				<tbody>
					
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