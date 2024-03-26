<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<link href="<?= base_url()?>/assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<div class="container pt-5">
	<div class="row">
        <div class="col-md-3">
			<label for="date-range">Select Date Range:</label>
    		<input type="text" id="date-range" class="form-control">
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
<script src="https://cdn.jsdelivr.net/npm/jquery"></script>
<script src="https://cdn.jsdelivr.net/npm/moment"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker"></script>
<script src="<?= base_url()?>/assets/js/plugins/dataTables/datatables.min.js"></script>
<!-- Page-Level Scripts -->
<script>
$(document).ready(function(){
	$('#date-range').daterangepicker({
		opens: 'left', // Date picker position
		locale: {
			format: 'DD-MM-YYYY', // Date format
			separator: ' to ',
			applyLabel: 'Apply',
			cancelLabel: 'Cancel',
			daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
			monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
		}
	});

	var table = $('#dataTables-example').DataTable({
        ajax: {
		url: 'https://www.drdweb.co.in/upload_sms/api01/get_upload_sms',
			type: 'POST',
			data: function(d) {
                var selectedDates = $('#date-range').val().split(' to ');
                if (selectedDates.length === 2) {
                    d.from_date = selectedDates[0].trim();
                    d.to_date = selectedDates[1].trim();
                }
                return d;
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

	$('#date-range').on('apply.daterangepicker', function(ev, picker) {
        table.draw();
    });
})
</script>