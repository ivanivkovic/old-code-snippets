<?php

class Controller extends libControllerBase implements intControllerBase
{
	public function index($id = false, $param = array())
	{
		if( ! empty( $_POST ) )
		{
			// Insert.
			if(
				libForm::validatePostForm(
					array('address', 'name', 'phone', 'contract', 'email', 'info'),
					array('contract', 'name', 'info')
				)
				&& isset( $_POST['action']) && $_POST['action'] === 'insert'
			)
			{
				// Blokiraj izmijenjivanje ičega za obične korisnike.
				if( Core::$user->level === 2 )
				{
					libTemplate::addError(0);
				}
				else
				{
					unset( $_POST['action'] );
					
					if( modelClient::insert( $_POST ) )
					{
						libTemplate::addSuccess(1);
					}
					else
					{
						libTemplate::addError(1);
					}
				}
			}
			
			// Update
			if(
				libForm::validatePostForm( array('address', 'contract', 'clientid', 'name', 'phone', 'email', 'info'), array('contract', 'name', 'info') ) &&
				isset( $_POST['action']) && $_POST['action'] === 'update'
			)
			{
				// Blokiraj izmijenjivanje ičega za obične korisnike.
				if( Core::$user->level === 2 )
				{
					libTemplate::addError(0);
				}
				else
				{
					unset( $_POST['action'] );
					
					if( modelClient::update($_POST) )
					{
						libTemplate::addSuccess(2);
					}
					else
					{
						libTemplate::addError(2);
					}
				}
			}
		}
		
		if($id)
		{
			// Očitaj clientid iz urla
			if( is_numeric($id) && modelClient::exists($id) )
			{
				$c = modelClient::getData( $id, array('name'));
				$c = $c['name'];
				
				libTemplate::set(
								array(
									'clientData' => modelClient::getCompleteData($id),
									'title' => 'Klijent ' . $c
								)
				);
				
				// Client stranica.
				libTemplate::loadPage('single');
			}
			else
			{
				$this->_load404();
			}
		}
		else
		{
			// List stranica.
			// Insert / Update forme.
			libTemplate::set(
							array(
								'title' => 'Klijenti'
							)
			);
			
			libTemplate::loadPage('list');
		}
	}
	
	private function _load404()
	{
		Core::$router->load404('Client Not Found', 7, Conf::$SETTINGS['timed_refresh_interval']);
	}
}