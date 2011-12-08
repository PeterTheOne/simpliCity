<?php

require_once("includes/essentials.inc.php");
require_once("includes/fs_essentials.inc.php");

$result = fs_checkin(sanitizeFilter($_GET['checkinid']));
?>

<div>
	<p>result:</p>
<?php if ($result) { ?>
	<p>checked in</p>
<?php } else { ?>
	<p>failed</p>
<?php } ?>
</div>
