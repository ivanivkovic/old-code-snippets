<?php
	if(isset($albums))
	{
	
		if($criteria === 'categories'):
		
			foreach($albums as $album)
			{
				$mode = 'user_categories_profile';
				include(Conf::$dir['widgets'] . 'masonry_box.php');
			}
		endif;
		
		if($criteria === 'cities')
		{
			foreach($albums as $album)
			{
				$mode = 'user_cities_profile';
				include(Conf::$dir['widgets'] . 'masonry_box.php');
			}
		}
		
		?><div class="cleaner"></div><?php 
		
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