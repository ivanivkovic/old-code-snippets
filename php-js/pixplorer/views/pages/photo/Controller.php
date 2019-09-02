<?php

class Controller extends BaseController{
	
	public function index()
	{
		die(header('Location: ' . SITE_URL . ''));
	}

	public function view()
	{
		$pic_id = $this->registry->router->criteria;
		
		if(Picture::exists($pic_id))
		{
			$data = Picture::getAllPicRelatedData($pic_id);
			$this->registry->template->data = $data; # FOR PHOTO VIEWER
			
			$this->registry->template->title = $data['description'] != '' ? $data['fullname'] . ' - ' . $data['description'] : $data['fullname'] . ' ' . $this->registry->template->loadString('conj_in') . ' ' . $data['cityName'];
			$this->registry->template->meta_description = $data['description'] != '' ? $data['description'] : $data['fullname'] . ' ' . $this->registry->template->loadString('conj_in') . ' ' . $data['cityName'];
			
			$this->registry->template->meta_keywords = '';
			
			$this->registry->template->fb_meta_image = Conf::$src['images'] . $data['user_id'] . $data['src'];
			$this->registry->template->fb_meta_url = Conf::$page['photo_view'] . $pic_id;
			
			$this->registry->template->user = $this->registry->user;
			
			$this->registry->template->options = $data['user_id'] == $this->registry->user->id ? 'home' : 'view';
			
		}else{
			$this->registry->template->user = $this->registry->user;
			$this->registry->template->title = $this->registry->template->loadString('err_picture_not_exist');
			$this->registry->template->meta_description = $this->registry->template->loadString('err_picture_not_exist2');
		}
		
		$this->registry->template->loadTemplate(__FUNCTION__);
		
	}
	
}