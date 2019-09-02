<?php $this->loadWidget('html_head'); ?>
	
	<?php $this->loadSrc('popup_viewer.js') ?>
		
	<?php include(Conf::$dir['widgets'] . 'close_popup_viewer.php'); ?>
	
	</head>
	<body>
	<?php $this->loadWidget('fb_init') ?>
	<div id="background"></div>
	<div id="background2"></div>

	<?php include(Conf::$dir['widgets'] . 'navigation_profile.php'); ?>
	
	<?php
	if(isset($errormsg)){
		?>
		<div id="warning"><?php echo $errormsg ?></div>
		<?php
	}else{
	?>
	<div id="container" data-photo_navigation="true" style="position: absolute; top: 85px;">
		
		<?php include(Conf::$dir['widgets'] . 'photofavorites_' . $action . '.php'); ?>
		
	</div>
	<?php } ?>
<?php $this->loadSrc('masonry.js') ?>
<?php $this->loadSrc('masonry_load.js') ?>

<?php $this->loadWidget('html_footer'); ?>