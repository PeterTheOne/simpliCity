<?php

require_once("includes/db_essentials.inc.php");
require_once("includes/essentials.inc.php");

$jobs = array();
$jobs[] = array(
	'name' => 'Wissenschaftler', 
	'description' => 'Der Wissenschaftler treibt die Forschung an, verringert aber den G&ouml;tterglauben der Menschheit', 
	'education' => 2, 'research' => 5, 'wealth' => 0, 'production' => 1, 'religion' => -3
);
$jobs[] = array(
	'name' => 'Lehrer', 
	'description' => 'Der Lehrer sorgt f&uuml;r eine bessere Bildung was auch den Wohlstand erh&ouml;ht', 
	'education' => 5, 'research' => 2, 'wealth' => 2, 'production' => 0, 'religion' => 1
);
$jobs[] = array(
	'name' => 'Arbeiter', 
	'description' => 'Der Arbeiter kurbelt die Produktion an, der Wohlstand der Stadt steigt', 
	'education' => 0, 'research' => 1, 'wealth' => 3, 'production' => 5, 'religion' => 0
);
$jobs[] = array(
	'name' => 'Geistlicher', 
	'description' => 'Der Geistliche bringt den Glauben zur Menschheit. Vor allem f&uuml;r das Kleinb&uuml;rgertum ist die Religion sehr wichtig', 
	'education' => 2, 'research' => -1, 'wealth' => 2, 'production' => 0, 'religion' => 5
);

if(isset($_GET["insert"]) && sanitizeFilter($_GET["insert"]) == "true") {
	db_connect();
	foreach ($jobs as $job) {
		$r = mysql_query(
			"INSERT INTO jobs (
				Name,Description,Education,Research,Wealth,Production,Religion
			) VALUES (
				'" . $job['name'] . 
				"','" . $job['description'] . 
				"','" . $job['education'] . 
				"','" . $job['research'] . 
				"','" . $job['wealth'] . 
				"','" . $job['production'] . 
				"','" . $job['religion'] . 
				"'
			)"
		);
		db_hasErrors($r);
	}
	db_disconnect();
} else if (isset($_GET["update"]) && sanitizeFilter($_GET["update"]) == "true") {
	db_connect();
	foreach ($jobs as $job) {
		$r = mysql_query(
			"UPDATE jobs SET 
			Description='" . $job['description'] . "', 
			Education='" . $job['education'] . "', 
			Research='" . $job['research'] . "', 
			Wealth='" . $job['wealth'] . "', 
			Production='" . $job['production'] . "', 
			Religion='" . $job['religion'] . "' 
			WHERE Name='" . $job['name'] . "'"
		);
		db_hasErrors($r);
	}
	db_disconnect();
} else {

	require_once("template/header.tpl.php");
	echo "<p><a href=\"?insert=true\">Insert job Entries!</a></p>";
	echo "<p><a href=\"?update=true\">Update job Entries!</a></p>";
	require_once("template/footer.tpl.php");
	
}
?>