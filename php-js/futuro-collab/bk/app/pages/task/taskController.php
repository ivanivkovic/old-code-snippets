<?php

class Controller extends libControllerBase implements intControllerBase
{
	public function index($param = false)
	{
		if( ! empty ( $_POST ) )
		{
			if(
				libForm::validatePostForm(
					array('action', 'assigned', 'description', 'recieverid', 'projectid', 'ordertype', 'deadline'),
					array('description', 'recieverid', 'projectid', 'ordertype', 'deadline')
				) 
				&& $_POST['action'] === 'insert'
			)
			{
				$assignedList = explode( ',', $_POST['assigned'] );
				
				unset($_POST['action']);
				unset($_POST['assigned']);
				
				$taskId = modelTask::createTask( $_POST );
				
				if( $taskId !== false || ! is_numeric($taskId) )
				{
					libTemplate::addSuccess(9);
				}
				else
				{
					libTemplate::addError(1);
				}
			}
			
			if( 
				libForm::validatePostForm( 
					array('taskid', 'action', 'assigned', 'description', 'recieverid', 'projectid', 'ordertype', 'deadline'),
					array('description', 'recieverid', 'projectid', 'ordertype', 'deadline')
			    )
				&& $_POST['action'] === 'update' && is_numeric($_POST['taskid']))
			{
				$taskId = $_POST['taskid'];
				$assignedList = explode( ',', $_POST['assigned'] );
				
				unset( $_POST['action'] );
				unset( $_POST['assigned'] );
				unset( $_POST['taskId'] );
				
				$update = modelTask::updateTask( $taskId, $_POST );
				
				if( $update )
				{
					libTemplate::addSuccess(11);
					
					// Briši voditelje projekta.
					$assigned = modelTask::getAssignedWorkers($taskId);
					
					if( ! empty($assigned) && ! empty($assignedList)  )
					{
						foreach( $assigned as $user )
						{
							if( ! in_array( $user['name'] . ' ' . $user['lastname'], $assignedList ) )
							{
								libTemplate::addSuccess(12);
								modelTask::dismissWorker( $taskId, $user['userid'] );
							}
						}
					}
					else
					{
						foreach( $assigned as $user )
						{
							libTemplate::addSuccess(12);
							modelTask::dismissWorker( $taskId, $user['userid'] );
						}
					}
				}
			}
			
			if( isset($assignedList) && ! empty($assignedList) && is_array($assignedList) )
			{
				// Dodaj assignane radnike.
				foreach($assignedList as $name)
				{
					if( $name !== '')
					{
						$userId = modelUserData::getID($name);
						
						if( is_numeric($userId) && modelUserData::isActive( $userId ) )
						{
							if( modelTask::assignWorker($taskId, $userId) !== false )
							{
								libTemplate::addSuccess(13);
							}
						}
					}
				}
			}
			
			/* ! FILES ! */
			
			// Unos datoteka AKO je insert/update bio uspješan.
			if( Core::$user->level === 0 || modelTask::isOwner($taskId, Core::$user->id) )
			{
				libFile::uploadFilesByPost('task', $taskId);
			}
			
			// Brisanje postojećih fajlova ako je određeno.
			if( Core::$user->level === 0 || modelTask::isOwner($taskId, Core::$user->id) )
			{
				libFile::deleteFilesByPost();
			}
		}
		
		if( $param === false )
		{
			libTemplate::set(
							array(
								'title' => 'Zadaci',
								'userList' => modelUserData::getUserList(),
								'projectList' => modelProject::projectList(),
								'clientList' => modelClient::getClientsList()
							)
			);
			
			libTemplate::loadPage('list');
		}
		else
		{
			if( is_numeric( $param ) && modelTask::exists( $param ))
			{
				$taskId = $param;
				$taskData = modelTask::getCompleteData($taskId);
				
				libTemplate::set(
							array(
								'title' => libString::limitString( $taskData['description'], 30 )
								,'taskData' => $taskData
								#,'userList' => modelUserData::getUserList()
								#,'projectList' => modelProject::projectList()
								#, 'clientList' => modelClient::getClientsList()
							)
				);
				
				libTemplate::loadPage('single');
			}
			else
			{
				Core::$router->load404('Zadatak nije pronađen', 9, Conf::$SETTINGS['timed_refresh_interval']);
			}
		}
	}
}