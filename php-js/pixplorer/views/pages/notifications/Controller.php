<?php

class Controller extends BaseController{
	
	public function index(){
			
		if($this->registry->user->logged_in){
			
			$this->registry->template->notifications = Notifications::getAllNotifications($this->registry->user->id);
			
			$this->registry->template->nItems = array(
							0 => $this->registry->template->loadString('notifications_comments'),
							1 => $this->registry->template->loadString('notifications_favorited_photos'),
							2 => $this->registry->template->loadString('notifications_blog')
						);
			
			$this->registry->template->fb_meta_image = Conf::$src['images'] . 'pix_icon_big.gif';
			$this->registry->template->fb_meta_url = SITE_URL;
			$this->registry->template->loadTemplate(__FUNCTION__);
			$this->registry->template->title = $this->registry->template->loadString('title_notifications');
			
		}else{
			header('Location: ' . SITE_URL);
		}
		
	}
}