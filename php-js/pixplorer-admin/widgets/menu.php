<div id="layoutLeft" class="menu layoutElement">
		
		<a href="index.php"><img style="margin-left: 50px;" src="<?php echo SRC_IMAGES ?>logo.png"/></a>
		</br>
		
		<div class="border_spacer10"></div>
				
			<div class="nav_item">
				Logged in: <?php echo ADMIN_NAME ?> <?php echo ADMIN_ID_TAG ?>
			</div>
		
		<div class="border_spacer10"></div>
				
			<div class="nav_item">
				<a href="<?php echo WEB_PATH ?>" target="_blank">						Go to Site</a>
			</div>
			
		<div class="border_spacer10"></div>
		
			
			<div class="nav_item">
				<a href="index.php?page=admin_log" <?php if($cur_page === 'admin_log'){ echo ' class="bold"' ;} ?>>								Log</a>
			</div>
			
			<div class="nav_item">
				<a href="index.php?page=overview" <?php if($cur_page === 'overview'){ echo ' class="bold"' ;} ?>>								Overview</a>
			</div>
			
			<div class="nav_item">
				<a href="index.php?page=site_preferences" <?php if($cur_page === 'site_preferences'){ echo ' class="bold"' ;} ?>>								Site Preferences</a>
			</div>			
			
		<div class="border_spacer10"></div>
		
			<div class="nav_item">
				<a href="index.php?page=photo_administration" <?php if($cur_page === 'photo_administration'){ echo ' class="bold"' ;} ?>>						Photo Administration</a>
			</div>
			<div class="nav_item">
				<a href="index.php?page=user_administration" <?php if($cur_page === 'user_administration'){ echo ' class="bold"' ;} ?>>						User Administration</a>
			</div>
		
		
		<div class="border_spacer10"></div>
		
		<div class="nav_item">
			<a href="logout.php">												Logout</a>
		</div>
		
</div>