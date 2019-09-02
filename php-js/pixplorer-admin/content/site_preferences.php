<?php

# Edit site settings.

if(isset($_POST['setting_name']) && isset($_POST['setting1'])){

	$query = 'UPDATE site_settings SET setting1 = "' . $_POST['setting1'] . '"';
	
	if(isset($_POST['setting2'])){
		$query .= ', setting2 = "' . $_POST['setting2'] . '"';
	}
	
	if(isset($_POST['setting3'])){
		$query .= ', setting3 = "' . $_POST['setting3'] . '"';
	}
	
	$query .= ' WHERE setting_name="' . $_POST['setting_name'] . '"';
	
	$db -> query($query);
	
	Logs::setLog('Admin ' . ADMIN_NAME . ' ' . ADMIN_ID_TAG . ' changed site mode to ' . $_POST['setting1'] . '.' , 'site preferences');
	
}

# Fetch site settings.

$result = $db -> query('SELECT * FROM site_settings');

while($fetch = $result -> fetch_array()){
	
	$settings[$fetch['setting_name']]['setting1'] = $fetch['setting1'];
	$settings[$fetch['setting_name']]['setting2'] = $fetch['setting2'];
	$settings[$fetch['setting_name']]['setting3'] = $fetch['setting3'];
	
}

?>

<?php
	$setting_name = 'site_mode';
	$modes = array('ONLINE', 'MAINTENANCE', 'DEVELOPMENT');
?>

<form action="" method="POST">
Site Mode: 
	<input type="hidden" name="setting_name" value="<?php echo $setting_name; ?>"/>
	<?php foreach($modes as $mode): ?>

	<input type="radio" onchange="this.form.submit()" name="setting1" value="<?php echo $mode ?>" 
		<?php
			if($settings[$setting_name]['setting1'] == $mode){
				echo 'checked';
			}
		?>
	/><?php echo ucwords(strtolower($mode)) ?>

	<?php endforeach; ?>

</form>

<div class="border_spacer10"></div>