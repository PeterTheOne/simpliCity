<?php

function sanitizeFilter($str) {
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

?>