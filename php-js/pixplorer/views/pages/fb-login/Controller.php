<?php

class Controller extends BaseController{
	
	public function index(){
	
		$user = $this->registry->user;
		
		if(!$user->logged_in)
		{
			# Facebook auth.
			if($user->fb_object->getUser() == 0)
			{
				header('Location: ' . $user->fb_object->getLoginUrl(array('redirect_uri' => SITE_URL . 'fb-login', 'scope' => 'publish_stream')));
			}
			else
			{
				$fb_id = $user->fb_object->getUser();
				# If the user session is not yet made, create the user in the db if not exists.
				if($user->getSession() == false)
				{
					if($user->findInDB($fb_id, 0) != false)
					{
						$id = $user->findInDB($fb_id, 0);
					}
					else
					{
						$id = $user->registerToDB($fb_id, 0);						
					}
					$user->logged_in = true;
					$user->id = $id;
					
					$user->getDBSocialData();
					$user->getSocialNetworkData();
					$user->convertSocialData();
					$user->updateDBUserData();
					
					$_SESSION['user'] = $id;
					header('Location: ' . SITE_URL);
				}
			}
		}else{
			header('Location: ' . SITE_URL);
		}
	}
}