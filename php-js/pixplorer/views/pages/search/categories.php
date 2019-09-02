<?php $this->loadWidget('html_head'); ?>
	</head>
	<body>
	<div id="background"></div>
 
	<?php 
	
	include(Conf::$dir['widgets'] . 'navigation_search.php');
#echo $file;
		if(isset($errormsg)){ 
		?>
			<div id="warning"><?php echo $errormsg ?>
		<?php 
		
		}else{
	?>
			<div id="container" style="position: absolute; top: 85px;">
			
				<?php include(Conf::$dir['widgets'] . $file);
		
		} ?>
			</div>
		
<?php $this -> loadSrc('masonry.js') ?>
<?php $this -> loadSrc('masonry_load.js') ?>

<?php
	$this->loadWidget('html_footer');
?>