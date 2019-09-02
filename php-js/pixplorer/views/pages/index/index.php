<?php $this->loadWidget('html_head');?>

		<?php $this -> loadSrc('popup_viewer.js') ?>
		<?php $this -> loadWidget('close_popup_viewer'); ?>
		
	</head>
	
	<body id="<?php echo $this -> registry -> router -> page ?>">
	<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
	<script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script>
<script>
$(document).ready(function(){
	if (window.location.hash == '#_=_') {
		window.location.hash = ''; // for older browsers, leaves a # behind
		history.pushState('', document.title, window.location.pathname); // nice and clean
		e.preventDefault(); // no page reload
	}
});
</script>
	<?php $this -> loadWidget('fb_init') ?>
	
		<div id="background"></div>
		<div id="background2" onclick="closePopup()"></div>
		<input type="hidden" id="last_id"/>
		<?php
			$distance = $this -> registry -> user -> logged_in === true ? '85' : '114';
			$navigation_id = $this -> registry -> user -> logged_in === true ? 'navigation2' : 'navigation';
		?>
		<?php include(Conf::$dir['widgets'] . 'navigation.php'); ?>
			
			<div id="container" data-photo_navigation="false" style="position: absolute; top: <?php echo $distance ?>px;">
				<?php
					
					$counter = 0;
					if(!isset($errormsg))
					{
						while($fetch = $result -> fetch(PDO::FETCH_ASSOC))
						{
							$mode = 'standard';
							include(Conf::$dir['widgets'] . 'masonry_box.php');
							
							if($counter == $result -> rowCount() -1)
							{
								$last_id = $fetch['pic_id'];
							}
							++$counter;
						}
					}
				?>
			</div>

<?php $this -> loadSrc('masonry.js') ?>
<?php $this -> loadSrc('masonry_load.js') ?>

<script>
$(document).ready(function(){

	$('#last_id').val(<?php echo $last_id ;?>);
	
	setInterval(function(){
		infiniteScroll('index', $('#last_id').val(), '', '');
	}, 1000);

});
</script>

<?php
	$this->loadWidget('html_footer');
?>