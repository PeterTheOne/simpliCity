$(function(){
	if (navigator.geolocation) {
		// GeoLocation verf�gbar
		navigator.geolocation.getCurrentPosition(saveCoords, error);
	} else {
		// GeoLocation nicht verf�gbar
		$("#venuelist").html("<p>Sorry, your browser doesn't support GeoLocation, please check-in manually at Foursquare!</p>");
	}
});

function saveCoords(position)
{
	$.post("aj_list_getlist.aj.php", {lat: position.coords.latitude, lon: position.coords.longitude}, function(data){
		$("#venuelist").html(data);
		//$("#venuelist").hide();
	});
}

function error()
{
	$("#venuelist").html("<p>Sorry, locating your device failed, please check-in manually at Foursquare!</p>");
}