<?php
	session_start();
	
	require_once("includes/config.inc.php");
	
	require_once("includes/lib/FoursquareAPI.class.php");
	require_once("includes/essentials.inc.php");
	require_once("includes/essentials_fs.inc.php");
	require_once("includes/essentials_db.inc.php");
	require_once("includes/User.class.inc.php");
	
	require_once("includes/functions_index.inc.php");
	
	fs_setup($_SESSION["authtoken"]);
	if(!isset($_SESSION['userid'])) {
		$_SESSION['userid'] = fs_getUserID();	
	}
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
		<div id="map" style="width:800px; height:600px;"></div>
		<div id="data" style="/*display:none;*/">
			<ul>
<?php
	$venueList = db_citizenCountPerVenue();
	foreach ($venueList as $db_venue) {
		$fs_venue = fs_getVenue($db_venue['VenueID']);
?>
				<li>
					<span class="name"><?php echo $fs_venue->name ?></span>
					<span class="id"><?php echo $db_venue['VenueID'] ?></span>
					<span class="cit"><?php echo $db_venue['countJob'] ?></span>
					<span class="lat"><?php echo $fs_venue->location->lat ?></span>
					<span class="lng"><?php echo $fs_venue->location->lng ?></span>
				</li>
<?php
	}
?>

			</ul>
		</div>
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
				
				// add markers layer
				var markers = new OpenLayers.Layer.Markers( "Markers" );
				map.addLayer(markers);
				
				// setup marker icons
				var size = new OpenLayers.Size(21,25);
				var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
				var icon = new OpenLayers.Icon('http://www.openlayers.org/dev/img/marker.png', size, offset);
				
				// parse list of venues
				$("#data ul li").each(function(){
					var lat = $(this).children(".lat").html();
					var lng = $(this).children(".lng").html();
					var lonLat = new OpenLayers.LonLat(lng, lat)
					.transform(
						new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
						map.getProjectionObject() // to Spherical Mercator Projection
					);
					markers.addMarker(new OpenLayers.Marker(lonLat, icon.clone()));
				});
				
			});
		</script>
	</body>
</html>
