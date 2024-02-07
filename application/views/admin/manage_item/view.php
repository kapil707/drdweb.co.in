<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>            
        </a>
   	</div>
    <div class="col-xs-12">
         <div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                    	<th>
                        	Sno.
                        </th>
						<th>
                        	Item Name
                        </th>
						<th>
							Category
                        </th>
						<th>
                        	Image
                        </th>
                        <th>
                        	Action
                        </th>
                    </tr>
                </thead>

                <tbody>
                <?php
				$i=1;
                foreach ($result as $row)
                {
					?>
                    <tr id="row_<?= $row->id; ?>">
                    	<td>
                        	<?= $i++; ?>
                        </td>
 						<td>
							<?php 
							$i_code = $row->i_code;
							$row1 =  $this->db->query ("select image1,item_name,item_code from tbl_medicine where i_code='$i_code'")->row();?>	
                        	<?= ($row1->item_name); ?> (<?= ($row1->item_code); ?>)
                        </td>
						<td>
							<?php 
							$category_id = $row->category_id;
							$row2 =  $this->db->query ("select name from tbl_item_category where id='$category_id' and status=1")->row();?>	
                        	<?= ($row2->name); ?>
                        </td>
						<td>
							<?php $item_image = constant('img_url_site').$row1->image1; ?>
							<?php $url = $item_image; ?>
                        	<img src="<?= $url; ?>" width="100px" />
                        </td>						
						<td class="text-right">
							<div class="btn-group">
								<a href="edit/<?= $row->id; ?>?pg=<?= $_GET["pg"]; ?>" class="btn-white btn btn-xs">Edit
								</a>
								<?php if($count==0 && $count1==0) { ?>
								<a href="javascript:void(0)" onclick="delete_rec('<?= $row->id; ?>')" class="btn-white btn btn-xs">Delete</i> </a>
								<?php } ?>
							</div>
                        </td> 
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
var delete_rec1 = 0;
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
						$("#row_"+id).hide("500");
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
}


$(".for_per,.previous,.next,.last,.fast").click(function(){
	for_per=$(this).attr("ford");
	per_page=$(this).attr("per_page");
	url="<?php echo base_url() ?>admin/<?= $Page_name; ?>/view?pg="+per_page;
	window.location.href=url;
  
  });
  $(".page_btn").click(function(){
  	//alert("ok");
 // per_page="<?php echo $per_page ?>";
// for_per=$(this).attr("ford");
  //per_page=$(this).attr("per_page");
 page_no=$('.page_no').val();

 bysearch=0;
 if(page_no!=""&&page_no!=0){
 	for_per=page_no-1;
  per_page=for_per*100;
  bysearch=1;
 }
 url="<?php echo base_url() ?>admin/<?= $Page_name; ?>/view?pg="+per_page;
	window.location.href=url;
  });
</script>