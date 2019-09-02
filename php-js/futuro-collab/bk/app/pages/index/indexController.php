<?php

class Controller extends libControllerBase implements intControllerBase
{
	public function index($param = false)
	{
		if( ! empty( $_POST ) )
		{
			// Insert newsfeed objave.
			if(
				libForm::validatePostForm( array('announcement', 'link'), array( 'announcement' ))
			)
			{
				$link = isset( $_POST['link'] ) ? $_POST['link'] : '';
				
				if ( 
					libSystemNews::addNews( 11, 
						array(
							'subjectid' => 0, 
							'actiontype' => 3, 
							'subjecttype' => '', 
							'additional' => $_POST['announcement'], 
							'additional2' => str_replace( 'http://' . $_SERVER['SERVER_NAME'], '', $link ) 
						)
					)
				)
				{
					libTemplate::addSuccess(10);
				}
				else
				{
					libTemplate::addError(1);
				}
			}
		}
		
		// Lijevi meni.
		$menu = array();
		
		$menu[0]['title'] = 'Sve novosti';
		$menu[0]['tab'] = '';
		$menu[0]['active'] = true;
		$menu[0]['class'] = 'filter';
		
		foreach( libSystemNews::$actionTypes as $key => $value )
		{
			$menu[] = array(
				'title' => $value,
				'tab' => $key,
				'class' => 'filter'
			);
		}
		
		// Init.
		libTemplate::set(
						array(
							'title' => 'Novosti',
							'leftMenu' => $menu,
							'cPanel' => true
						)
		);

		libTemplate::loadPage('home');
	}
}