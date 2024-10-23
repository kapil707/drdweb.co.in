<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
		<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a>
   	</div>
	<form method="get">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="col-md-3">
				<label for="date-range">Select Date Range:</label>
				<input type="text" id="date-range" class="form-control">
			</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
			<button type="submit" name="submit" class="btn btn-success notika-btn-success waves-effect" value="submit">Submit</button>
		</div>
	</form>
    <div class="col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover dataTables-example">
                <thead>
                    <!-- <tr>
                    	<th>
                        	Sno.
                        </th>
						<th>
							Mobile
						</th>
						<th>
							Message
						</th>
						<th>
							Date / Time
						</th>
						<th>
							Action
						</th>
                    </tr> -->
                </thead>
				<tbody>
                <?php
				/*$i=1;
                foreach ($result as $row)
                {
					?>
                    <tr id="row_<?= $row->id; ?>">
                    	<td>
                        	<?= $i++; ?>
                        </td>
						<td>
							<?= $row->mobile; ?>
                        </td>
						<td>
							<?= ($row->message); ?>
                        </td>
						<td>
							<?= date("d-M-y",strtotime($row->date))?>
							<?= $row->time; ?>
                        </td>
						<td>
							<a href="javascript:void(0)" onclick="delete_rec('<?= $row->id; ?>')" class="btn-white btn btn-xs">Delete</i> </a>
						</td>
                    </tr>
                    <?php
                    }*/
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
var delete_rec1 = 0;
function delete_rec(id)
{
	if (confirm('Are you sure Delete?')) { 
	if(delete_rec1==0)
	{
		delete_rec1 = 1;
		$.ajax({
			type       : "POST",
			data       :  { id : id ,} ,
			url        : "<?= base_url()?>admin/<?= $Page_name; ?>/delete_rec",
			success    : function(data){
					if(data!="")
					{
						java_alert_function("success","Delete Successfully");
						$("#row_"+id).hide("500");
					}					
					else
					{
						java_alert_function("error","Something Wrong")
					}
					delete_rec1 = 0;
				}
			});
		}
	}
}
</script>