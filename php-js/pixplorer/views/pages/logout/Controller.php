<?php

class Controller extends BaseController{
	
	public function index()
	{
	
		$user = $this -> registry -> user;
		
		if($user -> social_type == 0) # Facebook
		{
			session_destroy();
			header('Location: ' . $user -> fb_object -> getLogoutURL($params = array('next' => SITE_URL)));
		}
		else # Twitter etc?
		{
			
		}
	}
	
}