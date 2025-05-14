<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
        <a href="<?php echo base_url(); ?>admin/<?= $Page_name ?>/order_limit">
            <button type="submit" class="btn btn-info">
                Order Limit           
            </button>        
        </a>
    </div>
    <div class="col-xs-12">
		<div class="row">
			<div class="col-sm-12  pt-1 pb-5">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="example-table">
						<thead>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
function logout_function(chemist_id){	
    $.ajax({		
        type       : "POST",		
        data       :  {chemist_id:chemist_id},		
        url        : "<?php echo base_url(); ?>admin/<?= $Page_name ?>/chemist_logout",		
        cache	   : false,		
        success    : function(data){			
            if(data!="")			
            {				
                swal("Error");			
            }
            $.each(data.items, function(i,item){				
                if (item){					
                    swal("Logout Successfully");				
                }			
            });			
        }	
    });
}
</script>