<?php include(Conf::$dir['widgets']  . 'html_head.php'); ?>
	</head>
	<body id="<?php echo $this -> registry -> router -> page ?>">
		<?php
			$distance = $this -> registry -> user -> logged_in === true ? '85' : '114';
			$navigation_id = $this -> registry -> user -> logged_in === true ? 'navigation2' : 'navigation';
		?>
		<?php include(Conf::$dir['widgets'] . 'navigation.php'); ?>
			
		<div id="warning" style="position: absolute; top: <?php echo $distance ?>px;"><?php echo $warning ?></div><div id="background"></div>
<?php
	include(Conf::$dir['widgets'] . 'html_footer.php');
?>