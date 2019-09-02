<?php

class modelClient implements libBaseModel
{
	public static function getClientsList()
	{
		$data = array();
		
		if( $result = Core::$db->fetch('*', 'client') )
		{
			$data = $result;
		}
		
		return $data;
	}
	
	public static function insert($data)
	{
		if( $lastid = Core::$db->insert($data, 'client') )
		{
			$clientData = modelClient::getData( $lastid, array('name') );
			
			libSystemNews::addNews( 2, array( 'subjectid' => $lastid, 'actiontype' => 0, 'subjecttype' => 'client', 'additional' => $clientData['name'] ) );
			return true;
		}
		
		return false;
	}
	
	public static function update($data)
	{
		if( Core::$db->update($data, 'client', array('clientid' => $data['clientid'])) )
		{
			$clientData = modelClient::getData( $data['clientid'], array('name') );
			
			libSystemNews::addNews( 5, array( 'subjectid' => $data['clientid'], 'actiontype' => 0, 'subjecttype' => 'client', 'additional' => $clientData['name'] ) );
			return true;
		}
		
		return false;
	}
	
	public static function delete($data)
	{
		$clientData = self::getData( $data[1], array('name') );
		$data['name'] = $clientData['name'];
		
		if( Core::$db->delete( array('clientid' => $data[1] ) , 'client' ) )
		{
			libSystemNews::addNews( 3, array( 'subjectid' => $data[1], 'actiontype' => 0, 'additional' => $data['name'], 'subjecttype' => 'client' ) );
			
			if( isset($data[2]))
			{
				switch($data[2])
				{
					default: case 'keep':	$status = 0; 	break;
					case 'archiveundone':	$status = 1; 	break;
					case 'archivedone':		$status = 2; 	break;
				}
				
				if( $status !== 0 ):  modelProject::setStatus( $status, array('clientid' => $data[1]) );  endif;
			}
			
			return true;
		}
		
		return false;
	}
	
	public static function getData($id, $data)
	{
		return Core::$db->fetchOne($data, 'client', array( 'clientid' => $id ) );
	}
	
	public static function exists($id)
	{
		if ( Core::$db->fetchOne('clientid', 'client', array('clientid' => $id)) )
		{
			return true;
		}
		
		return false;
	}
	
	public static function hasProjects($id)
	{
		if ( Core::$db->fetchOne('projectid', 'project', array('userid' => $id)) )
		{
			return true;
		}
		
		return false;
	}
	
	public static function updateForm($data)
	{
		$data = self::getData($data[1], '*');
		
		include('app/widgets/ajaxforms/client-update.php');
		
		return true;
	}
	
	public static function getCompleteData($clientid)
	{
		$query = 'SELECT 
						?client.*,
						"clientdata" AS resulttype,
						(SELECT COUNT( ?project.projectid) as projectcount FROM ?project WHERE ?project.clientid="' . $clientid . '") AS project_count
						
					FROM ?client
					WHERE ?client.clientid="' . $clientid . '"';
		
		$query = str_replace("\t", ' ', $query);
		$query = preg_replace('/ +\?(?=[a-z]+)/i', ' ' . 'pos_', $query);
		$query = str_replace('\?', '?', $query);
		
		$clientData = Core::$db->fetchSQL($query, true);
		
		if( empty($clientData) )
		{
			return false;
		}
		
		$clientData['projects'] = modelProject::projectList( $clientData['clientid'] );
		
		return $clientData;
	}
	
	// Paginator.
	public static function getNumPages( $param )
	{
		$limit = $_POST['limit'];
		$keyword = $_POST['keyword'];
		
		$sql = 'SELECT COUNT( clientid ) AS numresults, CEIL( COUNT( clientid ) / ' . $limit . ' ) AS numpages FROM ?client';
		
		if( !$keyword === '0' )
		{
			if( strlen($keyword) < Conf::$INI['db_min_match_letters'] )
			{
				$sql .= ' WHERE name LIKE "%' . $keyword . '%"';
			}
			else
			{
				$sql .= ' WHERE name AGAINST ("' . $keyword . '")';
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
		
		$offset = $limit * $page;
		
		if( $keyword === '0' )
		{
			$query = 'SELECT
						*
					FROM ?client
					ORDER BY clientid DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
		}
		else
		{
			if( strlen($keyword) < Conf::$INI['db_min_match_letters'] )
			{
				$query = 'SELECT
						*
					FROM ?client
					WHERE name LIKE "%' . $keyword . '%" 
					ORDER BY clientid DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
			}
			else
			{
				$query = 'SELECT
							*
						MATCH( name ) AGAINST ("' . $keyword . '") AS score
					FROM ?client
					WHERE MATCH( name ) AGAINST ("' . $keyword . '")
					ORDER BY clientid DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
			}
		}
		
		$clientList = Core::$db->fetchSQL($query);
		
		foreach( $clientList as &$client )
		{
			$client['hasprojects'] = modelClient::hasProjects( $client['clientid'] );
		}
		
		include('app/includes/pagination/client-list.php');
	}
}