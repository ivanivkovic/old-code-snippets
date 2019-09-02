<?php

class HeaderEdit{
	
	public static function Run()
	{
		if( OUTPUT === 'html' )
		{
			if(!empty($_POST)){
				
				libLoad::Lib('ImageLink', 'images');
				libImageLink::Save('header', 1);
				
				echo libJSON::FromArray(array('error' => 0, 'url' => 'refresh')); die();
				
			}
			
			$tpl = new libTempleate('edit.tpl', 'header');
			
			usrAdmin::SetCentralContent($tpl);
		}
	}
	
	private static function _update($id)
	{
		if(FFCore::$Db->Query('UPDATE ?header SET image_id="' . $id . '"'))
		{
			return true;
		}
		
		return false;
	}
	
	private static function _insert($id)
	{
		if(FFCore::$Db->InsertArray('?header', array('image_id' => $id)))
		{
			return true;
		}
		
		return false;
	}
	
}

HeaderEdit::Run();