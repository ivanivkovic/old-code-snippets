<?php

class Controller extends BaseController{
	
	public function index(){
	
		$content = $this -> registry -> template -> loadString('terms');
		
		$this -> registry -> template -> content = $content;
		
		$this -> registry -> template -> user = $this -> registry -> user;
		
		$this -> registry -> template -> meta_keywords = 'terms of service';
		$this -> registry -> template -> meta_description = strip_tags($content);
		
		$this -> registry -> template -> fb_meta_url = Conf::$page['terms'];
		$this -> registry -> template -> fb_meta_image = Conf::$dir['src_images'] . 'pix_icon_big.gif';
		
		$this -> registry -> template -> title = $this -> registry -> template -> loadString('terms_of_service');
	
		$this -> registry -> template -> loadTemplate(__FUNCTION__);
	}
}