<?php

class Controller extends libControllerBase implements intControllerBase
{
	public function index( $param = false )
	{
		if( isset($_GET['file']) )
		{
			// Dohvaća ID fajla koji se traži zatim ga poslužuje.
			
			if( is_numeric( $_GET['file'] ) && $_GET['file'] !== false )
			{
				$data = libFile::getFileById( $_GET['file'] );
				
				if( libFile::exists( $data['filename'], $data['datafrom'], $data['interid'] ) )
				{
					libFile::serve( $data );
				}
			}
			else
			{
				/*
				* Tekstualni $_GET['file'] se koristi za generalno često korištene fajlove u folderu GENERAL.
				* Ex.   /download?file=access_details.txt
				*/
				libFile::serveGeneral( $_GET['file'] );
			}
		}
		
		die();
	}
}