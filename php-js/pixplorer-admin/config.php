<?php

ini_set('display_errors', true);

error_reporting(E_ALL);

define('CUR_DATE', date('Y-m-d'));
define('CUR_TIME', date('H:i:s'));

session_start();

include('includes/Logs.php');

# DB connect.
define('SERVER',	'localhost');
define('USER',		'ivanivk_pixplore');
define('PASS',		'W_eLV.{Gp,-G');
define('DB',		'ivanivk_pixplorer');
	
$db = new MySQLI(SERVER, USER, PASS, DB);
$db -> query('SET NAMES UTF8');
$db -> query('SET NAMES "UTF8"');


# Current page and current script.
$parts = explode('/', $_SERVER['SCRIPT_NAME']);
$cur_file = end($parts);

if(isset($_GET['page'])){

	$cur_page = $_GET['page'];
	
}else{

	$cur_page = 'admin_log';
}

define('CUR_PAGE', $cur_page);

# Admin auth.
if(isset($_SESSION['username'])){

	$result = $db -> query('SELECT * FROM admin_users WHERE username="' . $_SESSION['username'] . '"');

	if($result -> num_rows){

		$fetch = $result -> fetch_array();
			
		if(isset($_SESSION['password']) && $_SESSION['password'] === $fetch['password'] && isset($_SESSION['password2']) && $_SESSION['password2'] === $fetch['password2']){

			$admin = true;
			define('ADMIN_NAME', $fetch['fullname']);
			define('ADMIN_ID', $fetch['id']);
			define('ADMIN_ID_TAG', '(#' . ADMIN_ID . ')');
			define('ADMIN_USERNAME', $fetch['username']);

		}else{
			
			$admin = false;
			
		}
	}else{
		
		$admin = false;
		
	}

}else{

	$admin = false;
	
}


if(!$admin && $cur_file !== 'login.php'){
	die(header('Location: login.php'));
}

function __autoload($class){
	$path = 'models/' . $class . '.php';
	include($path);
}

define('WEB_PATH', 'http://pixplorer.ivanivkovich.com/');

define('ADMIN_PATH', 'http://admin.pixplorer.ivanivkovich.com/');

define('PAGE_INDEX', 				WEB_PATH );
define('PAGE_ALBUM_GENERATED',		WEB_PATH . 'album/generated/');
define('PAGE_CATEGORIES', 			WEB_PATH . 'categories/');
define('PAGE_404', 					WEB_PATH . 'err404/');
define('PAGE_SEARCH_CATEGORIES', 	WEB_PATH . 'search/categories/');
define('PAGE_SEARCH_KEYWORD', 		WEB_PATH . 'search/keyword/');
define('PAGE_PROFILE_HOME',			WEB_PATH . 'user/home/'); 
define('PAGE_PROFILE_VIEW',			WEB_PATH . 'user/profile/');
define('PAGE_MY_FAVORITE_PHOTOS',		WEB_PATH . 'user/favorites/home');
define('PAGE_USER_FAVORITE_PHOTOS',		WEB_PATH . 'user/favorites/');
define('PAGE_PHOTO_VIEW', 			WEB_PATH . 'photo/view/');
define('PAGE_FB_LOGIN', 			WEB_PATH . 'fb-login/');
define('PAGE_TERMS', 				WEB_PATH . 'terms/');
define('PAGE_PRIVACY', 				WEB_PATH . 'privacy/');
define('PAGE_UPLOAD_RESULT',		WEB_PATH . 'upload/result/');
define('PAGE_UPLOAD_ADD_INFO',		WEB_PATH . 'upload/addinfo/');
define('PAGE_LOGOUT', 				WEB_PATH . 'logout/');
define('AJAX', 						WEB_PATH . 'ajax/');
		
define('DEFAULT_LOGIN_URL', 		PAGE_FB_LOGIN);

define('ROOT_SITE_PATH', 	'../_portfolio_pixplorer');
define('DIR_WIDGETS', 		ROOT_SITE_PATH . '/views/widgets/');
define('DIR_WIDGET_DATA', 	ROOT_SITE_PATH . '/views/widget-data/');
define('DIR_INCLUDES', 		ROOT_SITE_PATH . '/includes/');
define('DIR_STYLES', 		ROOT_SITE_PATH . '/src/styles/');
define('DIR_SCRIPTS', 		ROOT_SITE_PATH . '/src/scripts/');
define('DIR_IMAGES', 		ROOT_SITE_PATH . '/src/images/');
		
define('DIR_PICS', 		ROOT_SITE_PATH . '/content/pics/');
define('DIR_THUMBS',	ROOT_SITE_PATH . '/content/thumbs/');


define('SRC_PICS', 		WEB_PATH . 'content/pics/');
define('SRC_THUMBS',	WEB_PATH . 'content/thumbs/');
define('SRC_SCRIPTS',	WEB_PATH . 'src/scripts/');
define('SRC_STYLES', 	WEB_PATH . 'src/styles/');
define('SRC_IMAGES', 	WEB_PATH . 'src/images/');

?>