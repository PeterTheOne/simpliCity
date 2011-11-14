<?php

function sanitizeFilter($str) {
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

function printarray($arr) {
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}

?>