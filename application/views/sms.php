<link href="<?= base_url()?>/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url()?>/assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<div class="container">
	<div class="row">
		<div class="col-sm-12  pt-5">
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
<script src="<?= base_url()?>/assets/js/plugins/dataTables/datatables.min.js"></script>
<!-- Page-Level Scripts -->
<script>
$(document).ready(function(){
	$('#example-table').DataTable({
        ajax: {
            url: 'https://www.drdweb.co.in/upload_sms/api01/get_upload_sms',
            type: 'POST',
            data: function(d) {
                return $.extend({}, d, {
                    from_date: '2024-03-22',
                    to_date: '2024-03-24'
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
})
</script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>