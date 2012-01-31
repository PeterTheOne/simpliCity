<?php
session_start();

if (isset($_GET['host'])) {
	$_SESSION['host'] = sanitize($_GET['host']);
}

//https://github.com/stephenyoung/php-foursquare
require_once("FoursquareAPI.class.php");

// docu: https://developer.foursquare.com/docs/oauth.html
// für jede neue uri muss eine neue Client-ID angelegt werden.

define("CLIENT_ID",     "GPP0FG0DZI3ES5JKLNBJWZDMEITYI2XECBVTSW2GL3ZJCHGT");
define("CLIENT_SECRET",     "5P3ABYS1CSVHXVEEAHWZP1A42DWZ4DTXROA5SAFCTXZCJNMX");
define("REDIRECT_URI",     "http://petergrassberger.at/pro5/prototypes/foursquare-api_php-foursquare/");

function sanitize($str) {
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

$foursquare = new FoursquareAPI(CLIENT_ID, CLIENT_SECRET);

if(array_key_exists("code", $_GET)){
	$token = $foursquare->GetToken(sanitize($_GET['code']), REDIRECT_URI);
}

	if (!isset($token)) { 
		?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>SimpliCity</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="Author" content="Andreas Kasch, Peter Grassberger"/>
		<meta name="Description" content="SimpliCity"/>
		<link rel="shortcut icon" type="image/x-icon" href="images/system/logo.ico" />
		<meta name="viewport" content="width=device-width, initial-scale = 1.0, user-scalable = no" />
		<script type="text/javascript" src="jquery.js"></script>
		<style type="text/css">
			body{
				background-color: #03638c;
				font-family: Nyala,Verdana,Arial,sans-serif;
				font-size: 12pt;
				width: 100%;
				height: 100%;
			}
			
			img, #authlink{
				margin: 0 auto;
			}
		</style>
	</head>
	<body>
		<div id="container">
		<img src="simplicity.png" alt="SimpliCity"/>
		<script type="text/javascript">
			$(function(){
				$("#jsactivate").hide();
				$("#authlink").show();
			});
		</script>
		<p id="jsactivate">
			Sie m&uuml;ssen Javascript aktivieren um das Spiel zu spielen!
		</p>
		<?php
		echo "<p style=\"display:none\" id=\"authlink\"><a href='" . 
			$foursquare->AuthenticationLink(REDIRECT_URI) . 
			"'><img src=\"foursquare.png\" alt=\"Connect to this app via Foursquare\" /></a></p>";
			
		?>
		</div>
	</body>
</html>
		<?php
	} else {
		//echo "<p id=\"token\">$token</p>";
		header('Location: http://' . $_SESSION['host'] . "?token=$token");
	}

?>