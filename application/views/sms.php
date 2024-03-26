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

	from_date = "2024-03-23";
	to_date   = "2024-03-26";

	let mydata = [];
	$.ajax({
		type       : "POST",
		data       : {from_date:from_date,to_date:to_date} ,
		url        : "<?php echo base_url(); ?>upload_sms/api01/get_upload_sms",
		cache	   : false,
		error: function(){
		},
		success    : function(data){
			console.log(data);
			if(data.items=="")
			{
				$.each(data.items, function(i,item){
					if (item){
						id 			= item.id;
						sender 		= item.sender;
						message_body= item.message_body;
						date 		= item.date;
						time 		= item.time;
						datetime 	= item.datetime;

						mydata.push(id,sender,message_body,date,time);
					}
				});
				load_datatable(mydata)
			}
		}
	});
	
	function load_datatable(mydata){
		$('#example-table').DataTable({
			data: mydata,
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
});
</script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>