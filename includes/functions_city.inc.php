<?php

require_once("includes/config.inc.php");
require_once("includes/essentials.inc.php");
require_once("includes/essentials_db.inc.php");
require_once("includes/essentials_fs.inc.php");

function getCityValues(){
	if(!isset($_SESSION)){
		session_start();
		fs_setup($_SESSION["authtoken"]);
	}
	$self = fs_getSelfCheckinOne();
	$venueID = $self->venue->id;
	$venueName = $self->venue->name;
	global $user;
	db_selectUser($_SESSION['userid']);
	printCityValues(db_citizenInVenue($venueID),db_playersInVenue($venueID),$venueID, $venueName,db_remainingCitizen($_SESSION['userid']),$user->points);
}

function printCityValues($citizencounts, $playercount, $venueID, $venueName, $remaining, $points){
	
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
	$innerCity = ($innerCity <= 0)? 0 : log($innerCity,1.5)*$multiplicator;
	//= (LOG((($research^3) - $religion*$multiplicator + $production)*$citizens/10)/($multiplicator/1,3))
	$industry = (pow($research,3) - $religion*$multiplicator + $production)*$citizens/10;
	$industry = ($industry <= 0)? 0 : log($industry,1.5)/($multiplicator/1.3);
	//=(LOG(($education + $research + $wealth + $religion*$multiplicator)*$citizens)*$multiplicator)
	$urban = ($education + $research + $wealth + $religion*$multiplicator)*$citizens;
	$urban = ($urban <= 0)? 0 : log($urban,1.5)*$multiplicator;
	//= (LOG(($production + $religion*5*$multiplicator) )* $multiplicator)
	$rural = $production + $religion*5*$multiplicator;
	$rural = ($rural <= 0)? 0 : log($rural,1.5)*$multiplicator;
	
	?>
	
	<div id="cityValues" style="display: none;">
		<p id="venueID"><?php echo $venueID; ?></p>
		<p id="venueName"><?php echo $venueName; ?></p>
		<p id="innerCity"><?php echo round($innerCity); ?></p>
		<p id="industry"><?php echo round($industry); ?></p>
		<p id="urban"><?php echo round($urban); ?></p>
		<p id="rural"><?php echo round($rural); ?></p>
		<p id="citizens"><?php echo round($citizens); ?></p>
		<p id="remaining"><?php echo round($remaining); ?></p>
		<p id="userpoints"><?php echo round($points); ?></p>
	</div>
	
	<?php
	
}

?>