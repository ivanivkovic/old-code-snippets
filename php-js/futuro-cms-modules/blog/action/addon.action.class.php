<?php 


class LoadBlogAddon
{

	private static $_DataFrom = '';
	private static $_InterId = false;
	
	public static function Run()
	{
		
		if( isset($_POST['datafrom']) && trim($_POST['datafrom']) != ''  )
		{
			self::$_DataFrom = FFCore::$Db->EscapeString($_POST['datafrom']);
		}
		else if( isset($_GET['datafrom']) && trim($_GET['datafrom']) != '' )
		{
			self::$_DataFrom = FFCore::$Db->EscapeString($_GET['datafrom']);
		}
		
		if( isset($_POST['interid']) && is_numeric($_POST['interid']) && $_POST['interid'] )
		{
			self::$_InterId = $_POST['interid'];
		}
		else if( isset($_GET['interid']) && is_numeric($_GET['interid']) && $_GET['interid'] )
		{
			self::$_InterId = $_GET['interid'];
		}
		
		
		$SelectedCategory = FFCore::$Db->GetOne('SELECT * FROM ?blog_link 
												 WHERE ?blog_link.datafrom = \'' . self::$_DataFrom . '\' 
												 	AND ?blog_link.interid = ' . self::$_InterId . ' LIMIT 1');
		
		$CategoryList = FFCore::$Db->GetData('SELECT * FROM ?blog_category ORDER BY title ASC');
		
		$tplCategoryAddon = new libTempleate('addon.tpl', 'blog');
		
		$tplCategoryAddon->Set('SelectedCategory', $SelectedCategory);
		$tplCategoryAddon->Set('CategoryList', $CategoryList);
		
		usrAdmin::SetCentralContent($tplCategoryAddon);
	}
}

LoadBlogAddon::Run();

?>