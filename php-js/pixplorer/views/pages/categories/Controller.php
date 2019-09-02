<?php

class Controller extends BaseController{
	
	public function index()
	{
		$this->registry->template->user = $this->registry->user;
		$this->registry->template->title = $this->registry->template->loadString('search_by_categories');
		
		$category = Categories::fetchCategories();
		$categories = '';
		
		while($fetch = $category->fetch(PDO::FETCH_ASSOC))
		{
			$categories .= ',' . $fetch['title'];
		}
	
		$category = Categories::fetchCategories(0);
		
		$this->registry->template->categories = $category;

		$this->registry->template->fb_meta_image = Conf::$src['images'] . 'pix_icon_big.gif';
		$this->registry->template->fb_meta_url = Conf::$page['categories'];
		
		$this->registry->template->meta_keywords = 'pixpresso,categories' . $categories;
		$this->registry->template->meta_description = 'Pixpresso categories.';
		
		
		$this->registry->template->loadTemplate(__FUNCTION__);
	}
	
}