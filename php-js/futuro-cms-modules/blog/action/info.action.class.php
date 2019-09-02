<?php 



class InfoObavijesti
{
		
	public static function Run()
	{
		
		$tplInfo = new libTempleate('info.tpl', 'blog');
		
		if( isset($_POST['info_save']) )
		{
			self::_Save();
			die();
		}
		
		$InfoList = FFCore::$Db->GetData('SELECT * FROM ?blog_info ORDER BY infoid ASC');
		$tplInfo->Set('InfoList', $InfoList);
		
		usrAdmin::SetCentralContent($tplInfo);
		
	}
	
	
	private static function _Save()
	{
		FFCore::$Db->Query('DELETE FROM ?blog_info WHERE 1');
		if( isset($_POST['info-infoid']) && is_array($_POST['info-infoid']) )
		{
			foreach( $_POST['info-infoid'] as $InfoKey => $InfoId )
			{

					$InfoInputArray['pubdate'] = libDateTime::Parse($_POST['info-date'][$InfoKey]);
					$InfoInputArray['text'] = trim($_POST['info-obavijest'][$InfoKey]);
					
					FFCore::$Db->InsertArray('?blog_info', $InfoInputArray);
			}
		}
		
		echo 'refresh';
	}
	
}


InfoObavijesti::Run();



?>