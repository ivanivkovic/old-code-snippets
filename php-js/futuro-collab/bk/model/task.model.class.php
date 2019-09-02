<?php

class modelTask implements libBaseModel
{
	public static $priority = array(
								0, # Low
								1, # Normal
								2  # Urgent
	);
	
	public static $orderType = array(
								0, # Phone/Fax
								1  # eMail
	);
	
	/**
	* return bool if fail, int taskID if success.
	*/
	public static function createTask( $data )
	{
		$data['publishtime'] = libDateTime::Time();
		$data['creatorid'] = Core::$user->id;
		$data['status'] = 1;
		$data['deadline'] = libTemplate::convertTimeString( $_POST['deadline'] );
		
		$taskId = Core::$db->insert($data, 'task');
		
		if( ! $taskId )
		{
			return false;
		}
		
		libSystemNews::addNews( 14, array( 'subjectid' => $taskId, 'actiontype' => 2, 'subjecttype' => 'task', 'additional' => $data['projectid'] ) );
		
		return $taskId;
	}
	
	public static function updateTask( $taskId, $data )
	{
		$data['creatorid'] = Core::$user->id;
		$data['deadline'] = libTemplate::convertTimeString( $_POST['deadline'] );
		
		$return = Core::$db->update($data, 'task', array('taskid' => $taskId));
		
		if( ! $return )
		{
			return false;
		}
		
		libSystemNews::addNews( 15, array( 'subjectid' => $taskId, 'actiontype' => 2, 'subjecttype' => 'task') );
		
		return true;
	}
	
	public static function getData( $id, $data )
	{
		$ndata = Core::$db->fetchOne($data, 'task', array('taskid' => $id));
		
		if( ! $ndata || empty($ndata) )
		{
			$ndata = array();
			
			foreach( $data as $elem )
			{
				$ndata[$elem] = '';
			}
		}
		
		return $ndata;
	}
	
	public static function getCompleteData( $taskId )
	{
		$query = 'SELECT ?task.*, ?project.type AS projectType FROM ?task JOIN ?project ON ?project.projectid = ?task.projectid WHERE taskid = "' .  $taskId . '"';
		$data = Core::$db->fetchSQL($query, true);
		
		$data['assigned'] = self::getAssignedWorkers( $data['taskid'] );
		$data['order_code'] = self::generateOrderCode( $data['taskid'], $data['publishtime'], $data['projectType'] );
		$data['files'] = libFile::getFiles( 'task', $taskId );
		$data['client'] = modelProject::getProjectClient( $data['projectid'] );
		
		return $data;
	}

	public static function exists($id)
	{
		if ( Core::$db->fetchOne('taskid', 'task', array('taskid' => $id)) )
		{
			return true;
		}
		
		return false;
	}

	public static function getActiveTasks( $projectid )
	{
		if( $data = Core::$db->fetch( array( 'COUNT(taskid)' => 'active_tasks'), 'task', array('projectid' => $projectid, 'status' => '1')))
		{
			return $data[0]['active_tasks'];
		}
		
		return false;
	}
	
	public static function getNumPages()
	{
		$limit = $_POST['limit'];
		
		$query = 'SELECT COUNT( ?task.taskid ) AS numresults, CEIL( COUNT( ?task.taskid ) / ' . $limit . ' ) AS numpages FROM ?task ';
		
		$where = false;
		
		// JOIN
		if( isset( $_POST['filters'] ) && ! empty( $_POST['filters'] ) )
		{
			if( isset( $_POST['filters']['clientid'] ) )
			{
				$query .= 'JOIN ?project ON ?task.projectid = ?project.projectid ';
			}
			
			if( isset( $_POST['filters']['userid'] ) )
			{
				$query .= 'JOIN ?task_assignment ON ?task.taskid = ?task_assignment.taskid ';
			}
			
			// WHERE
			if( isset( $_POST['filters']['clientid'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= '?project.clientid="' . $_POST['filters']['clientid'] . '"';
				$where = true;
			}
			
			if( isset( $_POST['filters']['userid'] ) && modelUserData::exists( $_POST['filters']['userid'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= ' ?task_assignment.userid="' . $_POST['filters']['userid'] . '" ';
				$where = true;
			}
			
			if( isset( $_POST['filters']['projectid'] ) && modelProject::exists( $_POST['filters']['projectid'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= '?task.projectid="' . $_POST['filters']['projectid'] . '"';
				$where = true;
			}
			
			if( isset( $_POST['filters']['priority'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= '?task.priority="' . $_POST['filters']['priority'] . '"';
				$where = true;
			}
			
			if( isset( $_POST['filters']['status'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= '?task.status="' . $_POST['filters']['status'] . '"';
				$where = true;
			}
			
			// Van roka
			if( isset( $_POST['filters']['expired'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= '?task.deadline < "' . libDateTime::Time() . '"';
				$where = true;
			}
		}
		
		$result = Core::$db->fetchSQL($query, true);
		
		return array( 'numpages' => $result['numpages'], 'numresults' => $result['numresults'] );
	}

	public static function getPage()
	{
		$limit = $_POST['limit'];
		$page = $_POST['page'];
		
		$offset = ( $limit * $page );
		
		$query = 'SELECT
					 ?task.*,
					 ?project.type AS projectType
				FROM ?task
				JOIN ?project ON ?task.projectid = ?project.projectid';
		
		$where = false;
		
		if( isset( $_POST['filters'] ) && ! empty( $_POST['filters'] ) )
		{
			if( isset( $_POST['filters']['userid'] ) )
			{
				$query .= ' JOIN ?task_assignment ON ?task.taskid = ?task_assignment.taskid ';
			}
			
			// WHERE
			if( isset( $_POST['filters']['clientid'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= '?project.clientid="' . $_POST['filters']['clientid'] . '"';
				$where = true;
			}
			
			if( isset( $_POST['filters']['userid'] ) && modelUserData::exists( $_POST['filters']['userid'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= ' ?task_assignment.userid="' . $_POST['filters']['userid'] . '" ';
				$where = true;
			}
			
			if( isset( $_POST['filters']['projectid'] ) && modelProject::exists( $_POST['filters']['projectid'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= '?task.projectid="' . $_POST['filters']['projectid'] . '"';
				$where = true;
			}
			
			if( isset( $_POST['filters']['priority'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= '?task.priority="' . $_POST['filters']['priority'] . '"';
				$where = true;
			}
			
			if( isset( $_POST['filters']['status'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= '?task.status="' . $_POST['filters']['status'] . '"';
				$where = true;
			}
			
			// Van roka
			if( isset( $_POST['filters']['expired'] ) )
			{
				$query .= $where === true ? ' AND ' : ' WHERE ';
				$query .= '?task.deadline < "' . libDateTime::Time() . '"';
				$where = true;
			}
		}
	
		$query .= ' ORDER BY publishtime DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
	
		$data = Core::$db->fetchSQL($query);
	
		foreach( $data as $key => $item )
		{
			$items[$key] = $item;
			$items[$key]['assigned'] = self::getAssignedWorkers( $item['taskid'] );
			$items[$key]['order_code'] = self::generateOrderCode( $item['taskid'], $item['publishtime'], $item['projectType'] );
			$items[$key]['client'] = modelProject::getProjectClient( $item['projectid'] );
			$items[$key]['reciever'] = modelUserData::getData( $item['recieverid'], array( 'name', 'lastname', 'userid') );
			
			if( empty( $items[$key]['reciever'] ) || $items[$key]['reciever'] === false )
			{
				$items[$key]['reciever']['name'] = '';
				$items[$key]['reciever']['lastname'] = '';
				$items[$key]['reciever']['userid'] = '';
			}
		}
		
		include( Conf::DIR_INCLUDES . 'pagination/task-list.php' );
	}
	
	public static function assignWorker( $taskId, $workerId )
	{
		$workers = self::getAssignedWorkers($taskId);
		// Ako je već ubačen kao assigned, nemoj ništa napraviti.
		foreach( $workers as $assignedWorker)
		{
			if( $workerId === $assignedWorker['userid'] )
			{
				return false;
			}
		}
		
		return Core::$db->insert(array('taskid' => $taskId, 'userid' => $workerId, 'adminid' => Core::$user->id, 'time' => libDateTime::Time()), 'task_assignment');
	}
	
	public static function dismissWorker( $taskId, $workerId )
	{
		return Core::$db->delete( array('taskid' => $taskId, 'userid' => $workerId), 'task_assignment' );
	}
	
	public static function getAssignedWorkers( $taskId )
	{
		$query = 'SELECT ?user.userid, ?user.name, ?user.lastname, ?user.username FROM ?task_assignment JOIN ?user ON ?task_assignment.userid = ?user.userid WHERE ?task_assignment.taskid="' . $taskId . '"';
		$assigned = Core::$db->fetchSQL( $query );
		
		return $assigned;
	}
	
	public static function generateOrderCode( $taskId, $publishTime, $projectType)
	{
		return modelProject::$types[$projectType] . ' ' . ( 1000 + $taskId ) . '-' . date('y', $publishTime);
	}

	public static function getCreateForm()
	{
		libTemplate::set( 'autocompleteList', modelUserData::getAdminsList() );
		libTemplate::loadTemplateFile( Conf::DIR_WIDGETS . 'ajaxforms/task-new.php' );
	}
	
	public static function isOwner( $taskId, $userId )
	{
		if( Core::$db->fetchOne( array('taskid'), 'task', array('creatorid' => $userId, 'taskid' => $taskId) ) )
		{
			return true;
		}
		
		return false;
	}
	
	public static function delete( $data )
	{
		$taskId = $data[1];
		
		if( Core::$user->level === 0 || self::isOwner( $taskId, Core::$user->id ) )
		{
			$subTasks = self::getSubTasks($taskId);
			$taskData = self::getData($taskId, array('description', 'projectid'));
			
			foreach($subTasks as $subTask)
			{
				if ( ! self::deleteSubTask( $subTask['taskid'] ) )
				{
					return false;
				}
			}
			
			if( Core::$db->delete(array('taskid' => $taskId), 'task') )
			{
				libSystemNews::addNews( 16, array( 'subjectid' => $taskId, 'actiontype' => 2, 'subjecttype' => 'task', 'additional' => $taskData['description'], 'additional2' => $taskData['projectid']) );
				return true;
			}
		}
		
		return false;
	}

	public static function getSubTasks( $taskId )
	{
		return Core::$db->fetch('*', 'task', array('parent' => $taskId));
	}

	public static function deleteSubTask( $taskId )
	{
		return Core::$db->delete( array('taskid' => $taskId), 'task' );
	}

	public static function updateForm( $data )
	{
		$taskId = $data[1];
		$taskData = self::getCompleteData( $taskId );
		$assigned = $taskData['assigned'];
		
		$autocompleteValues = array();
		foreach( $taskData['assigned'] as $user )
		{
			$autocompleteValues[] = $user['name'] . ' ' . $user['lastname'];
		}
		
		$autocompleteCurrentValue = '';
		foreach( $assigned as $item )
		{
			$autocompleteCurrentValue .= $item['name'] . ' ' . $item['lastname'] . ',';
		}
		
		libTemplate::set(
			array(
				'taskData' => $taskData,
				'autocompleteList' => modelUserData::getAdminsList(),
				'autocompleteValues' => $autocompleteValues,
				'autocompleteCurrentValue' => $autocompleteCurrentValue
			)
		);
		
		libTemplate::loadTemplateFile( Conf::DIR_WIDGETS . 'ajaxforms/task-update.php' );
	}
}