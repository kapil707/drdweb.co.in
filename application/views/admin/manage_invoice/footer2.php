<script>
$(document).ready(function(){
	let data = [];
	<?php
	$i = 1;
	foreach ($result as $row)
	{
		$date = date("d-M-y",strtotime($row->date));
		$amt = money_format('%!i',$row->amt);
		$status = "Not Report Send";
		if($row->status){
			$status = "Report Send";
		}
		?>
		data.push(['<?= ($i++); ?>','<?= ($row->gstvno); ?>','<?= ($date); ?>','<?= ($row->chemist_id); ?>','<?= ($amt); ?>','<?php echo $row->pickedby?>','<?php echo $row->checkedby?>','<?php echo $row->deliverby?>','<?php echo $status?>','<a href="http://www.drdistributor.com/invoice/<?= $row->chemist_id;?>/<?= $row->gstvno;?>" class="btn-white btn btn-xs" target="_blank">View</a>']);
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