<?php

/**
* Error list:
* 
* 0 => No error
* 1 => Nepotpuni podaci
* 2 => Došlo je do pogreške
* 
*/


ini_set('short_open_tag', true);

session_start();

error_reporting(-1);
ini_set('display_errors', true);


const OUTPUT = 'static';

include('config.php');

Core::$router->setRoute();

if( ! Core::$user->loggedIn() )
{
	if( Core::$router->route['page'] !== 'login' )
	{
		Core::$router->redirect('/login');
	}
}
else
{
	Core::$user->createData();
	
	if( Core::$router->route['page'] === 'login' )
	{
		Core::$router->redirect('/index');
	}
}

Core::$router->loadScript( OUTPUT );

?>