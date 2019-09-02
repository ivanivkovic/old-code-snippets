<?php

class Controller extends libControllerBase implements intControllerBase
{
	public function index($param = false)
	{
		if( ! empty ( $_POST ) )
		{
			if( isset($_POST['action']) && $_POST['action'] === 'insert')
			{
				if(
					libForm::validatePostForm(
						array('assigned', 'description', 'recieverid', 'projectid', 'ordertype', 'deadline'),
						array('description', 'recieverid', 'projectid', 'ordertype', 'deadline')
					)
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
			}
			
			if( isset($_POST['action']) && $_POST['action'] === 'update')
			{
				if(
					libForm::validatePostForm( 
						array('taskid', 'assigned', 'description', 'recieverid', 'projectid', 'ordertype', 'deadline'),
						array('description', 'recieverid', 'projectid', 'ordertype', 'deadline')
					)
					&& is_numeric($_POST['taskid']))
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
			}
			
			if( isset( $taskId ) )
			{
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
			
			if( isset($_POST['action']) && $_POST['action'] === 'update-status')
			{
				if( 
					libForm::validatePostForm( array('taskid', 'status'), array('timefinished')	)
				)
				{
					$taskId = $_POST['taskid'];
					
					unset($_POST['action']);
					unset($_POST['taskid']);
					
					modelTask::updateStatus($taskId, $_POST);
				}
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
								,'taskData' => $taskData,
								'leftMenu' => array(
									array(
										'title' => 'Informacije o zadatku',
										'tab' => 'info',
										'active' => true
									),
									array(
										'title' => 'Podzadaci',
										'tab' => 'subtasks'
									),
									array(
										'title' => libTemplate::txt('back'),
										'href' => Core::$router->back
									)
								)
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