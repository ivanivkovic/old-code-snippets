<?php

class modelUserData implements libBaseModel
{
	/**
	* @param int $id userid
	* @param array | string $data request user data
	* 
	* @return string | array requested user data
	*/
	
	public static $defaultPass = 'futuro';
	public static $userTypes = array(0, 1, 2);

	public static function exists($id)
	{
		if ( Core::$db->fetchOne( 'userid', 'user', array( 'userid' => $id ) ) )
		{
			return true;
		}
		
		return false;
	}
	
	public static function getData($id, $data)
	{
		return Core::$db->fetchOne($data, 'user', array('userid' => $id));
	}
	
	public static function getCompleteData( $id )
	{
		$data = array();
		
		$data = self::getData($id, '*');
		
		if( $data !== false && ! empty( $data ))
		{
			$data['level-title'] = libTemplate::txt( 'usertype-' . $data['level'] );
			$data['done-tasks'] = self::getCompleteTasks( $id );
			
			$fetch = Core::$db->fetchSQL('SELECT time FROM ?log WHERE actorid="' . $id . '" AND actiontype != "1"', true);
		}
		
		return $data;
	}
	
	public static function getCompleteTasks( $userId )
	{
		$data = Core::$db->fetchSQL('SELECT COUNT( ?task.taskid) AS count_tasks 
									FROM ?task
									JOIN ?task_assignment
									ON ?task.taskid = ?task_assignment.taskid
									WHERE ?task_assignment.userid="' . $userId . '" AND status="0"', true);
		
		return $data['count_tasks'];
	}
	
	
	/**
	
	Možda ne bude trebalo.
	
	public static function getDataByUserName( $username, $data)
	{
		$id = self::getIdByUserName( $username );
		
		if( $id !== false && ! empty( $id ) )
		{
			return self::getData($id, $data);
		}
		
		return array();
	}
	
	* 
	*/
	
	public static function getIdByUserName( $username )
	{
		return Core::$db->fetchOne('userid', 'user', array( 'username' => $username ) );
	}
	
	// Admin lista za dodavanje voditelja projektu.
	public static function getUsersForAutocomplete( $levels = array(0, 1, 2) )
	{
		$where = '';
		
		foreach($levels as $level)
		{
			if($where === ''){ $where = 'WHERE ';} else{ $where .= ' OR '; }
			
			$where .= ' level=' . $level;
		}
		
		
		if($where === ''){ $where = 'WHERE status=1';} else{ $where .= ' AND status=1'; }
		
		$data = Core::$db->fetchSQL( 'SELECT userid, name, lastname FROM ?user ' . $where . ' ORDER BY name DESC' );
		
		$admins = array();
		
		foreach( $data as $item )
		{
			$admins[$item['userid']] = $item['name'] . ' ' . $item['lastname'];
		}
		
		return $admins;
	}
	
	public static function isActive( $userid )
	{
		$result = Core::$db->fetchOne('userid', 'user', array('userid' => $userid, 'status' => '1'));
		
		if( $result && ! empty($result) )
		{
			return true;
		}
		
		return false;
	}
	
	public static function getUserList( $level = '' )
	{
		$levelExt = $level !== '' ? ' WHERE level="' . $level . '"' : '';
		
		$data = Core::$db->fetchSQL( 'SELECT * FROM ?user' . $levelExt . ' ORDER BY status DESC');
		
		return $data;
	}
	
	public static function getID($name)
	{
		$data = Core::$db->fetchSQL('SELECT userid FROM ?user WHERE CONCAT(name, " ", lastname) = "' . $name . '"', true);
		
		if( $data !== false && !empty($data))
		{
			return $data['userid'];
		}
		
		return false;
	}
	
	protected static function getSettings( $userid )
	{
		$data = Core::$db->fetch( array('settingid', 'value'), 'user_setting', array( 'userid' => $userid ) );
		
		if( $data !== false && ! empty($data))
		{
			return $data;
		}
		
		return array();
	}
	
	// Updateaj polje u user table-u.
	public static function updateField()
	{
		if(
			isset( $_POST['field'] ) &&
			is_string( $_POST['field'] ) &&
			isset( $_POST['value'] ) &&
			is_string( $_POST['field'] ) &&
			isset($_POST['userid']) &&
			is_numeric( $_POST['userid'] )
		)
		{
			if( Core::$user->id == $_POST['userid'] || Core::$user->level === 0 )
			{
				return self::setSetting( $_POST['field'], $_POST['value'], $_POST['userid'] );
			}
		}
		
		return false;
	}
	
	// Dohvati postavke.
	public static function getSetting( $setting, $userid )
	{
		$setting = Core::$db->fetchOne('value', 'user_setting', array('settingid' => $setting, 'userid' => $userid) );
		
		if( $setting )
		{
			return $setting;
		}
		
		return false;
	}
	
	// Postavi postavku i daj joj vrijednost.
	public static function setSetting( $setting, $value, $userid )
	{
		if( self::getSetting( $setting, $userid ) !== false )
		{
			if(	Core::$db->update( array( 'value' => $value ), 'user_setting', array( 'userid' => $userid, 'settingid' => $setting ) ) )
			{
				return true;
			}
		}
		else
		{
			if( Core::$db->insert( array( 'value' => $value, 'userid' => $userid, 'settingid' => $setting ), 'user_setting' ) )
			{
				return true;
			}
		}
		
		return false;
	}
	
	// Stvori korisnika.
	public static function createUser( $data )
	{
		if( Core::$user->level === 0 )
		{
			$data['pass'] = sha1( self::$defaultPass );
			$data['datejoined'] = libDateTime::Time();
			$data['status'] = 1;
			
			if( $data['role'] == 0 || $data['role'] == 1 || $data['role'] == 2 )
			{
				$userId = Core::$db->insert($data, 'user');
				
				if( is_numeric( $userId ) && $userId != 0 )
				{
					libSystemNews::addNews( 11, array( 'subjectid' => $userId, 'actiontype' => 1, 'subjecttype' => 'userdata')); return true;
				}
			}
		}
		
		return false;
	}
	
	// Update korisnika.
	public static function updateUser( $data )
	{
		if( Core::$user->level === 0 )
		{
			if( $data['role'] == 0 || $data['role'] == 1 || $data['role'] == 2 )
			{
				$userId = $data['userid'];
				
				if( Core::$db->update($data, 'user', array('userid' => $userId)) )
				{
					libSystemNews::addNews( 13, array( 'subjectid' => $userId, 'actiontype' => 0, 'subjecttype' => 'userdata'));
					return true;
				}
			}
		}
		
		return false;
	}
	
	// Dohvati update formu s korisničkim informacijama.
	public static function updateform( $param )
	{
		if( Core::$user->level === 0 )
		{
			$id = $param[1];
			$data = self::getData( $id, '*' );
			
			include(Conf::DIR_WIDGETS . 'ajaxforms/user-update.php');
		}
	}
}
