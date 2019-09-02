<?php $this->loadWidget('html_head'); ?>
	</head>
	<body>

	<div id="background"></div>

	<?php $this->loadWidget('navigation_profile'); ?>

	<div id="container" style="position: absolute; top: 85px;">
	
		<?php if(isset($user_data)): ?>
		
		<div id="profileBox" class="item element" style="height: 100%; cursor: default;">
			<a class="item_link">
				<img alt="" id="profileImage" onload="vAlignMe('#profileImage');" src="<?php echo $user_data['profile_pic']?>"/>
			</a>
			
			<div class="bottom_part">
			
				<div class="profileBottom">
					
					
					<div class="sbutton">
						<a target="_blank" href="<?php echo Conf::$url['facebook'] ?>/<?php echo $user_data['social_id'] ?>">
							<img alt="" class="hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>facebook_hover.png" src="<?php echo Conf::$src['images'] ?>facebook.png"/>
						</a>
					</div>
					
					<div class="sbutton">
						<?php
							$href = isset($user_data) ? Conf::$page['user_favorite_photos'] . $user_data['user_id'] : Conf::$page['my_favorite_photos'];
							$title = isset($user_data) ? 'user_favorite_photos' : 'my_favorite_photos';
						?>
						<a title="<?php echo $this -> loadString($title) ?>" href="<?php echo $href ?>">
							<img alt="" class="nav_img hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>nav/hover_favorites.png" src="<?php echo Conf::$src['images'] ?>nav/favorites.png"/>
						</a>
					</div>
					
					<span><?php echo $user_data['fullname'] ?></span>
					
					<div class="cleaner"></div>
					
				</div>
				
			</div>
		</div>
		
		<?php else: 
			echo '<div id="warning">' . $this -> loadString('err_user_does_not_exist') . '</div>';
		endif; ?>
		
		<?php include(Conf::$dir['widgets'] . 'user_categories_' . $this -> registry -> router -> action . '.php'); ?>
		
	</div>

	<?php $this -> loadSrc('masonry.js') ?>
	<?php $this -> loadSrc('masonry_load.js') ?>

<?php $this->loadWidget('html_footer'); ?>