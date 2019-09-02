<?php

# Album Controller

class Controller extends BaseController{
	
	public function index()
	{
		die(header('Location: ' . SITE_URL));
	}
	
	# Listout of albums.
	public function generated()
	{
	
		$user_id = $this->criteria;
		$cat_id = $this->criteria2;
		$city_id = $this->criteria3;
		
		if(Security::checkNum($user_id) !== false && Security::checkNum($city_id) !== false && Security::checkNum($cat_id) !== false)
		{
			$fetch = Picture::fetchAlbumPics($user_id, $city_id, $cat_id);
			$cat_name = '';
			
			if(Security::checkNumNotNull($cat_id) !== false && Categories::fetchCatName($cat_id) !== false)
			{
				$cat_name = Categories::fetchCatName($cat_id);
				$this->registry->template->cat_id = $cat_id;
			}
			
			if($fetch !== false)
			{
				$this->registry->template->result = Picture::fetchAlbumPics($user_id, $city_id, $cat_id);
				$fetch2 = $fetch->fetch(PDO::FETCH_ASSOC);
				$this->registry->template->fb_meta_image = SITE_URL . 'content/pics/' . $fetch2['user_id'] . $fetch2['src'];
			}
			else
			{
				$this->registry->template->errormsg = 'There are no pictures in this album. <a href="/user/profile/' . $user_id . '">Go to profile.</a>';
				$this->registry->template->meta_description = 'This album may have been deleted or does not exist.';
				$this->registry->template->fb_meta_image = Conf::$src['images'] . 'pix_icon_big.gif';
			}
			
			$this->registry->template->user = $this->registry->user;
			
			$city_name = $city_id != '' && $city_id != false ? clearAsterisk(WorldDatabase::fetchName($city_id, 'city')) : '';
			
			$parent = WorldDatabase::getParent($city_id);
			$parent_name = $parent['name'];
			
			if($user_id != '' && $user_id != false)
			{
				$user_data = ViewedUser::getDBUserData($user_id, array('fullname'));
				$user_name = $user_data['fullname'];
			}
			else{
				$user_name = '';
			}
			
			if($user_name != '' && $city_name != '')
			{
				$ext = ', ' . clearAsterisk($city_name);
			}
			
			if($user_name != '' && $cat_name != '')
			{
				$ext = ', ' . $cat_name;
			}
			
			if($fetch !== false)
			{
				$this->registry->template->meta_description = $user_name . $ext;
				$this->registry->template->meta_keywords = $user_name  . ',' . $city_name;
			}
			
			$this->registry->template->title = $user_name . $ext . ' ' . $city_name;
			
			if($city_name !== '')
			{
				$this->registry->template->city_name = $city_name;
			}
			
			$this->registry->template->nav_headline = 'Photos by ' . $user_name . $ext;
			$this->registry->template->nav_link = $city_id;
		}else{
			$this->registry->template->errormsg = $this->registry->template->loadString('err_default_error');
		}
		
		if($this->registry->user->logged_in && $this->registry->user->id == $user_id)
		{
			$this->registry->template->options = 'home';
		}
		
		if($this->registry->user->logged_in && $this->registry->user->id != $user_id)
		{
			$this->registry->template->options = 'view';
		}
		
		$this->registry->template->fb_meta_url = SITE_URL . $this->registry->router->route;
		$this->registry->template->loadTemplate(__FUNCTION__);
	}
}