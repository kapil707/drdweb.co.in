<div class="row">
	<div class="col-xs-12">
	    <a href="<?php echo base_url(); ?>admin/<?= $Page_name ?>/view">
			<button type="button" class="btn btn-w-m btn-info"><< Back</button>
		</a>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
        <?php
        foreach ($result as $row)
        { ?>
            <div class="form-group">
           		<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Select Item
                        </label>
                    </div>
                    <div class="col-sm-8">
						<input type="hidden" id="item_code" name="item_code" value="<?= $item_code = $row->i_code?>"/>
						<?php 
						$row1 =  $this->db->query("select item_name,item_code from tbl_medicine where i_code='$item_code'")->row();
						?>
						<input type="text" class="form-control" id="item_name" name="item_name"tabindex="1" onkeydown="call_search_item()" onkeyup="call_search_item()" placeholder="Select Item" autocomplete="off" value="<?= $row1->item_name?> (<?= $row1->item_code?>)" />
						<div class="call_search_item_result" style="position: absolute;z-index: 1;background: white;width: 300px;"></div>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('itemid'); ?>
                        </span>
                    </div>
                </div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Status
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <select name="status" id="status" data-placeholder="Select Status" class="form-control">
							<option value="1" <?php if($row->status==1) { ?> selected <?php } ?>>
								Active
							</option>
							<option value="0" <?php if($row->status==0) { ?> selected <?php } ?>>
								Inactive
							</option>
						</select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">  
                            <?= form_error('status'); ?>
                        </span>
                    </div>
                </div>
			</div>
            
            <div class="space-4"></div>
            <br /><br />
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn btn-info" name="Submit">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Submit
                    </button>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Reset
                    </button>
                </div>
            </div>
            <?php 
			} ?>
        </form>
		<br><br>
		<?php
		$item_code = $row->item_code;
		$items = "";
		$php_files = glob('./uploads/manage_medicine_use/'.$item_code.'/*');
		foreach($php_files as $file) {
			$file = str_replace("./","",$file);
			//$file = base_url().$file;
			
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if($ext=="jpg"){
				$file_type = "image";
			}
			if($ext=="mp4"){
				$file_type = "video";
			}
			
			if($file_type) {
				$q = $this->db->query("select * from tbl_medicine_use_child where item_code='$item_code' and file_type='$file_type' and  file='$file'")->row();
				if(empty($q)){
					$this->db->query("insert into tbl_medicine_use_child (item_code,file_type,file) values ('$item_code','$file_type','$file')");
				}
			}
		}
		$r = $this->db->query("select * from tbl_medicine_use_child where item_code='$item_code' order by file_type asc")->result();
		foreach($r as $row){
			if($row->file_type=="image"){
				?>
				<div class="col-sm-2 medicine_use_div">
					<img src="<?php echo base_url().$row->file; ?>" width="100%">
				</div>
				<?php
			}
			if($row->file_type=="video"){ ?>
				<div class="col-sm-4 medicine_use_div1">
					<video width="100%" height="250" controls="" poster="">
						<source src="<?php echo base_url().$row->file; ?>" type="video/mp4">
						Your browser does not support the video tag.
					</video>
				</div>
			<?php
			}
		}
		?>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
<style>
.medicine_use_div img {
    object-fit: contain;
    height: 200px;
}
.medicine_use_div1 video {
    width: 100%;
    height: 250px;
    border-radius: 10px;
}
</style>
<script>
function call_search_item()
{	
	item_name = $("#item_name").val();
	$(".call_search_item_result").html("Loading....");
	$.ajax({
	type       : "POST",
	data       :  {item_name:item_name},
	url        : "<?= base_url()?>admin/<?= $Page_name?>/call_search_item",
	cache	   : false,
	success    : function(data){
		$(".call_search_item_result").html(data);
		}
	});
}
function additem(item_code,name)
{
	name = atob(name);
	$("#item_code").val(item_code);
	$("#item_name").val(name);
	$(".call_search_item_result").html("");
}
</script>
<script>
var delete_rec1 = 0;
function delete_photo(path,type_me)
{
	if (confirm('Are you sure Delete?')) { 
	if(delete_rec1==0)
	{
		delete_rec1 = 1;
		$.ajax({
			type       : "POST",
			data       :  { path : path , type_me : type_me ,} ,
			url        : "<?= base_url()?>admin/<?= $Page_name; ?>/delete_photo",
			success    : function(data){
					if(data!="")
					{
						$(".img_id_"+type_me).hide();
						$(".old_"+type_me).val('');
						java_alert_function("success","Photo Deleted");
						$('.url_error').html("Photo Deleted");
						
						java_alert_function("success","Delete Successfully");
						$("#imgchange").html('<img src="<?= $url_path ?>default.jpg" class="img-responsive" />');
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

//setTimeout('url_change()',1000);
function url_change()
{
	name = $(".name1").val();
	name = name.replace(/&/g,'and');
	name = name.trim(name).replace(/ /g,'-');
	name = encodeURI(name).replace(/[!\/\\#,+()$~%.'":*?<>{}]/g,'');
	$(".url1").html(name)
	$(".url").val(name)
	a_href_change(name)
}
function a_href_change(name)
{
	document.getElementById("url1").href= "<?= base_url(); ?>products/"+name+".html"; 
}

var error2 = 1;
function change_url()
{
	error2 = 0;
	disabled_submit_button();
	$('.url_error').html("");
	url1 = $('.url').val();
	
	name = url1;
	name = name.replace(/&/g,'and');
	name = name.trim(name).replace(/ /g,'-');
	name = encodeURI(name).replace(/[!\/\\#,+()$~%.'":*?<>{}]/g,'');
	$(".url1").html(name)
	$(".url").val(name)
	a_href_change(name)
	
	$.ajax({
	type       : "POST",
	data       :  { url1 : url1,id : '<?= $row->id; ?>',} ,
	url        : "<?= base_url()?>admin/<?= $Page_name?>/change_url",
	success    : function(data){
			if(data!="")
			{
				//java_alert_function("success","Delete Successfully");
				//$('.product_code_error').html("This Product SKU Already Taken");
				if(data=="Error")
				{
					java_alert_function("error","This Product Url Already Taken")
					$('.url_error').html("This Product Url Already Taken");
				}
				if(data=="ok")
				{
					java_alert_function("success","Product Url Ok");
					$('.url_error').html("Product Url Ok");
					error2 = 1;
					disabled_submit_button();
				}
			}					
			else
			{
				java_alert_function("error","Something Wrong")
				$('.url_error').html("Something Wrong");
			}
		}
	});
}

function disabled_submit_button()
{
	if(error2==1)
	{
		$(".submit_button").prop('disabled', false);
	}
	else
	{
		$(".submit_button").prop("disabled", true);
	}
}
</script>