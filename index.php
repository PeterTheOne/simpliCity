<?php
	session_start();
	
	require_once("includes/config.inc.php");
	
	require_once("includes/lib/FoursquareAPI.class.php");
	require_once("includes/essentials.inc.php");
	require_once("includes/essentials_fs.inc.php");
	require_once("includes/essentials_db.inc.php");
	require_once("includes/User.class.inc.php");
	
	require_once("includes/functions_index.inc.php");
	
	checkAuthentication();
	
	/*
	 *
	 *	draw the page
	 *
	*/
	require_once("template/element_header.tpl.php");
	
	if(!isset($_GET["view"]) || $_GET["view"] == "city"){			//nicht definiert (neu eingeloggt) oder city-view gew�hlt
		if (fs_isCheckedIn(fs_getSelfCheckinOne()->createdAt)) {	//letzter checkin vor kurzem -> city-view
			require_once("template/view_city.tpl.php");
		} else {													//letzter checkin zu lange vergangen -> list-view
			//TODO: Notiz dass eingecheckt werden muss
			require_once("template/view_list.tpl.php");
		}
	} else if($_GET["view"] == "list"){								//nutzer w�hlt list-view
		require_once("template/view_list.tpl.php");
	} else if($_GET["view"] == "stats"){							//nutzer w�hlt statistiken
		require_once("template/view_stats.tpl.php");			//TODO: Stats Seite
	} else if($_GET["view"] == "setup"){							//nutzer w�hlt einstellungen
		require_once("template/view_setup.tpl.php");			//TODO: Setup Seite oder entfernen
	} else if($_GET["view"] == "checkin"){							//neuer checkin
		require_once("template/view_checkin.tpl.php");
	} else {														//ung�ltige eingabe -> liste darstellen
		require_once("template/view_list.tpl.php");
	}
	
	require_once("template/element_footer.tpl.php");
?>
