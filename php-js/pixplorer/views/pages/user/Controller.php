<?php

class Controller extends BaseController{
	
	public function index()
	{
		if($this -> registry -> user -> logged_in)
		{
			die(header('Location: ' . Conf::$page['profile_home']));
		}
		else
		{
			die(header('Location: ' . SITE_URL));
		}
	}
	
	# User logged in home profile page.
	public function home()
	{
		
		$criteria = $this -> getPageCriteria(__FUNCTION__);
		
		$user = $this -> registry -> user;
		
		if($user -> logged_in)
		{
			$this -> registry -> template -> title = $user -> data['fullname'];
			$this -> registry -> template -> user = $user;
			
			if($criteria === 'categories')
			{
				$fetch = Picture::fetchCategoryAlbumsByUser($user -> id);
			}
			elseif($criteria === 'cities')
			{
				$fetch = Picture::fetchAlbumsByLocation($user -> id);	
			}else
			{
				$this -> registry -> template -> errormsg = $this -> registry -> template -> loadString('err_default_error');
			}
			
			if($fetch === false)
			{
				$this -> registry -> template -> errormsg = $this -> registry -> template -> loadString('err_you_not_posted_pics');
			}
			else
			{
				$this -> registry -> template -> albums = $fetch;
			}
			
			$this -> registry -> template -> meta_description = '';
			
			$this -> registry -> template -> fb_meta_url = SITE_URL;
 			$this -> registry -> template -> fb_meta_image = Conf::$src['images'] . 'pix_icon_big.gif';
			
			$nav_headline[0]['title'] = ucfirst($this -> registry -> template -> loadString('conj_my')) . ' ' . ucfirst($this -> registry -> template -> loadString('categories'));
			$nav_headline[0]['link'] = Conf::$page['profile_home'];
			$nav_headline[0]['criteria'] = 'categories';
			
			$nav_headline[1]['title'] = ucfirst($this -> registry -> template -> loadString('conj_my')) . ' ' . ucfirst($this -> registry -> template -> loadString('cities'));
			$nav_headline[1]['link'] = Conf::$page['profile_home'];
			$nav_headline[1]['criteria'] = 'cities';
			
			$this -> registry -> template -> criteria = $criteria;
			$this -> registry -> template -> nav_link = Conf::$page['profile_home'];
			$this -> registry -> template -> nav_headline = $nav_headline;
			$this -> registry -> template -> footer = true;
			
			$this -> registry -> template -> loadTemplate(__FUNCTION__);
		}
		else
		{
			die(header('Location: ' . SITE_URL));
		}
	}
	
	# Viewing a profile by a route criteria.
	public function profile()
	{
		
		$criteria = $this -> getPageCriteria(__FUNCTION__);
		
		$user = $this -> registry -> user;
		$vuser_id = $this -> criteria;
		
		if($user -> id == $this -> criteria)
		{
			header('Location: ' . Conf::$page['profile_home']);
		}
		else
		{
			if(ViewedUser::findInDB($vuser_id) != false)
			{
			
				$this -> registry -> template -> user = $user;
				$user_data = ViewedUser::getDBUserData($vuser_id, array('*'));
		
				$this -> registry -> template -> title = $user_data['fullname'];
		
				$this -> registry -> template -> meta_description = $user_data['fullname'] . '\'s albums';
				$this -> registry -> template -> meta_keywords = $user_data['fullname'];
				
				$this -> registry -> template -> user_data = $user_data;
				$this -> registry -> template -> user_social_url = $user_data['social_type'] == 0 ? 'http://www.facebook.com/' . $user_data['social_id'] : 'http://www.twitter.com/' . $user_data['username'];

				if($criteria === 'categories'){
					
					$fetch = Picture::fetchCategoryAlbumsByUser($vuser_id);
					
				}elseif($criteria === 'cities'){
					
					$fetch = Picture::fetchAlbumsByLocation($vuser_id);
					
				}else{
					
					$this -> registry -> template -> errormsg = $this -> registry -> template -> loadString('err_default_error');
					
				}
			
				if($fetch === false){
					$this -> registry -> template -> errormsg =  $this -> registry -> template -> loadString('err_you_not_posted_pics');
				}else{
					$this -> registry -> template -> albums = $fetch;
				}

				$this -> registry -> template -> fb_meta_url = SITE_URL . $this -> registry -> router -> route;
				$this -> registry -> template -> fb_meta_image = $user_data['profile_pic'];

				$this -> registry -> template -> criteria = $criteria;
				$this -> registry -> template -> nav_link = Conf::$page['profile_view'] . $user_data['user_id'];
				
				$nav_headline[0]['title'] = ucfirst($this -> registry -> template -> loadString('categories'));
				$nav_headline[0]['link'] = Conf::$page['profile_view'] . $user_data['user_id'] . '/';
				$nav_headline[0]['criteria'] = 'categories';
				
				$nav_headline[1]['title'] = ucfirst($this -> registry -> template -> loadString('cities'));
				$nav_headline[1]['link'] = Conf::$page['profile_view'] . $user_data['user_id'] . '/';
				$nav_headline[1]['criteria'] = 'cities';
				
				$this -> registry -> template -> nav_headline = $nav_headline;

			}else{
				$this -> registry -> template -> errormsg = $this -> registry -> template -> loadString('err_user_does_not_exist');
				
				$this -> registry -> template -> title = $this -> registry -> template -> loadString('err_user_not_found');
			
				$this -> registry -> template -> meta_description = $this -> registry -> template -> loadString('err_invalid_user_url');
				$this -> registry -> template -> meta_keywords = '';
			}
			
			$this -> registry -> template -> footer = true;
			$this -> registry -> template -> loadTemplate(__FUNCTION__);
		}
	}
	
	public function favorites()
	{
		
		# Determines whether if it's it's a "my favorites" or "user favorites" page.
		
		$user_id = $this -> registry -> router -> criteria;
		
		if($this -> registry -> user -> logged_in)
		{
		
			if($user_id === 'home')
			{
				$type = 'home';
			}
			else
			{
				if(Security::checkNumNotNull($user_id))
				{
					if($user_id === $this -> registry -> user -> id)
					{
						die(header('Location: ' . Conf::$page['my_favorite_photos']));
					}
					else
					{
						$type = 'profile';
					}
				}else{
					$this -> registry -> template -> errormsg = $this -> registry -> template -> loadString('err_user_does_not_exist');
					$this -> registry -> template -> title = $this -> registry -> template -> loadString('err_user_not_found');
				}
			}
		}else
		{
			if(Security::checkNumNotNull($user_id))
			{
				$type = 'profile';
			}
		}
		
		
		if(!isset($this -> registry -> template -> errormsg)){
		
			# My favorites.
			if($type === 'home'){
			
				$user = $this -> registry -> user;
				$data = PhotoFavorites::fetchFavorites($user -> id);
				
				if($data !== false){
					$this -> registry -> template -> data = $data;
				}else{
					$this -> registry -> template -> errormsg = $this -> registry -> template -> loadString('err_you_no_favorites');
				}
				
				$this -> registry -> template -> title = $this -> registry -> template -> loadString('my_favorite_photos');
				$this -> registry -> template -> user = $user;
					
				$this -> registry -> template -> meta_description = '';
				
				$this -> registry -> template -> nav_headline = $this -> registry -> template -> loadString('my_favorite_photos');
				$this -> registry -> template -> nav_link = Conf::$page['my_favorite_photos'];
				
			}
			
			# User favorites.
			if($type === 'profile')
			{
				$user_data = ViewedUser::getDBUserData($user_id, array('fullname', 'user_id'));
				
				$data = PhotoFavorites::fetchFavorites($user_id);
				
				if($data !== false)
				{
					$this -> registry -> template -> data = $data;
				}
				else
				{
					$this -> registry -> template -> errormsg = $this -> registry -> template -> loadString('err_you_no_favorites');
				}
				
				$title = $user_data['fullname'] . ' ' . $this -> registry -> template -> loadString('favorite_photos');
				
				$this -> registry -> template -> user_data = $user_data;
				$this -> registry -> template -> title = $title;
				$this -> registry -> template -> user = $this -> registry -> user;
				
				$this -> registry -> template -> meta_description = '';
				
				$this -> registry -> template -> nav_headline = $title;
				$this -> registry -> template -> nav_link = Conf::$page['user_favorite_photos'] . $user_id;
			}
		}
		
		$this -> registry -> template -> fb_meta_url = SITE_URL . $this -> registry -> router -> route;
		$this -> registry -> template -> fb_meta_image = Conf::$src['images'] . 'pix_icon_big.gif';
		
		$this -> registry -> template -> action = $type;
		$this -> registry -> template -> footer = true;
		$this -> registry -> template -> loadTemplate(__FUNCTION__ . '_' . $type);
	}
	
	# Gets city parents (regions or countries) and ties them into the result array.
	private function formatCities($data)
	{
		for($i = 0; $i < count($data); ++$i)
		{
			$parent = WorldDatabase::getParent($data[$i]['id']);
			
			if($parent == false)
			{
				$data = false;
			}
			else
			{
				$data[$i]['parent_id'] = $parent['id'];
				$data[$i]['parent_name'] = $parent['name'];
				$data[$i]['parent_type'] = $parent['type'];
			}
		}
		return $data;
	}
	
	# Gets weather this page is user/something/cities or user/something/categories
	private function getPageCriteria($page)
	{
		if($page == 'home')
		{
			$criteria = 'criteria';
		}
		
		if($page == 'profile')
		{
			$criteria = 'criteria2';
		}
		
		if(!isset($this -> registry -> router -> $criteria) || $this -> registry -> router -> $criteria == '')
		{
			return 'categories';	
		}
		else
		{
			if($this -> registry -> router -> $criteria == 'cities' || $this -> registry -> router -> $criteria == 'categories')
			{
				return $this -> registry -> router -> $criteria;
			}
		}
	}
}