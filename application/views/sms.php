<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" rel="stylesheet">
<div class="container pt-5">
	<div class="row">
        <div class="col-md-3">
			<label for="start-date">Start Date:</label>
            <input type="date" id="start-date" class="form-control" placeholder="Start Date:">
        </div>
        <div class="col-md-3">
			<label for="start-date">Start Date:</label>
            <input type="date" id="end-date" class="form-control"  placeholder="End Date:">
        </div>
        <div class="col-md-2">
			<label for="start-date">Submit:</label>
            <button id="filter-btn" class="btn btn-primary btn-block">Submit</button>
        </div>
    </div>
	<div class="row">
		<div class="col-sm-12  pt-1 pb-5">
			<table class="table table-striped table-bordered table-hover dataTables-example" id="example-table">
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
					</tr>
				</thead>
				<tbody>
				
				</tbody>
			</table>
		</div>
	</div>
</div>
<script src="<?= base_url()?>/assets/js/jquery-3.1.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<!-- Page-Level Scripts -->
<script>
$(document).ready(function(){
	$('#filter-btn').on('click', function() {
        var startDate = $('#start-date').val();
        var endDate = $('#end-date').val();
		load_datatable(startDate,endDate)
    });
	function load_datatable(from_date,to_date){
		$('#example-table').DataTable({
			ajax: {
				url: 'https://www.drdweb.co.in/upload_sms/api01/get_upload_sms',
				type: 'POST',
				data: function(d) {
					return $.extend({}, d, {
						from_date: from_date,
						to_date: to_date
					});
				},
				dataSrc: 'items'
			},
			columns: [
				{ data: 'id', title: 'ID' },
				{ data: 'sender', title: 'Sender' },
				{ data: 'message_body', title: 'Message Body' },
				{ data: 'date', title: 'Date' },
				{ data: 'time', title: 'Time' }
			],
			pageLength: 25,
			responsive: true,
			dom: '<"html5buttons"B>lTfgitp',
			buttons: [
				{extend: 'copy'},
				{extend: 'csv'},
				{extend: 'excel', title: 'ExampleFile'},
				{extend: 'pdf', title: 'ExampleFile'},
				{extend: 'print',
					customize: function (win){
						$(win.document.body).addClass('white-bg');
						$(win.document.body).css('font-size', '10px');
						$(win.document.body).find('table')
								.addClass('compact')
								.css('font-size', 'inherit');
				}
				}
			]
		});
	}
})
</script>