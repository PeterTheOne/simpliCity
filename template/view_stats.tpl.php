<h2>Aufschlüsselung der verteilten Bürger</h1>
<p>
	Bürger
</p>
<?php
	$citizenGroupJob = db_citizenGroupJobByUser($_SESSION['userid']);
	db_selectUser($_SESSION['userid']);
?>
<table border=\"1\">
	<tr>
		<td>Beruf</td>
		<td>Anzahl</td>
	</tr>
<?php
	$totalCount = 0;
	foreach ($citizenGroupJob as $job) {
	$totalCount += $job['jobCount'];
?>
	<tr>
		<td><?php echo $job['Name'] ?></td>
		<td><?php echo $job['jobCount'] ?></td>
	</tr>
<?php
	}
?>
<tr>
	<td>Verteilt</td>
	<td><?php echo $totalCount ?></td>
</tr>
<tr>
	<td>Übrig</td>
	<td><?php echo $user->unusedCitizen; ?></td>
</tr>
</table>

<h2>Städte</h2>
<p>
	Du hast Bürger in <?php echo db_countPlayerCities($user->id); ?> Städten, 
	in <?php echo db_countMostCitizenCities($user->id); ?> Städten hast du mehr Bürger als alle anderen Spieler.
</p>
<p>
	Deine 10 erfolgreichsten Städte sind: 
</p>
<ul>
<?php
	$citiesByUser = db_citiesByUser($user->id);
	foreach ($citiesByUser as $city) {
?>
	<li><?php echo fs_getVenue($city['VenueID'])->name . " - Bürger: " . $city['countJob']  ?></li>
<?php
	}
?>
</ul>
<p>
	Die Städte in denen du mehr Bürger als alle anderen Spieler hast: 
</p>
<ul>
<?php
	$mostCitizenCities = db_mostCitizenCities($user->id);
	foreach ($mostCitizenCities as $city) {
?>
	<li><?php echo fs_getVenue($city['VenueID'])->name . " - Bürger: " . $city['countJob'] ?></li>
<?php
	}
?>
</ul>

<!--
<h2>Regionale Aktivität</h2>
<p>
	Idee: In welcher region am aktivisten vergleich zu foursquare freunden? 
	(wie werden die punkte berechnet?)
</p>
-->
<div id="menu">
	<?php
		require_once("template/element_menu.tpl.php");
	?>
</div>