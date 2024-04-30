<script>
$(document).ready(function(){
	let data = [];
	<?php
	$i = 1;
	foreach ($result as $row)
	{
		$fafa = "";
		if($row->find_by=="Chemist Table"){
			$fafa = "<i class='fa fa-check' aria-hidden='true'></i>";
		}

		?>
		data.push(['<?= ($row->status); ?>', '<?= ($row->amount); ?>','<?= ($row->date); ?>','<?= ($row->received_from); ?>','<?= ($row->upi_no); ?>','<?= ($row->orderid); ?>','<?= ($row->type); ?>','<?= ($row->_id); ?>','<?= ($row->find_by); ?>','<?= ($fafa); ?>','<a href="<?= base_url(); ?>admin/<?php echo $Page_name ?>/edit/<?= ($row->id); ?>">Edit</a>']);
		<?php
	}
	?>
	$('#example-table').DataTable({
		data: data,
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
});
</script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>