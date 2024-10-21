<div class="row">
	<div class="col-xs-12" style="margin-bottom:5px;">
    	<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a>
   	</div>
	<!-- <form method="post">
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
	</form> -->
    <div class="col-xs-12">
         <div class="table-responsive">
			<table id="data-table-basic" class="table table-striped table-bordered table-hover dataTables-example">
                <thead>
                    <tr>
                    	<th>
                        	Sno.
                        </th>
						<th>
							Item Code
						</th>
						<th>
							Item Name
						</th>
						<th>
                        	Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php
				$i=1;
                foreach ($result as $row)
                {
					?>
                    <tr></tr>
                    	<td>
                        	<?= $i; ?>
                        </td>
						<td>
                        	<?= $row->item_code;?>
						</td>
						<td>
							<?= $row->item_name;?>
                        </td>
						<td class="text-right">
							<div class="btn-group">
								<a href="edit/<?= $row->i_code; ?>" class="btn-white btn btn-xs">Edit
								</a>
								<a href="javascript:void(0)" onclick="delete_rec('<?= $row->i_code; ?>')" class="btn-white btn btn-xs">Delete</i> </a>
							</div>
                        </td> 
					</tr>
                    <?php
						$i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>