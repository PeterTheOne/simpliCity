<?php

require_once("includes/config.inc.php");

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
 *	checks the errorCode and optionally printsErrors
 *
*/
function fs_hasErrors($meta) {
	if(isset($meta->code) && $meta->code >= 400 && $meta->code <= 500){
		if (PRINT_FS_ERRORS) {
			echo "<p>error-code: $meta->code</p>";
			echo "<p>$meta->errorType: $meta->errorDetail</p>";
		}
		return true;
	}
	return false;
}

/*
 *
 *	returns userID
 *
*/
function fs_getUserID() {
	global $foursquare;
	$request = $foursquare->GetPrivate("users/self");
	$details = json_decode($request, false);
	if (fs_hasErrors($details->meta)) return false;
	return $details->response->user->id;
}

/*
 *
 *	checks the errorCode and optionally printsErrors
 *
*/
function fs_isCheckedIn($checkinTime) {
	return $checkinTime > time() - CHECKIN_TIME;
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
function fs_getSelfCheckins($limit = 1) {
	global $foursquare;
	$request = $foursquare->GetPrivate(
					"users/self/checkins", 
					array('limit' => $limit)
	);
	$details = json_decode($request, false);
	if (fs_hasErrors($details->meta)) return false;
	return $details->response->checkins->items;
}

/*
 *
 *	returns latest checkins
 *
*/
function fs_getVenuesExplore($ll, $limit = 5) {
	global $foursquare;
	$request = $foursquare->GetPrivate(
					"venues/explore", 
					array(
						'limit' => $limit, 
						'll' => $ll
					)
	);
	$details = json_decode($request, false);
	if (fs_hasErrors($details->meta)) return false;
	foreach ($details->response->groups as $group) {
		if ($group->type == "recommended") {
			return $group->items;
		}
	}
	//return $details->response->groups[0]->items;
	return false;
}

?>
