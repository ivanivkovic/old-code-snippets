<?php
	if(isset($albums))
	{
			
		foreach($albums as $album)
		{
			
			if($criteria === 'categories'):
				
				$mode = 'user_categories_home';
				include(Conf::$dir['widgets'] . 'masonry_box.php');
				
				else: 
					$mode = 'user_cities_home';
					include(Conf::$dir['widgets'] . 'masonry_box.php');
					
			endif;
		}
?>
<div class="cleaner"></div>
<?php 

	}else{/*
		if(isset($errormsg)){
			?>
			<div id="warning">
			<?php echo $errormsg; ?>
			</div>
			<?php
		}*/
	}
?>