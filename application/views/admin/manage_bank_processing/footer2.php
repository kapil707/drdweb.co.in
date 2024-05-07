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

		$invoice_chemist = "";
		$process_invoice = "";
		$fruits_array = explode(",", $row->process_invoice);
		foreach($fruits_array as $rows){
			$process_invoice.= $rows."<br>";

			$arr = explode(":-",$rows);
			$invoice_chemist = $arr[0];
		}
		
		$find = "find by ";
		if(!empty($row->process_invoice)){
			$find.= "<b>invoice</b>,";
		}
		if(!empty($row->chemist_id)){
			$find.= "<b>chemist</b>";
		}
		if(empty($process_invoice) && empty($row->chemist_id)){
			$find = "N/A";
		}
		$chemist_dt = $row->chemist_id." ".$chemist_fafa; 
		// if(!empty($invoice_chemist) &&  !empty($row->chemist_id)){
		// 	$chemist_dt.="<br>".$invoice_chemist;
		// }
		$find_all = "";
		if(($row->chemist_id==$invoice_chemist) && (!empty($invoice_chemist) && !empty($row->chemist_id))){
			$find_all = "done";
		}
		?>
		$(".myhiden_data_for_modal").append("<p class='myhiden_data_for_modal_id<?= ($row->id); ?>' chemist_id='<?= ($row->chemist_id); ?>' process_invoice='<?= ($row->process_invoice); ?>' find_by='<?= ($row->find_by); ?>' find='<?= ($find); ?>'></p>")

		data.push(['<?= ($row->status); ?> / <?= ($row->type); ?>', '<?= ($row->date); ?>','<?= ($row->upi_no); ?><br><?= ($row->orderid); ?>','<?= ($row->amount); ?>','<?= ($row->received_from); ?><br><?= ($highlighted_text); ?>','<?= ($row->find_by); ?>','<?= ($chemist_dt); ?>','<?= ($process_invoice); ?>','<?= ($find); ?>','<?= ($find_all); ?>','<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" onClick="model_data_add(<?= ($row->id); ?>)">Open Modal</button>']);
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

function model_data_add(id){
	chemist_id = $(".myhiden_data_for_modal_id"+id).attr("chemist_id")
	process_invoice = $(".myhiden_data_for_modal_id"+id).attr("process_invoice")
	find_by = $(".myhiden_data_for_modal_id"+id).attr("find_by")
	find = $(".myhiden_data_for_modal_id"+id).attr("find")

	var chemist_id_array = chemist_id.split(",");
	var chemist_id_val = "";
	for (i=0;i<chemist_id_array.length;i++){
		chemist_id_val+="<li onclick='add_chemist_id(\""+chemist_id_array[i]+"\")'>"+chemist_id_array[i]+"</li>";
	}

	var process_invoice_array = process_invoice.split(",");
	var process_invoice_val = "";
	for (i=0;i<process_invoice_array.length;i++){
		process_invoice_val+="<li>"+process_invoice_array[i]+"</li>";
	}

	$(".main_modal_title").html(find)
	$(".main_modal_p").html("<div class='row'><div class='col-sm-6'>Chemist find by Server : <ul>"+chemist_id_val+"</ul></div><div class='col-sm-6'>Invoice find by Server : <ul>"+process_invoice_val+"</ul></div></div>")
}
function add_chemist_id(id) {
    // Use the 'id' parameter here
    console.log("chemist_id: " + id);
}
function onchange_add_new_chemist(){
	add_new_chemist = $(".add_new_chemist").val();
	console.log(add_new_chemist)
}
</script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>