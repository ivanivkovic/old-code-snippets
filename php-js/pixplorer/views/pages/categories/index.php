<?php include(Conf::$dir['widgets'] . 'html_head.php'); ?>
		<?php $this->loadSrc('dyn_select.js') ?>
	</head>
	<body id="page_<?php echo $this->registry->router->page ?>">
		<div id="background"></div>
		<?php
			$distance = $this->registry->user->logged_in === true ? '85' : '114';
			$navigation_id = $this->registry->user->logged_in === true ? 'navigation2' : 'navigation';
		?>
		<?php include(Conf::$dir['widgets'] . 'navigation.php'); ?>
		
			<div id="container" data-photo_navigation="false" style="position: absolute; top: <?php echo $distance ?>px;">
			
				<?php
					while($fetch = $categories->fetch(PDO::FETCH_ASSOC)){ ?>
					<?php
						
						$imageUrl = str_replace(' ', '%20', ($fetch['title']));
						
					?>
					<div class="item category" data-id="<?php echo $fetch['cat_id'] ?>">
						<div class="darkbox" style="background-image:url(<?php echo Conf::$src['images'] . 'categories/' . $imageUrl ?>.png)">
							<h1><?php echo $fetch['title'] ?> | <?php echo Categories::getPhotosCount($fetch['cat_id']) ?></h1>
						</div>
					</div>
					
					<?php } ?>
				<div class="cleaner"></div>
			</div>
	<?php $this->loadSrc('masonry.js') ?>
	<?php $this->loadSrc('masonry_load.js') ?>
<?php include(Conf::$dir['widgets'] . 'html_footer.php'); ?>