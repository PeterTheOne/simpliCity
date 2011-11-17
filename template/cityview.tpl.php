			<div id="landscape">
				<?php
					$self = fs_getSelfCheckinOne();
					//printarray($self);
					$venueID = $self->venue->id;
					printarray(db_citizenInVenue($venueID));
					printarray(db_playersInVenue($venueID));
					printCityValues(db_citizenInVenue($venueID),1,$venueID);
					//echo db_citizenInVenue($venueID);
				?>
				<?php //require_once("canvas-anim.html"); ?>
			</div>