<?php
	session_start();
	
	require_once("includes/config.inc.php");
	
	require_once("includes/lib/FoursquareAPI.class.php");
	require_once("includes/essentials.inc.php");
	require_once("includes/essentials_fs.inc.php");
	require_once("includes/essentials_db.inc.php");
	require_once("includes/User.class.inc.php");
	
	require_once("includes/functions_index.inc.php");
	
	checkAuthentication();
	
	if (!isset($_GET['pwd']) || sanitize($_GET['pwd']) !== ADMIN_PWD) {
		echo "<p>sry, no permission.</p>";
		exit();
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>SimpliCity - map test</title>
		
		<meta charset="utf-8" />
		<!--<link rel="stylesheet" type="text/css" href="" />-->
		<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
		<script type="text/javascript" src="script/jquery.js"></script>
	</head>
	<body>
		<h1>SimpliCity - map test</h1>
		<div id="map" style="width:100%; height:600px;"></div>
		<p>
			<a href="admin_map_venuelist.php">see list</a>
		</p>
		<script defer="defer" type="text/javascript">
			$(function(){
				// create map
				map = new OpenLayers.Map("map");
				map.addLayer(new OpenLayers.Layer.OSM());
				
				// set startPosition
				//TODO: get my position as start
				var startPosition = new OpenLayers.LonLat(0, 0)
					.transform(
						new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
						map.getProjectionObject() // to Spherical Mercator Projection
					);
				
				// set map options
				var zoom = 2;
				map.setCenter(startPosition, zoom);
				
				// add points of interest layer from file
				var pois = new OpenLayers.Layer.Text( "My Points",
                    { location:"./admin_map_venuelist.php",
                      projection: map.displayProjection
                    });
				map.addLayer(pois);
				
			});
		</script>
	</body>
</html>
