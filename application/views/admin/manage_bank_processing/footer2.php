<script>
$(document).ready(function(){
	let data = [];
	<?php
	$i = 1;
	foreach ($result as $row)
	{
		$chemist_fafa = "";
		if($row->process_status=="1"){
			$chemist_fafa = '<i class="fa fa-check-circle" aria-hidden="true" style="color: green;font-size: 20px;"></i>';
		}
		if($row->process_status=="0"){
			$chemist_fafa = '<i class="fa fa-question-circle" aria-hidden="true" style="color: orange;font-size: 20px;"></i>';
		}
		$search = $row->process_name;
		
		$search_escaped = preg_quote($search, '/');
		$highlighted_text = preg_replace('/(' . $search_escaped . ')/i', '<span style="background-color: yellow;">$1</span>', $row->process_value);
		?>
		data.push(['<?= ($row->status); ?> / <?= ($row->type); ?>', '<?= ($row->date); ?>','<?= ($row->upi_no); ?><br><?= ($row->orderid); ?>','<?= ($row->amount); ?>','<?= ($row->received_from); ?><br><?= ($highlighted_text); ?>','<?= ($row->_id); ?>','<?= ($row->find_by); ?>','<?= ($row->chemist_id); ?><?= ($chemist_fafa); ?>','<?= ($row->process_invoice); ?>','<a href="<?= base_url(); ?>admin/<?php echo $Page_name ?>/edit/<?= ($row->id); ?>">Edit</a>']);
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