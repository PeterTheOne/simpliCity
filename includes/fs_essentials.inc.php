<?php

/*
 *
 *	setup the foursquare connection
 *	you may still need to SetAccessToken!!!
 *
*/
function fs_setup($authtoken = null){
	global $foursquare;
	$foursquare = new FoursquareAPI(CLIENT_ID, CLIENT_SECRET);
	if ($authtoken != null) {
		$foursquare->SetAccessToken($authtoken);
	}
}

/*
 *
 *	returns the latest checkin (only one)
 *
*/
function fs_getSelfCheckinOne() {
	$items = fs_getSelfCheckins();
	return $items[0];
}

/*
 *
 *	returns latest checkins
 *
*/
function fs_getSelfCheckins($num = 1) {
	global $foursquare;
	$request = $foursquare->GetPrivate(
					"users/self/checkins", 
					array('limit' => $num)
	);
	$details = json_decode($request, false);
	//printarray($details);
	if (fs_hasErrors($details->meta, true)) return false;
	return $details->response->checkins->items;
}

/*
 *
 *	checks the errorCode and optionally printsErrors
 *
*/
function fs_hasErrors($meta, $printErrors = false) {
	if(isset($meta->code) && $err >= 400 && $err <= 500){
		if ($printErrors) {
			echo $meta->code;
			echo "<p>" . $meta->errorType . ": " . $meta->errorDetail . "</p>";
		}
		return true;
	}
	return false;
}

?>
