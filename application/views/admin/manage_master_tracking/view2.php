<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<title>Transit layer</title>
<style>
html,body,#map-canvas {
    height: 100%;
    margin: 0px;
    padding: 0px
}
</style>
<link href="/maps/documentation/javascript/examples/default.css" rel="stylesheet"      type="text/css" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript"   src="http://geoxml3.googlecode.com/svn/branches/polys/geoxml3.js"></script>
<script> function initialize() 
{   
    var myLatlng = new google.maps.LatLng(0, -180);   
    var mapOptions = 
        {     
            zoom: 13,     
            center: myLatlng,     
            mapTypeId: google.maps.MapTypeId.ROADMAP  
        }    

     var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);    
     var transitLayer = new google.maps.TransitLayer();   
     transitLayer.setMap(map); 


    var geoXml = new geoXML3.parser({map: map, singleInfoWindow: true});
     geoXml.parse('kmload.kml'); 
     var geoXml1 = new geoXML3.parser({map: map, singleInfoWindow: true});
     geoXml1.parse('lines.kml'); 


     var coordinates = [     
                           new google.maps.LatLng(18.9800, 73.1000),     
                           new google.maps.LatLng(19.0361, 73.0617)];  

     google.maps.event.addListener(map, "click", function (e) 
      {  
                 var trainpath = new google.maps.Polyline({     
                 path: coordinates,    
                 geodesic: true,     
                 strokeColor: '#FF0000',     
                 strokeOpacity: 1.0,     
                 strokeWeight: 2   
                 });    
                trainpath.setMap(map);
      });



     }  
google.maps.event.addDomListener(window, 'load', initialize); 
    </script>
</head>
<body>
<div id="map-canvas"></div>
</body>
</html>