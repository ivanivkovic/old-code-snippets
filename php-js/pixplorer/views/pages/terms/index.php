<?php include(DIR_WIDGETS . 'html_head.php'); ?>
	
	</head>
	<body>
		
		<?php
				$distance = $this -> registry -> user -> logged_in === true ? '69' : '98';
				$navigation_id = $this -> registry -> user -> logged_in === true ? 'navigation2' : 'navigation';
				include(DIR_WIDGETS . 'navigation.php'); 
			?>
				
		<div class="terms" style="position: absolute; top: <?php echo $distance ?>px;">
		
		<?php
			echo $content;
		?>
		
		</div>
<style>

.terms{
	text-align: left !important;
	width: 98% !important;
	margin-left: 1%;
}

.terms *{
	letter-spacing: .75;
}

h1{
	font-size: 1.35em;
	width: 100%;
	text-align: center;
}

h2{
	font-size: 1.2em;
}
p{ font-size: 13px; }
</style>
		<div id="background"></div>
<?php
	include(DIR_WIDGETS . 'html_footer.php');
?>