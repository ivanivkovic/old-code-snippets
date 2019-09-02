<?php include(Conf::$dir['widgets'] . 'html_head.php'); ?>

	<?php $this->loadSrc('popup_viewer.js') ?>

	<?php include(Conf::$dir['widgets'] . 'close_popup_viewer.php'); ?>
	
</script>

</head>


<body id="<?php echo $this->registry->router->page ?>">

<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
<script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script>

<?php $this->loadWidget('fb_init') ?>

<?php  
	
	include(Conf::$dir['widgets'] . 'navigation_album.php'); 

	if(isset($errormsg)){ ?>
	
	<div id="warning"><?php echo $errormsg ?></div>
	
	<?php }else{ ?>

	<div id="container" data-photo_navigation="true" style="position: absolute; top: 85px;">
	
		<?php
		if(isset($result)){
		
			while($fetch = $result->fetch(PDO::FETCH_ASSOC)){
			
				$description = $fetch['description'] == '' ? 'No Description' : $fetch['description'];
				include(Conf::$dir['widgets'] . 'masonry_box.php');
				
			}
			
		}
		
		?>
	
		<div class="cleaner"></div>
		</div>
		
		<?php $this->loadSrc('masonry.js') ?>
		<?php $this->loadSrc('masonry_load.js') ?>

	<?php } ?>
   
	<div id="background"></div>
	<div id="background2"></div>
<?php include(Conf::$dir['widgets'] . 'html_footer.php'); ?>