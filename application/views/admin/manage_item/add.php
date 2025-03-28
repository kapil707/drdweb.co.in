<div class="row">
	<div class="col-xs-12">
		<button type="button" class="btn btn-w-m btn-info" onclick="goBack();"><< Back</button>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
			<div class="form-group">	
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Select Medicine
                        </label>
                    </div>
                    <div class="col-sm-8">
						<input type="hidden" id="find_medicine_id" name="find_medicine_id" value="" />

						<input type="text" class="form-control" id="medicine_name" name="medicine_name" tabindex="1" placeholder="Enter Medicine" autocomplete="off" value="" />

						<div class="find_medicine_result"></div>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            
                        </span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Category
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <select name="category_id" id="category_id" data-placeholder="Select Status" class="form-control" required>
							<option value="0">
                                Select Category
							</option>
                            <?php 
                            $result1 =  $this->db->query ("select * from tbl_item_category where status=1 and isdefault=0")->result(); 
                            foreach($result1 as $row1) {?>
                            <option value="<?php echo $row1->id;?>" <?php if(set_value('category_id')==$row1->id) { ?> selected <?php } ?>>
                                <?php echo $row1->title;?>
							</option>
                            <?php } ?>
						</select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            
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
							<option value="1" <?php if(set_value('status')==1) { ?> selected <?php } ?>>
								Active
							</option>
							<option value="0" <?php if(set_value('status')==0) { ?> selected <?php } ?>>
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