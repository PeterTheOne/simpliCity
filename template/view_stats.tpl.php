<div id="statistics" class="scrolling">
	<?php
		$citizenGroupJob = db_citizenGroupJobByUser($_SESSION['userid']);
		db_selectUser($_SESSION['userid']);
	?>
	<h3>Punkte: <?php echo $user->points; ?></h3>
	<h3>Highscores</h3>
	<table border=\"0\">
		<tr>
			<td>Name</td>
			<td>Punkte</td>
		</tr>
		<?php
		$scores = db_getHighscores();
		foreach ($scores as $score) {
	?>
		<tr>
			<td><?php echo $score["FirstName"]." ".$score["LastName"]; ?></td>
			<td><?php echo $score["Points"] ?></td>
		</tr>
	<?php
		}
	?>
	</table>
	<p>Du befindest dich auf Platz <?php echo db_getUserScorePosition($user->id); ?> in der Highscoreliste!</p>
	<h3>Bürger</h3>
	<table border=\"0\">
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

	<h3>Städte</h3>
	<p>
		Du hast Bürger in <?php echo db_countPlayerCities($user->id); ?> Städten, 
		in <?php echo db_countMostCitizenCities($user->id); ?> Städten hast du mehr Bürger als alle anderen Spieler.
	</p>
	<p>
		Deine 10 erfolgreichsten Städte sind: 
	</p>
	<table border=\"0\">
		<tr>
			<td>Venue</td>
			<td>Bürger</td>
		</tr>
	<?php
		$citiesByUser = db_citiesByUser($user->id);
		foreach ($citiesByUser as $city) {
	?>
		<tr>
			<td><?php echo fs_getVenue($city['VenueID'])->name; ?></td>
			<td><?php echo $city['countJob'] ?></td>
		</tr>
	<?php
		}
	?>
	</table>
	<p>
		Die Städte in denen du mehr Bürger als alle anderen Spieler hast:
	</p>
	<table border=\"0\">
		<tr>
			<td>Venue</td>
			<td>Bürger</td>
		</tr>
	<?php
		$mostCitizenCities = db_mostCitizenCities($user->id);
		foreach ($mostCitizenCities as $city) {
	?>
		<tr>
			<td><?php echo fs_getVenue($city['VenueID'])->name; ?></td>
			<td><?php echo $city['countJob'] ?></td>
		</tr>
	<?php
		}
	?>
	</table>
	<!--
	<h2>Regionale Aktivität</h2>
	<p>
		Idee: In welcher region am aktivisten vergleich zu foursquare freunden? 
		(wie werden die punkte berechnet?)
	</p>
	-->
	<p/>
</div>
<div id="menu">
	<?php
		require_once("template/element_menu.tpl.php");
	?>
</div>