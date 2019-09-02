<?php

class HeaderLoad{
	
	public static function getHeaderImageId()
	{
		$result = FFCore::$Db->GetOne('SELECT image_id FROM ?header');
		
		if($result)
		{
			return $result['image_id'];
		}
		
		return false;
	}
	
	public static function getHeaderImage()
	{
		
	}
}