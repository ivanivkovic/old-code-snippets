<?php

/*
* Status docs:
* 0 => active
* 1 => inactive/archive/undone
* 2 => inactive/done
*/

class modelProject implements libBaseModel
{
	public static $types = array(
							0 => 'WEB',
							1 => 'APP',
							2 => 'CMS',
							3 => 'CMS-MOD'
	);
	
	// Lista projekta.
	public static function projectList( $clientid = '' )
	{
		$data = array();
		
		$clientCond = $clientid !== '' && is_numeric($clientid) ? ' WHERE clientid="' . $clientid . '" ' : '';
		
		if( $result = Core::$db->fetchSQL('SELECT
										?project.projectid,
										title,
										name,
										lastname,
										?project.userid
									FROM ?project
									JOIN ?user
										ON ?user.userid = ?project.userid ' . $clientCond
									))
		{
			$data = $result;
		}
		
		foreach( $data as &$item )
		{
			$query = Core::$db->fetch( array( 'COUNT(taskid)' => 'active_tasks'), 'task', array('projectid' => $item['projectid'], 'status' => '0'));
			$item['active_tasks'] = $query[0]['active_tasks'];
		}
		
		return $data;
	}
	
	// Samo aktivni projekti.
	public static function getActiveProjects()
	{
		$data = array();
		
		if( $result = Core::$db->fetch(array('*'), 'project', array('status' => 0)) )
		{
			$data = $result;
		}
		
		return $data;
	}
	
	// Dohvati informacije o klijentu od projekta.
	public static function getProjectClient( $projectId )
	{
		$return = array('clientid' => '', 'name' => '', 'info' => '', 'phone' => '', 'email' => '', 'address' => '', 'contract' => '');
		
		$query = 'SELECT ?client.* FROM ?project JOIN ?client ON ?project.clientid = ?client.clientid WHERE ?project.projectid="' . $projectId . '" ';
		$data = Core::$db->fetchSQL($query, true);
		
		if( ! empty( $data ) && $data !== false )
		{
			$return = $data;
		}
		
		return $return;
	}
	
	// Detaljni podaci projekta.
	public static function getProjectData($id)
	{
		$data = array();
		
		$query = 'SELECT
						?project.*,
						?user.name,
						?user.lastname
					FROM ?project
					JOIN ?user ON ?project.userid = ?user.userid
					WHERE projectid="' . $id . '"'
		;
		
		if( $data = Core::$db->fetchSQL( $query , true ) )
		{
			$data['time-tag'] = date('Y-m-j', $data['time']);
			$data['time-string'] = date('j.m.Y H:i', $data['time']);
			$data['files'] = libFile::getFiles( 'project', $data['projectid'] );
			$data['managers'] = self::getManagers( $data['projectid'] );
			$data['client'] = self::getProjectClient( $data['projectid'] );
			
			switch( $data['status'] )
			{
				case '0':
					$data['active'] = 'Aktivan';
				break;
				
				case '1':
					$data['active'] = 'Arhiva, nedovršeno';
				break;
				
				case '2':
					$data['active'] = 'Arhiva';
				break;
			};
		}
		
		return $data;
	}
	
	// Postavljanje statusa projekta.
	public static function setStatus( $status, $where )
	{
		$projectid = $where['projectid'];
		
		if( self::isProjectManager( $projectid, Core::$user->id ) || Core::$user->level === 0 )
		{
			libSystemNews::addNews( 7, array( 'subjectid' => $projectid, 'actiontype' => 0, 'subjecttype' => 'project', 'additional' => $status, 'additional2' => self::getData( $projectid, 'title' ) ) );
			
			return Core::$db->update( array('status' => $status), 'project', $where );
		}
		
		return false;
	}

	// Izbriši projekt.
	public static function delete( $projectid )
	{
		if( self::isProjectManager( $projectid, Core::$user->id ) || Core::$user->level === 0 )
		{
			libSystemNews::addNews( 4, array( 'subjectid' => $projectid, 'actiontype' => 0, 'subjecttype' => 'project', 'additional' => self::getData( $projectid, 'title' ) ) );
			
			Core::$db->delete( array('projectid' => $projectid), 'project_assignment' );
			Core::$db->delete( array('projectid' => $projectid), 'project' );
			Core::$db->delete( array('projectid' => $projectid), 'discussion_post' );
			
			libFile::deleteAll( 'project', $projectid );
			libFile::deleteDir( 'project', $projectid );
			
			// modelTask::delete( array('projectid' => $projectid) );
			
			// self::generateAccessDetailsFile();
			
			return true;
		}
		
		return false;
	}
	
	// Dohvati voditelje projekta.
	public static function getManagers( $projectid )
	{
		if( $data = Core::$db->fetchSQL( 'SELECT ?user.userid, ?user.name, ?user.lastname, ?user.username FROM ?project_manager JOIN ?user ON ?project_manager.userid = ?user.userid WHERE projectid="' . $projectid . '"' ))
		{
			return $data;
		}
		
		return array();
	}
	
	// Forma za pristupne podatke.
	/*
	public static function getAccessForm()
	{
		include('app/widgets/ajaxforms/access-details.php');
		return true;
	}
	*/
	
	public static function getData($id, $data)
	{
		return Core::$db->fetchOne($data, 'project', array( 'projectid' => $id ) );
	}
	
	public static function exists($id)
	{
		if ( Core::$db->fetchOne('projectid', 'project', array('projectid' => $id)) )
		{
			return true;
		}
		
		return false;
	}
	
	// Ajax mijenjaj status.
	public static function changeStatus( $data )
	{
		$id = $data[1];
		$order = $data[2];
		
		switch( $order )
		{
			default: case 'archivedone':
				return self::setStatus(2, array('projectid' => $id));
			break;
			
			
			case 'archiveundone':
				return self::setStatus(1, array('projectid' => $id));
			break;
			
			
			case 'activate':
				return self::setStatus(0, array('projectid' => $id));
			break;
			
			
			case 'delete':
				return self::delete($id);
			break;
		}
		
		return false;
	}
	
	// Search pistupne podatke po keywordu.
	/*
	public static function getAccessDetails($data)
	{
		$string = $data[1];
		
		if( strlen($string) < Conf::$INI['db_min_match_letters'] )
		{
			$query = 'SELECT projectid, title, accessdetails FROM pos_project WHERE title = "' . $string . '" LIMIT 2';
			
			$data = Core::$db->fetchSQL($query);
			
			if( empty( $data ) )
			{
				$query = 'SELECT projectid, title, accessdetails FROM pos_project WHERE title = "' . $string . '" OR title LIKE "%' . $string . '%" LIMIT 2';
				
				$data = Core::$db->fetchSQL($query);
			}
		}
		else
		{
			$query = 'SELECT projectid, title, accessdetails, MATCH (title) AGAINST ("' . $string . '") AS rel FROM pos_project WHERE MATCH (title) AGAINST ("' . $string . '") ORDER BY rel DESC LIMIT 2';
			$data = Core::$db->fetchSQL($query);
		}
		
		if( ! empty($data) )
		{
			$data = $data[0];
			include('app/includes/access-details-show.php');
		}
		else
		{
			echo 'Nema rezultata';
		}
		
		return true;
	}
	
	*/
	
	// Stvori projekt.
	public static function addProject($data)
	{
		if( Core::$user->level !== 2 )
		{
			$data['time'] = libDateTime::Time();
			$data['status'] = '0';
			$data['userid'] = Core::$user->id;
			
			if( $lastid = Core::$db->insert($data, 'project'))
			{
				libSystemNews::addNews( 6, array( 'subjectid' => $lastid, 'actiontype' => 0, 'subjecttype' => 'project', 'additional' => self::getData($lastid, 'title') ));
				// self::generateAccessDetailsFile();
				return $lastid;
			}
		}
		
		return false;
	}
	
	// Update projekta.
	public static function updateProject($data, $where)
	{
		if( Core::$user->level === 0 || ( self::isProjectManager( $data['projectid'], Core::$user->id ) && Core::$user->level === 1) )
		{
			$projectid = $data['projectid'];
			
			unset($data['projectid']);
			
			if( Core::$db->update($data, 'project', $where))
			{
				libSystemNews::addNews( 8, array( 
											'subjectid' => $projectid,
											'actiontype' => 0,
											'subjecttype' => 'project',
											'additional' => self::getData( $projectid, 'title')
										)
				);
				
				# self::generateAccessDetailsFile();
				
				return true;
			}
		}
		
		return false;
	}
	
	public static function isProjectManager($projectid, $userid)
	{
		if( Core::$user->level !== 2 )
		{
			if( Core::$db->fetchOne('projectid', 'project_manager', array( 'projectid' => $projectid, 'userid' => $userid ) ) )
			{
				return true;	
			}
		}
		
		return false;
	}
	
	public static function addManager($projectid, $userid)
	{
		if( ( Core::$user->level === 0 || self::isProjectManager($projectid, Core::$user->id) ) && ! self::isProjectManager($projectid, $userid) )
		{
			if( Core::$db->insert(array('userid' => $userid, 'projectid' => $projectid), 'project_manager') !== false )
			{
				libSystemNews::addNews( 9, array( 'subjectid' => $projectid, 'actiontype' => 2, 'subjecttype' => 'project', 'additional' => self::getData($projectid, 'title'), 'additional2' => $userid ));
				return true;
			}
		}
		
		return false;
	}

	public static function removeManager($projectid, $userid)
	{
		if( ( Core::$user->level === 0 || self::isProjectManager($projectid, Core::$user->id) ) && self::isProjectManager($projectid, $userid) )
		{
			if( Core::$db->delete(array('userid' => $userid, 'projectid' => $projectid), 'project_manager') )
			{
				libSystemNews::addNews( 10, array( 
									'subjectid' => $projectid, 
									'actiontype' => 2, 
									'subjecttype' => 'project', 
									'additional' => self::getData($projectid, 'title'), 
									'additional2' => $userid 
				));
				
				return true;
			}
		}
		
		return false;
	}
	
	// Učitaj formu za update.
	public static function updateForm($param)
	{
		$projectId = $param[1];
		
		$autocompleteList = modelUserData::getAdminsList();
		$clientList = modelClient::getClientsList();
		$data = self::getData( $projectId, '*' );
		$managers = self::getManagers( $projectId );
		$files = libFile::getFiles( 'project', $projectId );
		
		$autocompleteValues = array();
		foreach( $managers as $key => $item)
		{
			$autocompleteValues[$key] = $item['name'] . ' ' . $item['lastname'];
		}
		
		$autocompleteCurrentValue = '';
		foreach( $managers as $item )
		{
			$autocompleteCurrentValue .= $item['name'] . ' ' . $item['lastname'] . ',';
		}
		
		include('app/widgets/ajaxforms/project-update.php');
		
		return true;
	}
	
	// Paginator
	public static function getNumPages( $param )
	{
		$limit = $_POST['limit'];
		$keyword = $_POST['keyword'];
		$status = $_POST['filters']['filter'];
		
		$sql = 'SELECT CEIL( COUNT(projectid) / ' . $limit . ' ) AS numpages, COUNT(projectid) AS numresults FROM ?project';
		
		if( $keyword === '0' )
		{
			$sql .= ' WHERE status="' . $status . '"';
		}
		else
		{
			if( strlen($keyword) < Conf::$INI['db_min_match_letters'] )
			{
				$sql .= ' WHERE title LIKE "%' . $keyword . '%" AND status="' . $status . '"';
			}
			else
			{
				$sql .= ' WHERE MATCH(title) AGAINST ("' . $keyword . '") AND status="' . $status . '"';
			}
		}
		
		$data = Core::$db->fetchSQL($sql, true);
		
		return array( 'numpages' => $data['numpages'], 'numresults' => $data['numresults'] );
	}
	
	public static function getPage( $param )
	{
		$limit = $_POST['limit'];
		$keyword = $_POST['keyword'];
		$page = $_POST['page'];
		$status = $_POST['filters']['filter'];
		
		$offset = $limit * $page;
		
		if( $keyword === '0' )
		{
			$query = 'SELECT
						?project.projectid,
						?project.title,
						?project.clientid,
						name,
						lastname,
						?project.userid
					FROM ?project
					JOIN ?user
						ON ?user.userid = ?project.userid
						WHERE ?project.status="' . $status . '" 
					ORDER BY time DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
		}
		else
		{
			if( strlen($keyword) < Conf::$INI['db_min_match_letters'] )
			{
				$query = 'SELECT
						?project.projectid,
						?project.title,
						?project.clientid,
						name,
						lastname,
						?project.userid
					FROM ?project
					JOIN ?user
						ON ?user.userid = ?project.userid
					WHERE title LIKE "%' . $keyword . '%" 
					AND ?project.status="' . $status . '" 
					ORDER BY time DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
			}
			else
			{
				$query = 'SELECT
						?project.projectid,
						?project.title,
						?project.clientid,
						name,
						lastname,
						?project.userid,
						MATCH(title) AGAINST ("' . $keyword . '") AS score
					FROM ?project
					JOIN ?user
						ON ?user.userid = ?project.userid
					WHERE MATCH(title) AGAINST ("' . $keyword . '")
					AND ?project.status="' . $status . '" 
					ORDER BY time DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
			}
		}
		
		$data = Core::$db->fetchSQL($query);
		
		foreach( $data as &$pitem )
		{
			$pitem['active_tasks'] 	= modelTask::getActiveTasks( $pitem['projectid'] );
			$pitem['managers'] 		= self::getManagers( $pitem['projectid'] );
			$pitem['client'] 		= modelClient::getData( $pitem['clientid'], array('name', 'clientid') );
			
			if( ! isset($pitem['client']['name']) )
			{
				$pitem['client']['name'] = '';
			}
		}
		
		include('app/includes/pagination/project-list.php');
	}
	
	// Generiraj .txt fajl za pristupne podatke.
	/*
	public static function generateAccessDetailsFile()
	{
		$data = Core::$db->fetch( array('accessdetails', 'title', 'projectid'), 'project', '', '', array('time' => 'ASC'));
		
		$text = '';
		
		foreach( $data as $item )
		{
			if( $item['accessdetails'] !== '' )
			{
				$text .= strtoupper( 'Projekt : ' . $item['title'] . ' #' . $item['projectid']);
				$text .= "\n\n";
				$text .= $item['accessdetails'];
				$text .= "\n\n--------------------------------------------------------------\n\n";
			}
		}
		
		libFile::writeToFile('general', 'access-details.txt', $text);
	}
	*/
}
