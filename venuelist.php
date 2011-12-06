<?php

//TODO: get coordinates from user
$items = fs_getVenuesExplore("48.367, 14.517", 10);
?>

<div>
<?php foreach ($items as $item) {?>
	<ul>
		<li><?php echo $item->venue->name; ?></li>
	</ul>
<?php } ?>
</div>