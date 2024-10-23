<div class="row">
	<div class="col-xs-12">
		<button type="button" class="btn btn-w-m btn-info" onclick="goBack();"><< Back</button>
	</div>
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <form class="form-horizontal" role="form" method="post">
        <?php
        foreach ($result as $row)
        { ?> <div class="form-group">
           		<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Add Replyto
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="form-field-1" placeholder="Add Replyto" name="addreplyto" value="<?= $row->addreplyto; ?>" required="required" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle"> 
                            <?= form_error('addreplyto'); ?>
                        </span>
                    </div>
                </div>	
				
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Add Replyto Name
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="form-field-1" placeholder="Add Replyto Name" name="addreplyto_name" value="<?= $row->addreplyto_name; ?>" required="required" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle"> 
                            <?= form_error('addreplyto_name'); ?>
                        </span>
                    </div>
                </div>
			</div>
				
			<div class="form-group">
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Server Email
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="form-field-1" placeholder="Server Email" name="server_email" value="<?= $row->server_email; ?>" required="required" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle"> 
                            <?= form_error('server_email'); ?>
                        </span>
                    </div>
                </div>	
				
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Server Email Name
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="form-field-1" placeholder="Server Email Name" name="server_email_name" value="<?= $row->server_email_name; ?>" required="required" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle"> 
                            <?= form_error('server_email_name'); ?>
                        </span>
                    </div>
                </div>
			</div>
				
			<div class="form-group">
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Email
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="form-field-1" placeholder="Email" name="email" value="<?= $row->email; ?>" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle"> 
                            <?= form_error('email'); ?>
                        </span>
                    </div>
                </div>
				
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Email Bcc
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="form-field-1" placeholder="Email Bcc" name="email_bcc" value="<?= $row->email_bcc; ?>" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle"> 
                            <?= form_error('email_bcc'); ?>
                        </span>
                    </div>
                </div>				
			</div>
				
			<div class="form-group">
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Email Function
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <input type="name" class="form-control" id="form-field-1" placeholder="Email Function" name="email_function" value="<?= $row->email_function; ?>" required="required" />
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle"> 
                            <?= form_error('email_function'); ?>
                        </span>
                    </div>
                </div>				
				<div class="col-sm-6">
                    <div class="col-sm-4 text-right">
                        <label class="control-label" for="form-field-1">
                            Live Or Demo
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <select name="live_or_demo" id="live_or_demo" data-placeholder="Select User Type" class="chosen-select">
							<option value="Demo" <?php if($row->live_or_demo=="Demo") { ?> selected <?php } ?>>							
								Demo
							</option>
							<option value="Live" <?php if($row->live_or_demo=="Live") { ?> selected <?php } ?>>							
								Live
							</option>
						</select>
                    </div>
                    <div class="help-inline col-sm-12 has-error">
                        <span class="help-block reset middle">
                            <?= form_error('live_or_demo'); ?>
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
                        <select name="status" id="status" data-placeholder="Select Status" class="chosen-select">
							<option value="0" <?php if($row->status==0) { echo "selected"; } ?>>
                                Inactive
							</option>
							<option value="1" <?php if($row->status==1) { echo "selected"; } ?>>							
                                Active
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
            <?php } ?>
        </form>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->