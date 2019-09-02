<?php

/*
	Some documentation.
	
	Models are made all the same to fit the application, for faster development. (it's an admin board afterall).
	editor.php is a universal editor for content like images, users, albums etc made by me...
	index.php (this file) loads a page which then sets some settings for editor.php, then loads up the editor.php, so most of the development is done in models	
	
	Model blueprint:
		listCriterias lists any criterias the model will be searched by.
		getCriteriaCount is counter for some criterias, if criteria is DATE, the criteria count would be how much model items are loaded that day (pictures uploaded in a day)
		listEntitiesByCriteria lists model entities by a given criteria (lists photos by date)
	
	editor.php settings
	
		
		$object - Model for the specific admin tool that uses editor.php

		$criteria - Default criteria.

		$not_found - Message if nothing is found.
		$not_found_by_criteria - Message if nothing is found by criteria

		$delete = true; - Enabling delete function.

		$criteria_setting = true; - Enabling criterias on/off.

	Ivan Ivković
	
*/
include('config.php');

include('widgets/header.php');

?>

<div class="wrapper">
	
	<div class="spacer50"></div>
	
	<?php include('widgets/menu.php'); ?>
	
	<div id="layoutRight" class="layoutElement">
		<?php
		
			if(isset($cur_page)){

				$file = 'content/' . $cur_page . '.php';
				
				if(is_file($file)){
					
					$parts = explode('_', $cur_page);
					$title = ucfirst(implode(' ', $parts));
					
					include($file);
					
				}else{
					
					$file = 'content/admin_log.php';
					
					if(is_file($file)){
					
						include($file);

					}
					
				}
				
			}
			
		?>
	</div>
	
</div>

<?php

include('widgets/footer.php');

?>