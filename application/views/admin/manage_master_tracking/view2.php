<?php define("API_KEY", "AIzaSyBHDSrVBSnNmTVBCYSPYqdz8qSKmG47Yxk"); ?>
<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<?php /*<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a> */ ?>
   	</div>
	<div class="col-xs-12">
  <style>
body {
	font-family: Arial;
}

#map-layer {
	margin: 20px 0px;
	max-width: 600px;
	min-height: 400;
}
.lbl-way-points {
    font-weight: bold;
    margin-bottom: 15px;
}
.way-points-option {
    display:inline-block;
    margin-right: 15px;
}
.btn-submit {
    background: #a2ccff;
    border: #96bdec 1px solid;
    padding: 5px 20;
    cursor: pointer;
}
</style>
</head>
<body>
    <h1>How to draw Path on Map using Google Maps Direction API</h1>
    <div class="lbl-way-points">Way Points</div>
    <div>
        
    <?php
    $countryResult = $this->db->query("select * from tbl_country")->result();
    foreach ($countryResult as $row) {
      $resultset[] = $row;
    }
    print_r($resultset);
    foreach ($resultset as $k => $v) {
    ?>
      <div class="way-points-option"><input type="checkbox" name="way_points[]" class="way_points" value="<?php echo $countryResult[$k]["country"]; ?>"> <?php echo $countryResult[$k]["country"]; ?></div>
    <?php
    }
    ?>
    <input type="button" id="go" value="Draw Path" class="btn-submit" />
    </div>
    
    <div id="map-layer"></div>
    <script>
      	var map;
		var waypoints
      	function initMap() {
        	  	var mapLayer = document.getElementById("map-layer"); 
            	var centerCoordinates = new google.maps.LatLng(37.6, -95.665);
        		var defaultOptions = { center: centerCoordinates, zoom: 4 }
        		map = new google.maps.Map(mapLayer, defaultOptions);
	
            var directionsService = new google.maps.DirectionsService;
            var directionsDisplay = new google.maps.DirectionsRenderer;
            directionsDisplay.setMap(map);

            $("#go").on("click",function() {
            	    waypoints = Array();
                	$('.way_points:checked').each(function() {
                    waypoints.push({
                        location: $(this).val(),
                        stopover: true
                    });
                	});
                var locationCount = waypoints.length;
                if(locationCount > 0) {
                    var start = waypoints[0].location;
                    var end = waypoints[locationCount-1].location;
                    drawPath(directionsService, directionsDisplay,start,end);
                }
            });
            
      	}
        	function drawPath(directionsService, directionsDisplay,start,end) {
            directionsService.route({
              origin: start,
              destination: end,
              waypoints: waypoints,
              optimizeWaypoints: true,
              travelMode: 'DRIVING'
            }, function(response, status) {
                if (status === 'OK') {
                directionsDisplay.setDirections(response);
                } else {
                window.alert('Problem in showing direction due to ' + status);
                }
            });
      }
	</script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo API_KEY; ?>&callback=initMap">
    </script>
    </div>
</div>