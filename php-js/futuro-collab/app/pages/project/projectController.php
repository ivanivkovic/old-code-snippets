<?php

class Controller extends libControllerBase implements intControllerBase
{
	public function index($param = false, $params = array())
	{
		if( ! empty( $_POST ) )
		{
			// Insert projekta.
			if(
				libForm::validatePostForm( array('title', 'client', 'type', 'domain', 'description', 'usernames'), array('title', 'description', 'client', 'type') )
				&& isset( $_POST['action'] ) && $_POST['action'] == 'insert'
			)
			{
				$data['title'] = $_POST['title'];
				$data['clientid'] = $_POST['client'];
				$data['info'] = $_POST['description'];
				$data['domain'] = $_POST['domain'];
				$data['type'] = $_POST['type'];
				
				$projectId = modelProject::addProject($data);
				libTemplate::addSuccess(3);
				
				// Po defaultu osnivač postaje voditelj projekta.
				modelProject::addManager($projectId, Core::$user->id);
			}
			
			if(	libForm::validatePostForm( array( 'projectid', 'type', 'domain', 'title', 'client', 'description', 'usernames'), array('title', 'type', 'description', 'client') ) &&
				is_numeric($_POST['projectid']) && 
				isset( $_POST['action'] ) && $_POST['action'] == 'update'
			)
			{
				// Update projekta.
				
				$data['title'] = $_POST['title'];
				$data['clientid'] = $_POST['client'];
				$data['info'] = $_POST['description'];
				$data['projectid'] = $_POST['projectid'];
				$data['domain'] = $_POST['domain'];
				$data['type'] = $_POST['type'];
				
				$projectId = $data['projectid'];
				
				if( modelProject::updateProject($data, array('projectid' => $projectId)) )
				{
					libTemplate::addSuccess(4);
				}
				
				// Iz autocomplete input-a dodijeli voditelje za projekt.
				$leaders = explode(', ', $_POST['usernames'] );
				
				foreach($leaders as $leaderName)
				{
					if( $leaderName !== '')
					{
						$leaderId = modelUserData::getID($leaderName);
						
						if( is_numeric($leaderId) && modelUserData::isActive( $leaderId ) )
						{
							if( modelProject::addManager( $projectId, $leaderId ) )
							{
								libTemplate::addSuccess(5);
							}
						}
					}
				}
				
				// Briši voditelje projekta.
				$existingManagers = modelProject::getManagers($projectId);
				
				if( ! empty($existingManagers) )
				{
					foreach( $existingManagers as $manager )
					{
						if( ! in_array( $manager['name'] . ' ' . $manager['lastname'], $leaders ) )
						{
							libTemplate::addSuccess(14);
							modelProject::removeManager( $projectId, $manager['userid'] );
						}
					}
				}
			}
			
			// Unos datoteka AKO je insert/update bio uspješan.
			if( isset($projectId) && ! empty( $_FILES ) )
			{
				if( Core::$user->level === 0 || modelProject::isProjectManager($projectId, Core::$user->id) )
				{
					libFile::uploadFilesByPost('project', $projectId);
				}
			}
			
			// Brisanje postojećih fajlova ako je određeno.
			if( isset( $_POST['filesdelete'] ) )
			{
				if( Core::$user->level === 0 || modelProject::isProjectManager($projectId, Core::$user->id) )
				{
					libFile::deleteFilesByPost();
				}
			}
		}
		
		if( isset ( $projectId) )
		{
			// Iz autocomplete input-a dodijeli voditelje za projekt.
			$leaders = explode(',', $_POST['usernames'] );
			
			foreach($leaders as $leaderName)
			{
				if( $leaderName !== '')
				{
					$leaderId = modelUserData::getID($leaderName);
					
					if( is_numeric($leaderId) && modelUserData::isActive( $leaderId ) )
					{
						modelProject::addManager($projectId, $leaderId);
					}
				}
			}
		}
		
		/* End insert/update */
		
		
		
		
		
		
		// Ako je param numeričan i predstavlja projectId, učitaj stranicu tog projekta. Ako ne, onda izlistaj projekta.
		if( $param )
		{
			if( is_numeric( $param ) )
			{
				if( modelProject::exists($param) )
				{
					// URL param je projectId.
					$projectData = modelProject::getProjectData( $param );
					
					libTemplate::set(
									array(
										'projectData' => $projectData,
										'title' => $projectData['title'],
										'leftMenu' => array(
											array(
												'title' => libTemplate::txt('back'),
												'href' => Core::$router->back
											),
											'<div class="border-bottom"></div>',
											array(
												'title' => 'Informacije o projektu',
												'tab' => 'info',
												'active' => true
											),
											array(
												'title' => 'Zadaci',
												'href' => '/task#projectid=' . $param
											)
											/*,
											4 =>
											array(
												'title' => 'Povijest',
												'tab' => 'history'
											)
											*/
										)
									)
								);
				}
				else
				{
					$this->_load404();
				}
				
				libTemplate::loadPage('single');
			}
			else
			{
				$this->_load404();
			}
		}
		else
		{
			libTemplate::set(
							array(
								'title' => 'Projekti',
								'autocompleteList' => modelUserData::getUsersForAutocomplete(array(0,1)),
								'clientList' => modelClient::getClientsList(),
								'leftMenu' => array(
									array(
										'title' => libTemplate::txt('back'),
										'href' => Core::$router->back
									),
									'<div class="border-bottom"></div>',
									array(
										'title' => 'Aktivni Projekti',
										'tab' => '0',
										'active' => true,
										'class' => 'filter'
									),
									array(
										'title' => 'Dovršeni Projekti',
										'tab' => '2',
										'class' => 'filter'
									),
									array(
										'title' => 'Nedovršeni Projekti',
										'tab' => '1',
										'class' => 'filter'
									)
								)
							)
			);
			
			// Samo admini imaju pristup pristupnim podacima za projekte.
			if( Core::$user->level !== 2 )
			{
				libTemplate::$pageVars['leftMenu'][] = '<div class="border-bottom"></div>';
				// libTemplate::$pageVars['leftMenu'][] = array('title' => 'Preuzmi Pristupne Podatke', 'href' => '/download/?file=access-details.txt');
			}
			
			libTemplate::loadPage('list');
		}
	}
	
	private function _load404()
	{
		Core::$router->load404('Project Not Found', 7, Conf::$SETTINGS['timed_refresh_interval']);
	}
}