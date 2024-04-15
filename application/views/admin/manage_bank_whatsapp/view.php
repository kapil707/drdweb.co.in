<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<!-- <a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a> -->
   	</div>
	<div class="col-xs-12">
        <div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="example-table">
                <thead>
                    <tr>
						<th>
							body
						</th>
						<th>
							date
						</th>
						<th>
							extracted_text
                        </th>
						<th>
							from_number 
						</th>
						<th> 
							message_id
                        </th>
						<th>
							screenshot_image
                        </th>
						<th>
							timestamp
                        </th>
						<th>
                        	Edit
                        </th>
                    </tr>
                </thead>
				<tbody>
					<?php
					foreach($result as $row){
						?>
						<tr>
							<td><?php echo $row->body; ?></td>
							<td><?php echo $row->date; ?></td>
							<td><?php echo $row->extracted_text; ?></td>
							<td><?php echo $row->from_number; ?></td>
							<td><?php echo $row->message_id; ?></td>
							<td><?php echo $row->screenshot_image; ?></td>
							<td><?php echo $row->timestamp; ?></td>
							<td><?php echo $row->timestamp; ?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
    </div>
</div>