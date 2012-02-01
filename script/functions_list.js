$(function(){
	if (navigator.geolocation) {
		// GeoLocation verfügbar
		navigator.geolocation.getCurrentPosition(saveCoords, error);
	} else {
		// GeoLocation nicht verfügbar
		$("#venuelist").html("<p class=\"upspace\">Sorry, your browser doesn't support GeoLocation, please check-in manually at Foursquare!</p>");
	}
	
	$(".widebutton").live("click",function(){
		window.location = $(this).children("a").attr("href");
	});
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