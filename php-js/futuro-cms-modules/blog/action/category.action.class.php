<?php




class BlogCategory
{


	public static function Run()
	{
		if( OUTPUT === 'html' )
		{
			$tplCategory = new libTempleate('category.tpl', 'blog');
			$CategoryList = FFCore::$Db->GetData('SELECT * FROM ?blog_category');
			
			foreach($CategoryList as &$Category ) 
			{
				$Category['title'] = libText::LoadOne('blog_category', $Category['categoryid'], 'title', 'hr');
			}
			
			$tplCategory->set('CategoryList', $CategoryList);
			
			usrAdmin::SetCentralContent($tplCategory);
		}
		else if( OUTPUT === 'ajax' )
		{
			if( isset($_GET['action']) )
			{
				switch($_GET['action'])
				{
					case 'save':

						if( isset($_POST['categoryid']) && is_numeric($_POST['categoryid']) && $_POST['categoryid'] )
						{
							$CategoryId = $_POST['categoryid'];
						}
						else
						{
							$CategoryId = false;
						}
						
						if( ! isset($_POST['title']) || trim($_POST['title']) == '' )
						{
							usrAjax::SetOutputData(array('error' => 3));
							return false;
						}

						usrAjax::SetOutputData(self::_Save($CategoryId, $_POST['title']));
						return false;


						break;
					
					
					case 'delete':
							
						if( ! isset($_POST['categoryid']) || ! is_numeric($_POST['categoryid']) || !$_POST['categoryid'] )
						{
							usrAjax::SetOutputData(array('error' => 3));
							return false;
						}

						usrAjax::SetOutputData(self::_Delete($_POST['categoryid']));
						return false;

						break;
							
					case 'load':
							
						if( ! isset($_POST['categoryid']) || ! is_numeric($_POST['categoryid']) || !$_POST['categoryid'] )
						{
							usrAjax::SetOutputData(array('error' => 3));
							return false;
						}

						usrAjax::SetOutputData(self::_Load($_POST['categoryid']));
						return false;

						break;
				}

			}
		}

	}

	private static function _Save($CategoryId, $Title)
	{
		if( ! $CategoryId )
		{
			if( ! FFCore::$Db->Query('INSERT INTO ?blog_category VALUES (default)') )
			{
				return array('error' => 2);
			}
			
			$CategoryId = FFCore::$Db->GetInsertId();
		}
		
		libText::ParseInput('blog_category', $CategoryId);

		return array('error' => false, 'categoryid' => $CategoryId);
	}


	private static function _Delete($CategoryId)
	{

		if( ! FFCore::$Db->Query('DELETE FROM ?blog_category WHERE categoryid = ' . $CategoryId . ' LIMIT 1') )
		{
			return array('error' => 5);
		}

		return array('error' => false);
	}


	private static function _Load($CategoryId)
	{
		if( $CategoryData = FFCore::$Db->GetOne('SELECT * FROM ?blog_category WHERE categoryid = ' . $CategoryId . ' LIMIT 1') )
		{
			$CategoryData['texts'] = libText::LoadAll('blog_category', $CategoryId);

			return array('error' => false, 'category' => $CategoryData);
		}
		else
		{
			return array('error' => 4);
		}
	}
}




BlogCategory::Run();














?>