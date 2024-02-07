<div class="row">
	<div class="col-xs-12" style="margin-bottom:5px;">
		<?php /* ?>
			<a href="add">
				<button type="submit" class="btn btn-info">
					Add
				</button>
			</a>
		<?php */ ?>
		<form method="post">
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
				<div class="form-group nk-datapk-ctm form-elet-mg" id="data_1">
					<div class="input-group date nk-int-st">
						<label>Select Date</label>
						<input type="date" class="form-control" value="<?= $vdt1; ?>" name="vdt">
					</div>
				</div>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
				<button type="submit" name="submit" class="btn btn-success notika-btn-success waves-effect" value="submit">Submit</button>
			</div>
		</form>
	</div>
	<div class="col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="example-table">
				<thead>
					<tr>
						<th>Sno.</th>
						<th>Gstvno</th>
						<th>Date</th>
						<th>Chemist</th>
						<th>Amount</th>
						<th>Picked-by</th>
						<th>Checked-by</th>
						<th>Deliver-by</th>
						<th>Report</th>
						<th>View Order</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>