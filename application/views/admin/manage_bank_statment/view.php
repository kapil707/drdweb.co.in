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
			<button type="submit" class="btn btn-info submit_button" name="Submit">
				<i class="ace-icon fa fa-check bigger-110"></i>
				Submit
			</button>
		</div>		
		<div class="col-md-3">
			<a href="<?= base_url()?>admin/<?= $Page_name?>/statment_excel_file?date-range=<?= $date_range ?>" class="btn btn-info">Download Statment</a>
		</div>
	</form>
	<div class="col-xs-12">
		<div class="table-responsive">
            <table class="table table-striped table-bordered table-hover dataTables-example21">
                <thead>
                    <tr>
						<th>Account Number</th>
						<th>Branch Number</th>
						<th>Statement Date</th>
						<th>Closing Ledger Balance</th>
						<th>Calculated Balances</th>
						<th>Amount</th>
						<th>Entry Date</th>
						<th>Value Date</th>
						<th>Bank Reference</th>
						<th>Customer Reference</th>
						<th>Narrative</th>
						<th>Transaction Description</th>
						<th>Chemist</th>
						<th>Invoice</th>
                    </tr>
                </thead>
				<tbody>
				<?php				
				foreach ($result as $row) { ?>
					<tr class="tr_css_<?php echo $row->id; ?>">
						<td>
							<?php echo $row->account_no; ?>
						</td>
						<td>
							<?php echo $row->branch_no; ?>
						</td>
						<td>
							<?php echo $row->statment_date; ?>
						</td>
						<td>
							<?php echo $row->closing_ledger_balance; ?>
						</td>
						<td>
							<?php echo $row->calculated_balances; ?>
						</td>
						<td>
							<?php echo $row->amount; ?>
						</td>
						<td>
							<?php echo $row->enter_date; ?>
						</td>
						<td>
							<?php echo date('m/d/Y', strtotime($row->value_date)); ?>
						</td>
						<td>
							<?php echo $row->bank_reference; ?>
						</td>
						<td>
							<?php echo $row->customer_reference; ?>
						</td>
						<td>
							<?php echo $row->narrative; ?>
						</td>
						<td>
							<?php echo $row->transaction_description; ?>
						</td>
						<td>
							<?php echo $row->chemist_id; ?>
						</td>
						<td>
							<?php echo $row->done_invoice; ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
    </div>
</div>