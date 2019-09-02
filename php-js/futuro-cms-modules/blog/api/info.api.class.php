<?php 


class apiInfo
{
	public static function GetList()
	{
		$InfoList = FFCore::$Db->GetData('SELECT * FROM ?blog_info WHERE pubdate < ' . libDateTime::Time(). ' ORDER BY pubdate DESC');
		return $InfoList;
	}
}


?>