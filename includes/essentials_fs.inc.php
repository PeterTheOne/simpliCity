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
	$request = $foursquare->GetPrivate(
					"users/self", 
					array(
						'v' => FOURSQUARE_API_VERSION
					)
	);
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
					array(
						'limit' => $limit, 
						'v' => FOURSQUARE_API_VERSION
					)
	);
	$details = json_decode($request, false);
	if (fs_hasErrors($details->meta)) return false;
	return $details->response->checkins->items;
}

/*
 *
 *	returns venues list by location
 *
*/
function fs_getVenuesExplore($ll, $limit = 5) {
	global $foursquare;
	$request = $foursquare->GetPrivate(
					"venues/explore", 			//besser mit /search ?
					array(
						'limit' => $limit, 
						'll' => $ll, 
						'v' => FOURSQUARE_API_VERSION
					)
	);
	$details = json_decode($request, false);
	if (fs_hasErrors($details->meta)) return false;
	foreach ($details->response->groups as $group) {
		if ($group->type == "recommended") {
			return $group->items;
		}
	}
	return false;
}

/*
 *
 *	checksin into venue
 *
*/
function fs_checkin($venueid) {
	global $foursquare;
	$request = $foursquare->GetPrivate(
					"checkins/add", 
					array(
						'venueId' => $venueid, 
						'shout' => 'checked in with simpliCity', 
						'v' => FOURSQUARE_API_VERSION
					),
					true
	);
	$details = json_decode($request, false);
	if (fs_hasErrors($details->meta)) return false;
	return true;
}

function fs_getVenue($venueid) {
	global $foursquare;
	$request = $foursquare->GetPrivate(
					"venues/$venueid",
					array(
						'v' => FOURSQUARE_API_VERSION
					)
	);
	$details = json_decode($request, false);
	if (fs_hasErrors($details->meta)) return false;
	return $details->response->venue;	
}

function fs_getVenueLocation($venueid) {
	global $foursquare;
	$request = $foursquare->GetPrivate(
					"venues/$venueid",
					array(
						'v' => FOURSQUARE_API_VERSION
					)
	);
	$details = json_decode($request, false);
	if (fs_hasErrors($details->meta)) return false;
	return $details->response->venue->location;	
}


?>
