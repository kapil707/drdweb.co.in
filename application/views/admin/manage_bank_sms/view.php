<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<!-- <a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a> -->
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
		<div class="col-md-3"></div>
	</form>
	<div class="col-xs-12">
        <div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="example-table">
                <thead>
					<tr>
						<th>
							Sno.
						</th>
						<th>
							Sender
						</th>
						<th>
							Message
						</th>
						<th>
							Date
						</th>
						<th>
							Time
						</th>
						<th>
							ChemistID
						</th>
                    </tr>
                </thead>
				
				</tbody>
			</table>
		</div>
    </div>
</div>
