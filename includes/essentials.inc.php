<?php

require_once("includes/config.inc.php");
require_once("includes/lib/FoursquareAPI.class.php");

/*
 *
 *	use this to sanitize $_POST and $_GET input
 *
*/
function sanitizeFilter($str) {
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

/*
 *
 *	prints arrays nicely
 *
*/
function printarray($arr) {
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}


function printCityValues($citizencounts, $playercount, $venueID){
	if(!is_array($citizencounts) || $playercount <= 0){
		return false;
	}
	require_once("includes/db_essentials.inc.php");
	$citizens = 0;
	$multiplicator = sqrt($playercount);
	$education = 0;
	$research = 0;
	$wealth = 0;
	$production = 0;
	$religion = 0;
	
	db_connect();
	foreach($citizencounts as $jobID => $count){
		if($result = mysql_query(
			"SELECT Education,Research,Wealth,Production,Religion FROM jobs WHERE ID='$jobID'"
		)){
			while ($line = mysql_fetch_array($result)){
				$education += $count * $line['Education'];
				$research += $count * $line['Research'];
				$wealth += $count * $line['Wealth'];
				$production += $count * $line['Production'];
				$religion += $count * $line['Religion'];
			}
			$citizens += $count;
		}
	}
	db_disconnect();
	
	//=(LOG((($education + $research/($religion*$multiplicator) + $research/($religion*$multiplicator) + $wealth + $production)*$citizens)/10)*$multiplicator)
	$temp = ($religion*$multiplicator == 0)? 1 : ($religion*$multiplicator);
	$innerCity = (($education + $research/$temp + $research/$temp + $wealth + $production)*$citizens)/10;
	$innerCity = ($innerCity <= 0)? 0 : log10($innerCity)*$multiplicator;
	//= (LOG((($research^3) - $religion*$multiplicator + $production)*$citizens/10)/($multiplicator/1,3))
	$industry = (pow($research,3) - $religion*$multiplicator + $production)*$citizens/10;
	$industry = ($industry <= 0)? 0 : log10($industry)/($multiplicator/1.3);
	//=(LOG(($education + $research + $wealth + $religion*$multiplicator)*$citizens)*$multiplicator)
	$urban = ($education + $research + $wealth + $religion*$multiplicator)*$citizens;
	$urban = ($urban <= 0)? 0 : log10($urban)*$multiplicator;
	//= (LOG(($production + $religion*5*$multiplicator) )* $multiplicator)
	$rural = $production + $religion*5*$multiplicator;
	$rural = ($rural <= 0)? 0 : log10($rural)*$multiplicator;
	
	?>
	
	<div id="cityValues" style="display: none;">
		<p id="venueID"><?php echo $venueID; ?></p>
		<p id="innerCity"><?php echo round($innerCity); ?></p>
		<p id="industry"><?php echo round($industry); ?></p>
		<p id="urban"><?php echo round($urban); ?></p>
		<p id="rural"><?php echo round($rural); ?></p>
	</div>
	
	<?php
	
}
?>
