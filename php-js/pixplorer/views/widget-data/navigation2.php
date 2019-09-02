<?php

	$items[0]['title'] = $this -> loadString('nav_home');
	$items[0]['href'] = ' href="' . SITE_URL . '"'; 
	$items[0]['pic'] = 'home.png';
	$items[0]['pic_hover'] = 'hover_home.png';
	$items[0]['id'] = 'Home';
	
if(isset($user) && $user -> logged_in){
	$items[1]['title'] = $this -> loadString('nav_cat');
	$items[1]['href'] = ' href="' . Conf::$page['categories'] . '"';
	$items[1]['pic'] = '1.png';
	$items[1]['pic_hover'] = 'hover_1.png';
	$items[1]['id'] = 'Categories';
}

	
	
if(isset($user) && $user -> logged_in){

	$items[2]['title'] = $this -> loadString('nav_upl');
	$items[2]['href'] = '';
	$items[2]['pic'] = '4.png';
	$items[2]['pic_hover'] = 'hover_4.png';
	$items[2]['id'] = 'Upload';
	
}

$items[3]['title'] = $this -> loadString('nav_back');
$items[3]['href'] = ' href="javascript:history.go(-1)" ';
$items[3]['pic'] = 'back.png';
$items[3]['pic_hover'] = 'hover_back.png';
$items[3]['id'] = 'Back';