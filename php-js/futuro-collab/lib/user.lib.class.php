<?php

class libUser extends modelUserData
{
	public $id;
	public $data = array();
	public $level;
	public $setting = array();

	// Vraća je li korisnik ulogiran.
	public function loggedIn()
	{
		if( libSession::get('userid') !== false )
		{
			if( ! modelUserData::isActive( libSession::get('userid') ) )
			{
				self::logout();
				Core::$router->redirect('/logout');
			}
			
			return true;
		}
		else
		{
			return false;
		}
	}

	// Validira username i pass.
	public function validateLogin($username, $pass)
	{
		if( $userid = Core::$db->fetchOne('userid', 'user', array('username' => $username, 'pass' => sha1($pass), 'status' => '1') ) )
		{
			return $userid;
		}
		
		return false;
	}
	
	// Stvara session i objavljuje login.
	public function login($userid)
	{
		libSession::set('userid', $userid);
		Core::$db->update( array('lastlogin' => libDateTime::Time()), 'user', array('userid' => $userid)  );
		
		libSystemNews::addNews( 0, array( 'actorid' => $userid, 'subjectid' => $userid, 'actiontype' => 1, 'subjecttype' => '', 'additional' => '' ));
	}
	
	// Učitava podatke u objekt i formira ih.
	public function createData()
	{
		$data = self::getData( libSession::get('userid'), '*' );
		
		$this->id = $data['userid'];
		$this->data = $data;
		
		$this->_createSettings();
		
		if( isset( Core::$user->setting['lang'] ) )
		{
			libTemplate::setLanguage( Core::$user->setting['lang'] );
		}
		
		$this->_formatData();
	}
	
	private function _createSettings()
	{
		$data = self::getSettings( $this->id );
		
		if( ! empty($data) )
		{
			foreach( $data as $setting)
			{
				$this->setting[$setting['settingid']] = $setting['value'];
			}
		}
	}
	
	private function _formatData()
	{
		$this->data['fullname'] = $this->data['name'] . ' ' . $this->data['lastname'];
		$this->level = intval( $this->data['level'] );
		$this->title = libTemplate::txt( 'usertype-' . $this->level );
	}
	
	public function logout()
	{
		libSystemNews::addNews( 1, array( 'subjectid' => libSession::get('userid'), 'actiontype' => 1, 'subjecttype' => '', 'additional' => '' ) );
		libSession::destroy();
	}
	
	public static function getSettingsForm()
	{
		libTemplate::loadTemplateFile(Conf::DIR_WIDGETS . 'ajaxforms/user-settings.php');
	}
	
	// Provjerava šifru za ulogiranog korisnika. (potrebno za mijenjanje šifre)
	public static function checkMyPassword()
	{
		$password = sha1( $_POST['password'] );
		
		if( Core::$db->fetchOne('userid', 'user', array('pass' => $password, 'userid' => Core::$user->id)) )
		{
			return true;
		}
		else
		{
			return false;
		}
		
		return false;
	}
	
	// Mijenja šifru.
	public static function updateMyPass()
	{
		$oldPassword = sha1( $_POST['oldpass'] );
		$newPassword = sha1( $_POST['newpass'] );
		
		if( Core::$db->fetchOne('userid', 'user', array('pass' => $oldPassword)) )
		{
			if( Core::$db->update( array( 'pass' => $newPassword ), 'user', array( 'userid' => Core::$user->id )) )
			{
				return true;
			}
		}
		
		return false;
	}
}