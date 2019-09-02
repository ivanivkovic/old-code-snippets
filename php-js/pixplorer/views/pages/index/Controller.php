<?php

class Controller extends BaseController{
	
	public function index(){
	
		$result = Picture::getInfiniteScrollData(PAGE_LOAD_ITEM_LIMIT, 0);
		
		if($result)
		{
			$this -> registry -> template -> result = $result;
		}
		else
		{
			$this -> registry -> template -> errormsg = $this -> registry -> template -> loadString('err_no_upl_images');
		}
		
		if($this -> registry -> user -> logged_in)
		{
			$this -> registry -> template -> title = $this -> registry -> template -> loadString('title_home');
		}
		else
		{
			$this -> registry -> template -> title = '';
		}
		
		$this -> registry -> template -> meta_keywords = '';
		$this -> registry -> template -> meta_description = $this -> registry -> template -> loadString('explore_enter');
		
		$this -> registry -> template -> fb_meta_image = Conf::$src['images'] . 'pix_icon_big.gif';
		$this -> registry -> template -> fb_meta_url = SITE_URL;
		$this -> registry -> template -> footer = true;
		
		$this -> registry -> template -> loadTemplate(__FUNCTION__);
		
	}
}