<h1><?php echo $this->getCurMenu()->getName();?></h1>
<?php echo $this->getCurMenu()->getPageBody();?>

<script type="text/javascript" src="http://www.google.com/jsapi?key=<?php echo Config::findByCode('google_map_api')->getValue();?>" type="text/javascript"></script> 

    
    <div id="map_canvas" style="width: 335px; height: 335px"></div> 
    
     <script type="text/javascript"> 
		google.load("maps", "2.x");
		var map;
		var geocoder;
		
		function initialize() {
			if (GBrowserIsCompatible()) {
				map = new GMap2(document.getElementById("map_canvas"));
				map.setCenter(new GLatLng(52.052959, 4.271557), 15);
				geocoder = new GClientGeocoder();
				geocoder.getLocations("Deltaplein 6132554 L' Aia, The Netherlands", addAddressToMap);           
			}
		}
		
		function addAddressToMap(response) {
			place = response.Placemark[0];
			point = new GLatLng(place.Point.coordinates[1],
			place.Point.coordinates[0]);
			
			var blueIcon = new GIcon(G_DEFAULT_ICON);
			blueIcon.image = "http://www.ketjesmix.com/img/main/google-marker.gif";     
			blueIcon.iconSize = new GSize(50, 50);
			// Set up our GMarkerOptions object
			markerOptions = { icon:blueIcon };  
			marker = new GMarker(point,markerOptions);
			
			map.addOverlay(marker);
			
			marker.openInfoWindowHtml("<b>Ketje's Mix</b><br/>Deltaplein 613<br/>2554 GJ Den Haag");
			return;   
		}
		
		google.setOnLoadCallback(initialize)
    </script> 