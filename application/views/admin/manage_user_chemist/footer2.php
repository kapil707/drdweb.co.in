<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
var table;
$(document).ready(function(){
	table = $('#example-table').DataTable({
		processing: true,
  		serverSide: true,
		ajax: {
		url: '<?php echo base_url(); ?>admin/<?php echo $Page_name ?>/view_api_server',
			type: 'POST',
			dataSrc: 'items'
		},
		order: [[0, 'asc']],
		columns: [
			{ data: 'sr_no', title: 'Id' },
			{ data: 'code', title: 'Chemist Code' },
			{ data: 'chemist_id', title: 'Chemist Id' },
			{ data: 'name', title: 'Name' },
			{ data: 'mobile', title: 'Mobile' },
			{ data: 'email', title: 'Email' },
			{
				data: 'image',
				title: 'Image',
				render: function (data, type, row) {
					if (data) {
						return `<img src="${data}" alt="Image" style="width: 100px; ">`;
					} else {
						return 'No Image';
					}
				}
			},
			{
				data: null,
				title: 'Address',
				render: function (data, type, row) {
					let fullAddress = '';
					fullAddress += row.address ? row.address + ', ' : '';
					fullAddress += row.address1 ? row.address1 + ', ' : '';
					fullAddress += row.address2 ? row.address2 + ', ' : '';
					fullAddress += row.address3 ? row.address3 : '';
					return fullAddress.replace(/,\s*$/, ''); // remove trailing comma
				}
			},
			{
				data: null,
				title: 'Website / Android Limit',
				render: function (data, type, row) {
					let fulltext = '';
					fulltext += row.website_limit ? row.website_limit + ' / ' : '';
					fulltext += row.android_limit ? row.android_limit + '' : '';
					return fulltext.replace(/,\s*$/, ''); // remove trailing comma
				}
			},
			/*{ data: 'timestamp', title: 'DateTime' },*/
			{
				data: null,
				title: 'Action',
				orderable: false,
				render: function (data, type, row) {
					return `
						<a href="<?php echo base_url(); ?>admin/<?php echo $Page_name ?>/edit/${row.id}" class="btn-white btn btn-xs">Edit</a>`;
				}
			},
			{
				data: null,
				title: 'Logout',
				orderable: false,
				render: function (data, type, row) {
					return `
						<a href="javascript:logout_function('${row.chemist_id}');" class="btn-white btn btn-xs">Logout</a>`;
				}
			}
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
function reload_page(){

	table.ajax.reload();
	setInterval(function () {
		reload_page();
	}, 120000);
}
/*var delete_rec1 = 0;
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
						reload_page();
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
}*/
</script>
<script src="https://cdn.datatables.net/scroller/2.2.0/js/dataTables.scroller.min.js"></script>