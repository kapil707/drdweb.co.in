<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<?php /*<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a> */ ?>
   	</div>
	<div class="col-xs-12">
    
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>

  <div id="map" style="height: 400px;"></div>
  <script>
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 7,
            center: { lat: 37.7749, lng: -122.4194 }, // San Francisco
        });

        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({ map: map });

        const request = {
            origin: "San Francisco, CA",
            destination: "Los Angeles, CA",
            travelMode: google.maps.TravelMode.DRIVING,
        };

        directionsService.route(request, function (result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(result);
            } else {
                window.alert("Directions request failed due to " + status);
            }
        });
    }
</script>
   
<script>
    window.onload = function () {
        initMap();
    };
</script>

</div>
</div>