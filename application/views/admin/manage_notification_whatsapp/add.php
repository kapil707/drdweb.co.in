<div class="row">
	<div class="col-xs-12">
        <a href="<?php echo base_url(); ?>admin/<?= $Page_name ?>/view">
		    <button type="button" class="btn btn-w-m btn-info"><< Back</button>
		</a>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
			<div class="form-group">				
				<div class="col-sm-6">
					<div class="col-sm-4 text-right">
						<label class="control-label" for="form-field-1">
							Enter Name / Altercode
						</label>
					</div>
					<div class="col-sm-8">

						<input type="hidden" id="find_chemist_id" name="find_chemist_id"/>

						<input type="text" class="form-control" id="chemist_name" name="chemist_name" tabindex="1" onkeydown="find_chemist()" onkeyup="find_chemist()" placeholder="Enter Name / Altercode" autocomplete="off" />

						<div class="find_chemist_result" style="position: absolute;z-index: 1;background: white;width: 300px;"></div>
					</div>
					<div class="help-inline col-sm-12 has-error">
						<span class="help-block reset middle">
							<?= form_error('altercode'); ?>
						</span>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Message
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <textarea type="text" class="form-control" id="form-field-1" placeholder="Message" name="message" value="" required="required"><?= set_value('message'); ?></textarea>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('message'); ?>
                        </span>
                    </div>
                </div>
				
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Media
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="form-field-1" placeholder="Media" name="media" value="" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('message'); ?>
                        </span>
                    </div>
                </div>
          	</div>

            <div class="space-4"></div>
            <br /><br />
            <div class="clearfix form-actions">
                <div class="col-md-offset-3 col-md-9">
                    <button type="submit" class="btn btn-info submit_button" name="Submit">
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
        </form>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
<script>
function find_chemist()
{	
	chemist_name = $("#chemist_name").val();
	$(".find_chemist_result").html("Loading....");
	if(chemist_name=="")
	{
		$(".find_chemist_result").html("");
	}
	else
	{
		$.ajax({
            type       : "POST",
            data       : {chemist_name:chemist_name},
            url        : "<?= base_url()?>admin/<?= $Page_name?>/find_chemist",
            cache	   : false,
            dataType: 'json',
            success    : function(response){
                if (response.success === "1") {
                    // Display success message
                    //$('#message').text(response.message);

                    // Display items in the data container
                    let htmlContent = '<ul>';
                    response.items.forEach(item => {
                        htmlContent += `<li onlcick="add_chemist('${item.chemist_id}','${item.chemist_name}')">Name: ${item.chemist_name} - (Chemmist Id :${item.chemist_id})</li>`;
                    });
                    htmlContent += '</ul>';
                    $('.find_chemist_result').html(htmlContent);
                } else {
                    $('.find_chemist_result').text("Failed to load data.");
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('.find_chemist_result').text("Error loading data.");
            }
        });
	}
}
function add_chemist(chemist_id,chemist_name)
{
	$("#find_chemist_id").val(chemist_id);
	$("#chemist_name").val(chemist_name);
	$(".find_chemist_result").html("");
}
</script>