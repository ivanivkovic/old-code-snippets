<?php

if(isset($_POST['log'])){
	
	Logs::setLog(ADMIN_NAME . ' ' . ADMIN_ID_TAG . ' Custom Log : "' . $_POST['log'] . '"', 'admin logs'); 
	
}

if(isset($_GET['category'])){
	$selected_category = $_GET['category'];
}

if(isset($_GET['log'])){
	$selected_log = $_GET['log'];
}

$categories = Logs::getCategories();

if($categories === false){

	echo 'Log directories not found.';
	
}else{

	?>

	<form class="fl" action="" method="GET">
		<input type="hidden" name="page" value="<?php echo CUR_PAGE ?>"/>
		Log: <select name="category" onchange="window.location='index.php?page=<?php echo CUR_PAGE ?>&category='+this.value; e.preventDefault(); return false;">
			<?php
		
				$counter = 0;
				$count = count($categories);
				
				while($counter <= $count){
					
					if(isset($categories[$counter])){
					
						if($counter === 0){
						
							if(!isset($selected_category)){
							
								$selected_category = $categories[$counter];
								
							}
							
						}
						
						?>
						
						<option value="<?php echo $categories[$counter] ?>" <?php if($selected_category === $categories[$counter]){ echo 'selected'; }?>>
							<?php echo ucwords( $categories[$counter] ) ?>
						</option>
						
						<?php
					
					}
					++$counter;
				}
			?>
		</select>
		<?php
		
			$logs = Logs::getLogs($selected_category);
			
			if($logs !== false):
			
		?>
		<select name="log" onchange="this.form.submit()">
			<?php
				$counter = 0;
				
				foreach($logs as $log){
				
					if($counter === 0){
					
						if(!isset($selected_log)){
						
							$selected_log = $log;
							
						}
						
					}
			?>
					
					<option value="<?php echo $log ?>" <?php if($selected_log === $log){ echo 'selected'; }?>>
						<?php echo $log ?>
					</option>
					
					<?php
					
					++$counter;
				}
			?>
		</select>
		
		<?php endif; ?>
		
	</form>
	
	<button class="fr" onclick="newLog();">Write a Log</button>

	<div class="cleaner"></div>
	
	<div class="border_spacer10"></div>
	
	<div id="logs_container">
	<?php
	
	if(isset($selected_log)){

		$content = Logs::getLog($selected_category, $selected_log);
		$content = Logs::convertToHTML($content);

		if($content === false){
			
			echo 'File empty.';
			
		}else{

			echo $content;
			
		}
	
	}else{
	
		echo 'No current logs for your criteria.';
		
	}
	?>
	</div>
	<?php
}
?>

<script>

function newLog(){
	
	var log = prompt("Enter your log below:");
	
	if(log.length){
		$('#log').val(log);
		$('#log_form').submit();
	}
	
}

</script>

<form action="index.php?page=admin_log&category=admin%20logs" id="log_form" method="POST">
	<input type="hidden" id="log" name="log" />
</form>