<div class="container" style="margin-top:-10px;">
	<div class="row">
		<div class="col-sm-1"></div>
		<div class="col-sm-10 col-12">
			<div class="row">
				<div class="col-sm-12 col-12 load_page">
					
				</div>
				<div class="col-sm-12 load_page_loading" style="margin-top:10px;">
				
				</div>
				<div class="col-sm-12" style="margin-top:10px;">
					<button onclick="call_page_by_last_id()" class="load_more btn btn-success btn-block site_main_btn">Load More</button>
				</div>
			</div>
		</div>
		<div class="col-sm-1"></div>
	</div>     
</div>
<input type="hidden" class="lastid1">
<script>
$(document).ready(function(){
	call_page("kapil");
});
function call_page_by_last_id()
{
	lastid1=$(".lastid1").val();
	call_page(lastid1)
}
function call_page(lastid1)
{
	$(".load_more").hide();
	$(".load_page_loading").html('<h1><center><img src="<?= base_url(); ?>/img_v<?= constant('site_v') ?>/loading.gif" width="100px"></center></h1><h1><center>Loading....</center></h1>');
	$.ajax({
		type       : "POST",
		data       :  { lastid1:lastid1,user_type:'<?= $user_type; ?>',user_altercode:'<?= $user_altercode; ?>'} ,
		url        : "<?php echo base_url(); ?>android/api_mobile31/my_invoices_api",
		cache	   : false,
		error: function(){
			$(".load_page_loading").html('<h1><img src="<?= base_url(); ?>img_v<?= constant('site_v') ?>/something_went_wrong.png" width="100%"></h1>');
		},
		success    : function(data){
			if(data.items=="")
			{
				$(".load_page_loading").html('<h1><center><img src="<?= base_url(); ?>/img_v<?= constant('site_v') ?>/no_record_found.png" width="100%"></center></h1>');
			}
			else
			{
				$(".load_page_loading").html("");
			}
			$.each(data.items, function(i,item){	
				if (item){
					$(".load_page").append('<li class="list_item_radius"><a href="<?= base_url(); ?>android/api_mobile_html31/my_invoices_view/'+item.url+'"><div class="row"><div class="col-sm-6 col-6 search_page_title">'+item.gstvno+'</div><div class="col-sm-6 col-6 text-right all_page_date_time">'+item.date_time+'</div><div class="col-sm-6 col-6 all_page_total">Total: '+item.total+'</div><div class="col-sm-6 col-6 text-right search_page_stock">'+item.status+'</div></div></a></li>');
					$(".lastid1").val(item.gstvno);
					if(item.css!="")
					{
						$(".load_more").show();
					}
				}
			});
		},
		timeout: 10000
	});
}
</script>