<?php




class libBlogiLink
{

	public static function Save($DataFrom, $InterId = false)
	{
		// prvo čistimo stare veze:
		self::_ClanUp($DataFrom, $InterId);

		if( isset($_POST['blog']['categoryid']) && is_numeric($_POST['blog']['categoryid']) && $_POST['blog']['categoryid'])
		{
			$BlogAddonArray['datafrom'] = $DataFrom;
			$BlogAddonArray['interid'] = $InterId;
			$BlogAddonArray['categoryid'] = $_POST['blog']['categoryid'];
			FFCore::$Db->InsertArray('?blog_link', $BlogAddonArray);	
		}
	}
	
	
	public static function Delete($DataFrom, $InterId = false )
	{
		self::_ClanUp($DataFrom, $InterId);
	}


	// metoda za brisanje slika vezanih na quant
	private static function _ClanUp($DataFrom, $InterId = false)
	{
		if( $InterId !== false )
		{
			FFCore::$Db->Query('DELETE FROM ?blog_link WHERE datafrom = \''.$DataFrom.'\' AND interid = ' . $InterId);
		}
	}

}



?>