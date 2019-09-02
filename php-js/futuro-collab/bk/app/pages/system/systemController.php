<?php

class Controller extends libControllerBase implements intControllerBase
{
	// Glavna stranica - statistika, info i takve stvari.
	public function index($param = false)
	{
		$this->redirect();
		
		if( Core::$user->level === 0 )
		{
			libTemplate::set(
							array(
								'title' => 'Sustav',
								'stats' => $this->getStats(),
								'pageNav' => $this->setPageNav( __FUNCTION__ )
							)
			);
		}
		
		libTemplate::loadPage('stats');
	}
	
	// Stranica za CRUD korisnika.
	public function users()
	{
		$this->redirect();
		
		if( Core::$user->level === 0 )
		{
			if( ! empty ( $_POST ) )
			{
				if( libForm::validatePostForm( array('name', 'lastname', 'username', 'role', 'level', 'phone', 'einfo', 'action'), array( 'name', 'lastname', 'username', 'level', 'role' ) ) && $_POST['action'] === 'insert' )
				{
					// Insert korisnika.
					unset( $_POST['action'] );
					
					if( modelUserData::createUser( $_POST ) )
					{
						libTemplate::addSuccess(7);
					}
					else
					{
						libTemplate::addError(1);
					}
				}
				
				if( libForm::validatePostForm( array('status', 'userid', 'name', 'lastname', 'username', 'role', 'level', 'phone', 'einfo', 'action'), array( 'name', 'lastname', 'username', 'level', 'role' ) ) && $_POST['action'] === 'update' && modelUserData::exists( $_POST['userid'] ) )
				{
					// Update korisnika.
					unset( $_POST['action'] );
					
					if( modelUserData::updateUser( $_POST ) )
					{
						libTemplate::addSuccess(8);
					}
					else
					{
						libTemplate::addError(2);
					}
				}
			}
			
			$userList = modelUserData::getUserList(2);
			$adminList = modelUserData::getUserList(1);
			$superAdminList = modelUserData::getUserList(0);
			
			libTemplate::set(
							array(
								'title' => 'Korisnici',
								'pageNav' => $this->setPageNav( __FUNCTION__ ),
								'topTabs' => array(
									array(
										'title' => 'Administratori (' . count( $adminList ) . ')',
										'tab' => 'admins',
										'active' => true
									),
									array(
										'title' => 'Korisnici (' . count( $userList ) . ')',
										'tab' => 'users'
									),
									array(
										'title' => 'Glavni Administratori (' . count( $superAdminList ) . ')',
										'tab' => 'superadmins'
									)
								),
								'userList' => $userList,
								'adminList' => $adminList,
								'superAdminList' => $superAdminList
							)
			);
			
			libTemplate::loadPage('users');
		}
	}
	
	// Stranica za postavke (in construction).
	public function settings()
	{
		$this->redirect();
		
		if( Core::$user->level === 0 )
		{
			libTemplate::set(
							array(
								'title' => 'Postavke',
								'pageNav' => $this->setPageNav( __FUNCTION__ )
							)
			);
			
			libTemplate::loadPage('settings');
		}
	}
	
	// Pod-stranice navigacija.
	public function setPageNav( $page )
	{
		$menu = array(
			array(
				'title' => 'Info',
				'href' => '/system/index'
			),
			array(
				'title' => 'Korisnici',
				'href' => '/system/users'
				)
				
			/*	
			,
			array(
				'title' => 'Postavke',
				'href' => '/system/settings'
			)
			*/
		);
		
		foreach( $menu as &$item )
		{
			$parts = explode('/', $item['href']);
			
			if( end( $parts ) === $page )
			{
				$item['active'] = true;
			}
		}
		
		return $menu;
	}

	public function getStats()
	{
		$data = array();
		
		$fetch = Core::$db->fetchSQL('
		
		SELECT * FROM
		
		(
			SELECT
				COUNT( userid ) AS value,
				"superAdminCount" AS type
			FROM
				?user
			WHERE
				?user.level = 0
		
		UNION
			
			SELECT
				COUNT( userid ) AS value,
				"adminCount" AS type
			FROM
				?user
			WHERE
				?user.level = 1
		
		UNION
		
			SELECT
				COUNT( userid ) AS value,
				"userCount" AS type
			FROM
				?user
			WHERE
				?user.level = 2
		
		UNION
			
			SELECT
				COUNT( projectid ) AS value,
				"projectCount" AS type
			FROM
				?project
				
		UNION
			
			SELECT
				COUNT( taskid ) AS value,
				"taskCount" AS type
			FROM
				?task
			WHERE status = 0
		)
		
		AS stats
		');
		
		foreach( $fetch as $item )
		{
			$data[ $item['type'] ] = $item['value'];
		}
		
		return $data;
	}

	// System stranice su dostupne samo glavnim administratorima.
	public function redirect()
	{
		if( Core::$user->level !== 0 )
		{
			Core::$router->load404('Nije dopušteno', 8, Conf::$SETTINGS['timed_refresh_interval']);
		}
	}
}