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
					CURLOPT_URL =>"http://172.105.50.148:5000/groups",
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
	<?php 

	$start_date = $end_date = date('d-m-Y');
	if(isset($_GET["date-range"])){
		$date_range = $_GET["date-range"];

		// `to` ke aas paas se string ko tukdon mein vibhajit karen
		$date_parts = explode(" to ", $date_range);

		// Start date aur end date ko extract karen
		$start_date = $date_parts[0];
		$end_date 	= $date_parts[1];
	}

	$start_date = DateTime::createFromFormat('d-m-Y', $start_date);
	$end_date 	= DateTime::createFromFormat('d-m-Y', $end_date);

	$start_date = $start_date->format('d/m/Y');
	$end_date 	= $end_date->format('d/m/Y');

	$sender_name_place = "Online%20Details";

	//Created a GET API
	//http://97.74.82.55:5000/messages?from=07/04/2024&to=07/04/2024
	//http://172.105.50.148:5000/messages?from=07/04/2024&to=07/04/2024
	$parmiter = '';
	$curl = curl_init();
		
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL =>"http://172.105.50.148:5000/messages?from=$start_date&to=$end_date&sender_name_place=$sender_name_place",
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
				),
			)
		);

		$response = curl_exec($curl);
		//print_r($response);
		curl_close($curl);

		$data1 = json_decode($response, true); // Convert JSON string to associative array
		?>
	<div class="col-xs-12">
        <div class="table-responsive">
			<table class="table table-striped table-bordered table-hover" id="example-table">
                <thead>
                    <tr>
						<th>
							body
						</th>
						<th>
							date
						</th>
						<th>
							extracted_text
                        </th>
						<th>
							from_number 
						</th>
						<th> 
							message_id
                        </th>
						<th>
							screenshot_image
                        </th>
						<th>
							Price
                        </th>
						<th>
							timestamp
                        </th>
						<th>
                        	Edit
                        </th>
                    </tr>
                </thead>
				<tbody>
					<?php /*
					foreach($result as $row){
						?>
						<tr><?php $body = utf8_decode($row->body); ?>
							<td><?php echo $body; ?></td>
							<td><?php echo $row->date; ?></td>
							<td><?php echo $row->extracted_text; ?></td>
							<td><?php echo $row->from_number; ?></td>
							<td><?php echo $row->message_id; ?></td>
							<td><?php echo $row->screenshot_image; ?></td>
							<td><?php echo $row->timestamp; ?></td>
							<td><?php echo $row->timestamp; ?></td>
						</tr>
						<?php
					}
					 */?>
					 <?php
					if (isset($data1['messages'])) {
						foreach ($data1['messages'] as $message) {
							$body = isset($message['body']) ? $message['body'] : "Body not found";

							$date = isset($message['date']) ? $message['date'] : "Date not found";

							$extracted_text = isset($message['extracted_text']) ? $message['extracted_text'] : "extracted_text not found";

							$from_number = isset($message['from_number']) ? $message['from_number'] : "Date not found";
							
							$id = isset($message['id']) ? $message['from_number'] : "id not found";

							$screenshot_image = isset($message['screenshot_image']) ? $message['screenshot_image'] : "screenshot_image not found";

							$timestamp = isset($message['timestamp']) ? $message['timestamp'] : "timestamp not found";
							?>
							<tr>
								<td><?php echo $body; ?></td>
								<td><?php echo $date; ?></td>
								<td><?php echo $extracted_text; ?></td>
								<td><?php echo $from_number; ?></td>
								<td><?php echo $id; ?></td>
								<td>
									<a href="https://api.wassi.chat<?php echo $screenshot_image; ?>?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e" target="_blank">	
										<img src="https://api.wassi.chat<?php echo $screenshot_image; ?>?token=531fe5caf0e132bdb6000bf01ed66d8cfb75b53606cc8f6eed32509d99d74752f47f288db155557e" width="100px">
									</a>
								</td>
								<td>
									<?php
									$pattern = '/\d{1,3}(?:,\d{3})*(?:\.\d+)?/'; // Regex pattern to match the price format

									preg_match_all($pattern, $extracted_text, $matches1);

									$price1 = $matches1[0][0] ?? '';

									echo "Price 1: $price1";
									?>
								</td>
								<td><?php echo $timestamp; ?></td>
								<td><?php echo $timestamp; ?></td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
		</div>
    </div>
</div>
