<?php

require_once("includes/essentials.inc.php");
require_once("includes/essentials_fs.inc.php");

$result = fs_checkin(sanitize($_GET['checkinid']));

if($result){
	header("Location: ?view=city");
}

?>

<div>
	<p>result:</p>
<?php if ($result) { ?>
	<p>checked in</p>
<?php } else { ?>
	<p>failed</p>
<?php } ?>
</div>
<div id="menu">
	<?php
		require_once("template/element_menu.tpl.php");
	?>
</div>