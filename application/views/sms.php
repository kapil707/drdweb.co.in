<link href="<?= base_url()?>/assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
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
<script src="<?= base_url()?>/assets/js/jquery-3.1.1.min.js"></script>
<script src="<?= base_url()?>/assets/js/plugins/dataTables/datatables.min.js"></script>
<!-- Page-Level Scripts -->
<script>
$(document).ready(function(){
	$('#example-table').DataTable({
        ajax: {
            url: 'https://www.drdweb.co.in/upload_sms/api01/get_upload_sms?from_date=2024-03-22&to_date=2024-03-24',
            dataSrc: ''
        },
        columns: [
            { data: 'name', title: 'Name' },
            { data: 'age', title: 'Age' },
            { data: 'city', title: 'City' }
        ]
    });
})
</script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>