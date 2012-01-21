<!DOCTYPE html>
<html>
	<head>
		<title>SimpliCity - map test</title>
		
		<meta charset="utf-8" />
		<!--<link rel="stylesheet" type="text/css" href="" />-->
		<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
	</head>
	<body>
		<h1>SimpliCity - map test</h1>
		<div id="map" style="width:500px; height:500px;">test</div>
		<script defer="defer" type="text/javascript">
			var map = new OpenLayers.Map('map');
			var wms = new OpenLayers.Layer.WMS(
					"OpenLayers WMS", 
					"http://vmap0.tiles.osgeo.org/wms/vmap0", 
					{layers: 'basic'});
			map.addLayer(wms);
			map.zoomToMaxExtent();
		</script>
	</body>
</html>
