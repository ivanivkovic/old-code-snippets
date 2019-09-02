<?php

if(isset($cur_page)){
	$parts = explode('_', $cur_page);
	$title = ucwords(implode(' ', $parts));
}

?>

<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<title><?php if(isset($title)) { echo $title; }else{ echo 'Pixplorer Admin';} ?></title>
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript" src="src/scripts_gallery.js"></script>
		<link rel="stylesheet" type="text/css" href="src/style.css"/>
<style>
body{
	background: url(<?php echo WEB_PATH ?>src/images/image_background.png);
}
</style>
	</head>
	<body>