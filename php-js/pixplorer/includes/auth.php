<?php
	# User authorization.
	if(!$registry -> user -> logged_in)
	{
		if($registry -> user -> getSession() !== false)
		{
			if($registry -> user -> loggedInViaAnyNetwork() !== false)
			{
				$registry -> user -> logged_in = true;
				$registry -> user -> getSession();
				
				if($registry -> user -> id != false)
				{
					$registry -> user -> getDBSocialData();
					
					if($registry -> user -> social_type == 0)
					{
						$registry -> user -> social_url = 'http://www.facebook.com/';
						$registry -> user -> facebook_connect = true;
					}
					
					if($registry -> user -> social_type == 1)
					{
						$registry -> user -> social_url = 'http://www.twitter.com/';
					}
					
					$registry -> user -> getDBUserData();
				}else{
					echo 'No user id found!';
				}
			}else{
			
				# In case social sdk or api fails.
				session_destroy();
				header('Location: ' . SITE_URL);
				
			}
		}
	}

?>