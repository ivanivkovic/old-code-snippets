<?php

class Controller extends libControllerBase implements intControllerBase
{
	public function index($param = false)
	{
		if( Core::$user->loggedIn() )
		{
			Core::$user->logout();
			Core::$router->redirect('/login');
		}
	}
}