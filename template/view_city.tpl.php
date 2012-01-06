			<div id="landscape">
				<?php
					require_once("includes/functions_city.inc.php");
					getCityValues();
				?>
				<canvas id="canvas" width="100" height="100">
			
				</canvas>
				<script type="text/javascript" src="script/functions.js"></script>
				<script type="text/javascript" src="script/images.js"></script>
				<script type="text/javascript" src="script/canvas.js"></script>
			</div>
			<div id="menu">
				<?php
					require_once("template/element_menu.tpl.php");
				?>
				<p id="cityname">
					&nbsp;
				</p>
				<script type="text/javascript" src="script/citymenu.js"></script>
				<script type="text/javascript" src="script/citizenMenu.js"></script>
			
				<div id="lowerLeft">
					<div class="button" id="addBtn"><img src="images/interface/addCitizenIcon.png" alt="add citizen"/></div>
				</div>
				<p id="citizenCount">
					
				</p>
			</div>			
			<div id="overlay">
				<div id="citizenTable">
				
				</div>
				<div id="citizenTitle">
					<div id="goBack"><img src="images/interface/left.png" alt="left"/></div>
					<div id="goForward"><img src="images/interface/right.png" alt="right"/></div>
					<div id="citizenTitles">
						<div id="currentTitle"></div>
					</div>
				</div>
				<div id="citizenDescription"></div>
				<div id="removeCitizen" class="button"><img src="images/interface/minus.png" alt="remove"/></div>
				<div id="addCitizen" class="button"><img src="images/interface/plus.png" alt="add"/></div>
			</div>