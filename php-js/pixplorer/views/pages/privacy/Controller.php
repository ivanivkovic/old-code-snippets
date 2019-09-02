<?php

class Controller extends BaseController{
	
	public function index(){
	
		$content = $this -> registry -> template -> loadString('privacy');
		
		$this -> registry -> template -> content = $content;
		$this -> registry -> template -> title = $this -> registry -> template -> loadString('privacy_statement');
		
		$this -> registry -> template -> meta_keywords = 'privacy statement';
		$this -> registry -> template -> meta_description = strip_tags($content);
		
		
		$this -> registry -> template -> fb_meta_url = Conf::$page['privacy'];
		$this -> registry -> template -> fb_meta_image = Conf::$src['images'] . 'pix_icon_big.gif';
		
		$this -> registry -> template -> user = $this -> registry -> user;
		$this -> registry -> template -> loadTemplate(__FUNCTION__);
		
	}
	
}