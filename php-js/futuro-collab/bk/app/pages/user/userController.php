<?php

class Controller extends libControllerBase implements intControllerBase
{
	public function index($param = false)
	{
		if( ! empty( $param ) )
		{
			// Ako je je link s ID-em korisnika, preusmjeri na URL s username-om ili HOME ako je korisnik.
			if( is_string( $param ) && is_numeric( $param ) )
			{
				if( intval( $param ) === Core::$user->id )
				{
					Core::$router->redirect('/user/home' );
				}
				
				if( modelUserData::exists( $param ) )
				{
					Core::$router->redirect('/user/' . modelUserData::getData( $param, 'username') );
				}
				else
				{
					$this->_load404();
				}
			}
			
			if( is_string( $param ) && ! is_numeric( $param ) ) // Param je username string, pretvori u ID i učitaj njegovu stranicu.
			{
				if( $param === Core::$user->data['username'] )
				{
					Core::$router->redirect('/user/home' );
				}
				
				$userId = modelUserData::getIdByUserName( $param );
				
				if( ! empty( $userId ) )
				{
					$this->_loadUserPage( $userId );
				}
				else
				{
					$this->_load404();
				}
			}
		}
		else
		{
			$this->_load404();
		}
	}

	public function home()
	{
		libTemplate::set( 'cPanel', true ); // Aktiviraj kontrolnu ploču.
		
		$this->_loadUserPage( Core::$user->id );
	}

	private function _loadUserPage( $userId )
	{
		$userData = modelUserData::getCompleteData( $userId );
		
		libTemplate::set(
		
				array(
					'title' => $userData['name'] . ' ' . $userData['lastname'],
					'userData' => $userData,
					'topTabs' => array(
						array(
							'title' => 'Povijest',
							'tab' => 'history',
							'active' => true
						),
						array(
							'title' => 'Profil',
							'tab' => 'profile'
						),
						array(
							'title' => 'Zadaci',
							'href' => '/task#userid=' . $userId
						)
					)
				)
		);
		
		libTemplate::loadPage('profile');
	}

	private function _load404()
	{
		Core::$router->load404('User Not Found', 4, Conf::$SETTINGS['timed_refresh_interval']);
	}
}