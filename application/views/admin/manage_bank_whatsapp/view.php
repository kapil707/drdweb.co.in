<style>
.pg_text_box{
    width: 60px !important;
	border-radius: 10px !important;
    padding: 3px !important;
    height: 26px !important;
    border-radius: 0px !important;
    background: none !important;
    border: none !important;
    border-bottom: solid !important;
}
</style>
<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<!-- <a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a> -->
    </div>
	<?php
	$date_range = "";
	if(isset($_GET["date-range"])){
		$date_range = $_GET["date-range"];
	}
	?>
	<form method="get" class="mb-5" style="margin-bottom:10px;">
		<div class="col-md-3">
			<label for="date-range">Select Date Range:</label>
		</div>
		<div class="col-md-3">
			<input type="text" id="date-range" class="form-control" name="date-range" value="<?php echo $date_range ?>">
		</div>
		<div class="col-md-3">
		<?php
		$parmiter = '';
		$curl = curl_init();
			
			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL =>"http://192.46.214.43:5000/groups",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 0,
					CURLOPT_TIMEOUT => 300,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'GET',
					CURLOPT_POSTFIELDS => $parmiter,
					CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json',
						'Authorization: Bearer THIRTEENWOLVESWENTHUNTINGBUT10CAMEBACK'
					),
				)
			);

			$response = curl_exec($curl);
			//print_r($response);
			curl_close($curl);

			$data0 = json_decode($response, true); // Convert JSON string to associative array
			?>
			<select class="form-control" name="select_group">
				<?php 
				if (isset($data0['groups'])) {
					foreach ($data0['groups'] as $groups) {
						if(!empty($groups)){
							?>
							<option value="xxxx">
								<?php echo $groups ?>
							</option>
							<?php
						}
					}
				}
				?>
			</select>
		</div>
		<div class="col-md-3">
			<button type="submit" class="btn btn-info submit_button" name="Submit">
				<i class="ace-icon fa fa-check bigger-110"></i>
				Submit
			</button>
		</div>
	</form>
	<div class="col-xs-12">
        <div class="table-responsive">
			<table class="table table-striped table-bordered table-hover dataTables-example21">
                <thead>
                    <tr>
						<th>
							ID
						</th>
						<th>
							Date / Time
                        </th>
						<th>
							Number
						</th>
						<th>
							Body
						</th>
						<th>
							Vision Text
                        </th>
						<th>
							UPI No
                        </th>
						<th>
							Image
                        </th>
						<th>
							Chemist
                        </th>
						<th>
							Amount
                        </th>
                    </tr>
                </thead>
				<tbody>
					<?php
						foreach ($result as $message) {
							$body = $message->body ? $message->body : "Body not found";

							$reply_body = $message->reply_body ? $message->reply_body : "";
							if($reply_body)
							{
								$reply_body = "Reply Body : " .$reply_body;
							}

							$date = $message->date ? $message->date : "Date not found";

							$extracted_text = $message->extracted_text ? $message->extracted_text : "extracted_text not found";
							
							$vision_text = $message->vision_text ? $message->vision_text : "vision_text not found";

							$from_number = $message->from_number ? $message->from_number : "Date not found";
							
							$id = $message->id;
							
							$row_id = $message->id;

							$screenshot_image = $message->screenshot_image ? $message->screenshot_image : "screenshot_image not found";

							$timestamp = $message->timestamp ? $message->timestamp : "timestamp not found";
							
							$chemist_id = "";
							if(!empty($message->find_chemist))
							{
								$chemist_id = $message->find_chemist;
							}

							if(!empty($message->final_chemist))
							{
								$chemist_id = $message->final_chemist;
							}

							if(!empty($message->set_chemist))
							{
								$chemist_id = $message->set_chemist;
							}

							$extracted_text = str_replace("\n", "<br>", $extracted_text);
							$vision_text = str_replace("\n", "<br>", $vision_text);
							?>
							<tr>
								<td>
									<?php echo $id; ?>
								</td>
								<td>
									<?php echo date('d-M-y \a\t H:i:s', $timestamp); ?>
								</td>
								<td>
									<?php echo $from_number; ?>
								</td>
								<td>
									<?php echo $body; ?><br>
									<b><?php echo $reply_body ;?></b>
								</td>
								<td><?php echo $vision_text; ?></td>
								<td><?php echo $message->upi_no; ?></td>
								<td>
									<?php if(!empty($screenshot_image)) { ?>
									<b data-toggle="modal" data-target="#myModal" onclick="get_full_image('https://api.wassi.chat<?php echo $screenshot_image; ?>?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e')">	<img src="https://api.wassi.chat<?php echo $screenshot_image; ?>?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e" width="100px">
									</b>
									<?php } ?>
								</td>
								<td width="160">
									<?php if(!empty($message->find_chemist)){ ?>
										<span style="float: left;">Find Chemist : <?php echo $message->find_chemist; ?></span>
									<?php } ?>

									<?php if(!empty($message->final_chemist)){ ?>
									<span style="float: left;">Final Chemist : <?php echo $message->final_chemist; ?></span>
									<?php } ?>
									
									<?php
									if(empty($message->final_chemist)){
										?>
										<span style="float: left;">Set Chemist : </span>
										<span class="span_chemist_<?= ($row_id); ?>" style="float: left;">
											<?php echo $message->set_chemist; ?>
										</span> 

										<span class="edit_chemist_<?= ($row_id); ?>" onclick="edit_chemist('<?= ($row_id); ?>')" style="float: left;margin-left:10px;">
											Edit <i class="fa fa-pencil" aria-hidden="true"></i>
										</span>
										
										<input type="text" value="<?php echo $chemist_id; ?>" class="text_chemist_<?= ($row_id); ?> pg_text_box" style="float: left !important;display:none;" placeholder="Set Chemist Id">
									
										<span class="add_chemist_<?= ($row_id); ?>" onclick="add_chemist('<?= ($row_id); ?>')" style="float: left;display:none;"> Set
											<i class="fa fa-check" aria-hidden="true"></i>
										</span>

										<i class="fa fa-times add_chemist_cancel_<?= ($row_id); ?>" aria-hidden="true" onclick="add_chemist_cancel('<?= ($row_id); ?>')" style="float: left;font-size: 18px;display:none;"></i>
										<?php
									}
								?>
								</td>
								<td width="150px">
									<span style="float: left;">Amount : </span>
									<span class="span_amount_<?= ($row_id); ?>" style="float: left;"><?php echo $message->amount; ?></span>

									<i class="fa fa-pencil edit_amount_<?= ($row_id); ?>" aria-hidden="true" onclick="edit_amount('<?= ($row_id); ?>')" style="float: left;font-size: 13px;margin-left:10px;"></i>
									
									<input type="text" value="<?php echo $message->amount; ?>" class="text_amount_<?= ($row_id); ?> pg_text_box" style="float: left !important;display:none;" placeholder="Amount">
									
									<i class="fa fa-check add_amount_<?= ($row_id); ?>" aria-hidden="true" onclick="add_amount('<?= ($row_id); ?>')" style="float: left;font-size: 18px;display:none;"></i>

									<i class="fa fa-times add_amount_cancel_<?= ($row_id); ?>" aria-hidden="true" onclick="add_amount_cancel('<?= ($row_id); ?>')" style="float: left;font-size: 18px;display:none;"></i>
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
function edit_chemist(id){
	$(".span_chemist_"+id).hide();
	$(".edit_chemist_"+id).hide();
	
	$(".text_chemist_"+id).show();
	$(".add_chemist_"+id).show();
	$(".add_chemist_cancel_"+id).show();

	var chemist = $(".text_chemist_"+id).val();
	if(chemist=="N/a"){
		$(".text_chemist_"+id).val('');
	}
}

function add_chemist_cancel(id){
	$(".text_chemist_"+id).hide();

	$(".add_chemist_"+id).hide();
	$(".add_chemist_cancel_"+id).hide();

	$(".span_chemist_"+id).show();
	$(".edit_chemist_"+id).show();
}

function add_chemist(id){
	$(".span_chemist_"+id).show();
	$(".edit_chemist_"+id).show();
	
	$(".text_chemist_"+id).hide();
	$(".add_chemist_"+id).hide();
	
	var chemist = $(".text_chemist_"+id).val();
	if(chemist.trim()==""){
		alert(chemist)
	}else{
		$.ajax({
			type : "POST",
			data : {row_id:id,chemist:chemist,},
			url  : "<?= base_url()?>admin/<?= $Page_name?>/add_chemist",
			cache: true,
			error: function(){
				toastr.error('Error');
			},
			success: function(data){
				toastr.info('Chemist set successfully');
				$(".span_chemist_"+id).html(chemist);
			}
		});
	}
}


function edit_amount(id){
	$(".span_amount_"+id).hide();
	$(".edit_amount_"+id).hide();
	
	$(".text_amount_"+id).show();
	$(".add_amount_"+id).show();
	$(".add_amount_cancel_"+id).show();

	var amount = $(".text_amount_"+id).val();
	if(amount=="N/a" || amount=="0.0"){
		$(".text_amount_"+id).val('');
	}
}

function add_amount_cancel(id){
	$(".text_amount_"+id).hide();

	$(".add_amount_"+id).hide();
	$(".add_amount_cancel_"+id).hide();

	$(".span_amount_"+id).show();
	$(".edit_amount_"+id).show();
}

function add_amount(id){
	$(".span_amount_"+id).show();
	$(".edit_amount_"+id).show();
	
	$(".text_amount_"+id).hide();
	$(".add_amount_"+id).hide();
	
	var amount = $(".text_amount_"+id).val();
	if(amount.trim()==""){
		alert("Etne amount")
	}else{
		$.ajax({
			type : "POST",
			data : {row_id:id,amount:amount,},
			url  : "<?= base_url()?>admin/<?= $Page_name?>/add_amount",
			cache: true,
			error: function(){
				toastr.error('Error');
			},
			success: function(data){
				toastr.info('Amount set successfully');
				$(".span_amount_"+id).html(amount);
			}
		});
	}
}
function get_full_image(url){
	$('#myfullimg').attr('src', url);
}
</script>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Image</h4>
      </div>
      <div class="modal-body">
        <img id="myfullimg" src="https://via.placeholder.com/150" alt="Default Image" width="100%">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>