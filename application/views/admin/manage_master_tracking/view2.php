<?php $mapapikey = $this->Scheme_Model->get_website_data("mapapikey") ;?>
<div class="row">
    <div class="col-xs-12" style="margin-bottom:5px;">
    	<?php /*<a href="add">
            <button type="submit" class="btn btn-info">
                Add
            </button>
        </a> */ ?>
   	</div>
	<div class="col-xs-12">
    
  <script async
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $mapapikey ?>&loading=async&callback=initMap">
</script>

  <div id="map" style="height: 400px;"></div>afdasfd
  <script>
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 7,
            center: { lat: 29.625996, lng: 74.287491 }, // San Francisco
        });

        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({ map: map });

        const request = {
            origin: "hanumangarh",
            destination: "jaipur",
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