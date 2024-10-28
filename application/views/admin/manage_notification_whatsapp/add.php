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

                        <input type="text" class="form-control" id="chemist_name" name="chemist_name" tabindex="1" placeholder="Enter Name / Altercode" autocomplete="off" />

						<div class="find_chemist_result"></div>
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
<style>
.find_chemist_result {
    position: absolute;
    z-index: 1;
    background: white;
    max-height: 300px;
    overflow: auto;
    width: 100%; /* Adjust to span the width of the input */
}

/* Style the unordered list */
.find_chemist_result ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

/* Style each list item */
.find_chemist_result ul li {
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    cursor: pointer;
    transition: background-color 0.3s;
}

/* Highlight active list item */
.find_chemist_result ul li.active,
.find_chemist_result ul li:hover {
    background-color: #e6e6e6;
    font-weight: bold;
}
</style>

<script>
let currentFocus = -1; // Tracks the currently focused item

$(".chemist_name").keyup(function(e){
    let chemist_name = $("#chemist_name").val();
    $(".find_chemist_result").html("Loading....");

    if (chemist_name.length < 2) {
        $(".find_chemist_result").html(""); // Clear results if input is too short
        return; // Exit if fewer than 2 characters
    }

    $.ajax({
        type: "POST",
        data: {chemist_name: chemist_name},
        url: "<?= base_url()?>admin/manage_user_chemist/find_chemist",
        cache: false,
        dataType: 'json',
        success: function(response) {
            if (response.success === "1") {
                let htmlContent = '<ul>';
                response.items.forEach(item => {
                    htmlContent += `<li onclick="add_chemist('${item.chemist_id}', '${item.chemist_name}')">Name: ${item.chemist_name} - (Chemist ID: ${item.chemist_id})</li>`;
                });
                htmlContent += '</ul>';
                $('.find_chemist_result').html(htmlContent);
                currentFocus = -1; // Reset focus
            } else {
                $('.find_chemist_result').text("Failed to load data.");
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('.find_chemist_result').text("Error loading data.");
        }
    });
});

function add_chemist(chemist_id, chemist_name) {
    $("#find_chemist_id").val(chemist_id);
    $("#chemist_name").val(`Name: ${chemist_name} - (Chemist ID: ${chemist_id})`);
    $(".find_chemist_result").html("");
}

// Keyboard navigation function
$("#chemist_name").on("keydown", function(e) {
    let listItems = $(".find_chemist_result ul li");

    if (e.key === "ArrowDown") {
        e.preventDefault();
        currentFocus++;
        if (currentFocus >= listItems.length) currentFocus = 0; // Loop back to top
        addActive(listItems);
    } else if (e.key === "ArrowUp") {
        e.preventDefault();
        currentFocus--;
        if (currentFocus < 0) currentFocus = listItems.length - 1; // Loop back to bottom
        addActive(listItems);
    } else if (e.key === "Enter") {
        e.preventDefault();
        if (currentFocus > -1) {
            listItems[currentFocus].click(); // Trigger click on the selected item
        }
    }
});

function addActive(listItems) {
    listItems.removeClass("active");
    if (currentFocus >= 0 && currentFocus < listItems.length) {
        listItems.eq(currentFocus).addClass("active");
    }
}
</script>