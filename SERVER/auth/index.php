<?php

//https://github.com/stephenyoung/php-foursquare
require_once("FoursquareAPI.class.php");

// docu: https://developer.foursquare.com/docs/oauth.html
// für jede neue uri muss eine neue Client-ID angelegt werden.

define("CLIENT_ID",     "GPP0FG0DZI3ES5JKLNBJWZDMEITYI2XECBVTSW2GL3ZJCHGT");
define("CLIENT_SECRET",     "5P3ABYS1CSVHXVEEAHWZP1A42DWZ4DTXROA5SAFCTXZCJNMX");
define("REDIRECT_URI",     "http://petergrassberger.at/pro5/prototypes/foursquare-api_php-foursquare/");

function sanitizeFilter($str) {
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

$foursquare = new FoursquareAPI(CLIENT_ID, CLIENT_SECRET);

if(array_key_exists("code", $_GET)){
	$token = $foursquare->GetToken(sanitizeFilter($_GET['code']), REDIRECT_URI);
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>prototype: foursquare-api_php-foursquare</title>
		
		<meta charset="utf-8">
	</head>
	<body>
<?php 

	if (!isset($token)) { 
		echo "<p id=\"authlink\"><a href='" . $foursquare->AuthenticationLink(REDIRECT_URI) . "'>Connect to this app via Foursquare</a></p>";
	} else {
		echo "<p id=\"token\">$token</p>";
	}

?>
	</body>
</html>