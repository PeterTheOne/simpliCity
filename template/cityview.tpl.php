			<div id="landscape">
				<?php
					$self = fs_getSelfCheckinOne();
					$venueID = $self->venue->id;
					printCityValues(db_citizenInVenue($venueID),db_playersInVenue($venueID),$venueID);
				?>
				<canvas id="canvas" width="100" height="100">
			
				</canvas>
				<script type="text/javascript" src="script/functions.js"></script>
				<script type="text/javascript" src="script/images.js"></script>
				<script type="text/javascript" src="script/canvas.js"></script>
			</div>