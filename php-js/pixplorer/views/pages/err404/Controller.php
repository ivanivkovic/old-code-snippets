<?php

class Controller extends BaseController{

	public function index(){
		$this->registry->template->title = $this->registry->template->loadString('err_invalid_page');
		$this->registry->template->user = $this->registry->user;
		$this->registry->template->warning = $this->registry->template->loadString('err_page_not_exist');
		$this->registry->template->loadTemplate(__FUNCTION__);
		
		$this->registry->template->meta_keywords = 'error page';
		$this->registry->template->meta_description = $this->registry->template->loadString('err_this_is_error_page');
		
		$this->registry->template->fb_meta_image = Conf::$src['images'] . 'pix_icon_big.gif';
		$this->registry->template->fb_meta_url = Conf::$page['404'];
	}
	
}