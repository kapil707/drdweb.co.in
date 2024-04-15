<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<!-- <a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a> -->
   	</div>
	<?php 
	$parmiter = '';
	$curl = curl_init();
		
		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL =>"http://97.74.82.55:5000/messages?from=07/04/2024&to=07/04/2024",
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
								<td><?php echo $screenshot_image; ?></td>
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