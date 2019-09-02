<?php $this->loadWidget('html_head');?>
	<?php $this->loadSrc('popup_viewer.js') ?>
	<?php $this->loadWidget('close_popup_viewer'); ?>
<script>

window.onload = function(){
	vAlignMe('.profile_picture');
};

</script>
	</head>

	<body>
<?php $this->loadWidget('fb_init') ?>
	<div id="background"></div>
	<div id="background2"></div>
	<input type="hidden" id="last_id"/>
	<input type="hidden" id="keyword" value="<?php echo $keyword ?>"/>
 
	<?php  include(Conf::$dir['widgets'] . 'navigation_search.php'); 

		if(isset($errormsg)){ 
		?>
			<div id="warning"><?php echo $errormsg ?>
		<?php 
		
		}else{ 
		?>
		
		<div id="container" data-photo_navigation="false" style="position: absolute; top: 85px;">
		
		<?php
			$c = 0;
			while($fetch = $result->fetch(PDO::FETCH_ASSOC)){
				/*
				place an if statement on which models to load for this loop
				*/
				switch($fetch['type']){
					
					default:
						$mode = 'search_' . $fetch['type'];
					break;
					
				}
				
				include(Conf::$dir['widgets'] . 'masonry_box.php');
				
				if($c == $result->rowCount() -1){
					$last_id = $c + 1;
				}
				
				$c++;
			}
			
		?>
		
		</div>
		
		<?php
		
		}
		
		?>



<?php $this->loadSrc('masonry.js'); ?>
<?php $this->loadSrc('masonry_load.js'); ?>

<script>
$(document).ready(function(){

	$('#last_id').val(<?php echo $last_id ;?>);
	
	setInterval(function(){
		infiniteScroll('search', $('#last_id').val(), $('#keyword').val(), '');
	}, 1000);

});
</script>

<?php $this->loadWidget('html_footer'); ?>