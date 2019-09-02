<?php

class Controller extends libControllerBase implements intControllerBase
{
	public function index($param = false)
	{
		if( empty( $_POST ) )
		{
			$this->_LoginPage();
		}
		else
		{
			if( isset($_POST['username']) && isset($_POST['password']) )
			{
				if( $userid = Core::$user->validateLogin($_POST['username'], $_POST['password']) )
				{
					Core::$user->login( $userid );
					Core::$router->redirect('/');
				}
				else
				{
					$this->_LoginPage( true );
				}
			}
		}
	}
	
	private function _LoginPage( $failed = false )
	{
		if( $failed === true )
		{
			libTemplate::set( array( 'title' => 'Neuspjeli login', 'error' => 'Neispravno korisničko ime ili lozinka!') );
			libTemplate::loadPage( 'login' );
		}
		else
		{
			libTemplate::set( 'title', 'Login' );
			libTemplate::loadPage( 'login' );
		}
	}
}