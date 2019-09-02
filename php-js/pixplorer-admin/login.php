<?php

include('config.php');

if($admin === true && $cur_file === 'login.php'){
	die(header('Location: index.php'));
}

if(	isset($_POST['username']) && $_POST['username'] !== '' &&
	isset($_POST['password']) && $_POST['password'] &&
	isset($_POST['password2']) && $_POST['password2']
){
	
	$result = $db -> query('SELECT * FROM admin_users WHERE username="' . $_POST['username'] . '"');
	
	if($result -> num_rows){
	
		$fetch = $result -> fetch_array();
		
		if(sha1($_POST['password']) === $fetch['password'] && sha1($_POST['password2']) === $fetch['password2']){
		
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['password'] = sha1($_POST['password']);
			$_SESSION['password2'] = sha1($_POST['password2']);
			
			define('ADMIN_NAME', $fetch['fullname']);
			define('ADMIN_ID', $fetch['id']);
			define('ADMIN_ID_TAG', '(#' . ADMIN_ID . ')');
			
			Logs::setLog('Admin ' . ADMIN_NAME . ' ' . ADMIN_ID_TAG . ' LOGGED IN to admin panel.' , 'admin logins');
			
			die(header('Location: index.php'));
			
		}
	}
}

include('widgets/header.php');

include('widgets/login_form.php');

include('widgets/footer.php');

?>