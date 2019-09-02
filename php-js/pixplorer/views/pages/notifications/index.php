<?php include(Conf::$dir['widgets'] . 'html_head.php');?>

		<?php $this->loadSrc('popup_viewer.js') ?>
		<?php $this->loadWidget('close_popup_viewer'); ?>
		
	</head>
	
	<body id="<?php echo $this->registry->router->page ?>">
		<div id="background"></div>
		<div id="background2"></div>
		<?php
			$distance = $this->registry->user->logged_in === true ? '85' : '114';
			$navigation_id = $this->registry->user->logged_in === true ? 'navigation2' : 'navigation';
		
			include(Conf::$dir['widgets'] . 'navigation.php'); 
		?>
		
			<div id="container" data-photo_navigation="false" style="position: absolute; top: <?php echo $distance ?>px;">
			<?php
			
				foreach($nItems as $key => $item){
					include(Conf::$dir['widgets'] . 'notifications.php');
				}
				
			?>
				
			</div>
<script>
$(window).load(function(){
	clearNotifications();
});
</script>
<?php $this->loadSrc('masonry.js') ?>
<?php $this->loadSrc('masonry_load.js') ?>
<style> .darkbox h1{ text-shadow: none !important; } <style>
<?php
	include(Conf::$dir['widgets'] . 'html_footer.php');
?>