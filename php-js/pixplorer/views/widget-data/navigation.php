<?php
	
	$items[2]['title'] = $this -> loadString('nav_home');
	$items[2]['href'] = ' href="' . SITE_URL . '" ';
	$items[2]['pic'] = 'home2.png';
	$items[2]['pic_hover'] = 'hover_home2.png';
	$items[2]['id'] = 'Home';
	
	
	$items[4]['title'] = $this -> loadString('nav_cat');;
	$items[4]['href'] = ' href="' . Conf::$page['categories'] . '"';
	$items[4]['pic'] = '1.png';
	$items[4]['pic_hover'] = 'hover_1.png';
	$items[4]['id'] = 'Categories';
	
	

if($this -> registry -> user -> logged_in){

	$items[5]['title'] = $this -> loadString('nav_upl');
	$items[5]['href'] = '';
	$items[5]['pic'] = '4.png';
	$items[5]['pic_hover'] = 'hover_4.png';
	$items[5]['id'] = 'Upload';
	
}