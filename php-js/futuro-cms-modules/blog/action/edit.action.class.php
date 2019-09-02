<?php 





class EditBlog
{
	
	private static $_BlogId = false;
	
	public static function Run()
	{
		
		if( OUTPUT === 'html' )
		{
		
			if( isset($_GET['blogid']) && is_numeric($_GET['blogid']) )
			{
				self::$_BlogId = $_GET['blogid'];
			}
			
			if( isset($_POST['blog_save']) )
			{
				self::_Save();
			}
			
			$tplEditBlog = new libTempleate('edit_blog.tpl', 'blog');
			
			if( ! self::$_BlogId )
			{
				$Data['blogid'] = 0;
				$ChangeCategory = true;
				$Data['publishtime'] = libDateTime::Time();
				$Data['unpublishtime'] = 0;
				$Data['frontpage'] = 0;
			}
			else
			{
				$Data = FFCore::$Db->GetOne('SELECT * FROM ?blog WHERE blogid = ' . self::$_BlogId . ' LIMIT 1');
				$Data['text'] = libText::LoadAll('blog', self::$_BlogId);
				$Data['preview'] = FFConf::GetUrl() . 'blog/pregled-0/' . libString::ToLabel($Data['text']['hr']['title']) . '-' . self::$_BlogId . '.html';
				
				if( $Data['categoryid'] != 0 )
				{
					$BlogCategoryAdmiLabel = 'blog_category_admin_' . $Data['categoryid'];
					$ChangeCategory = FFCore::$Access->Has($BlogCategoryAdmiLabel);
				}
				else
				{
					$ChangeCategory = true;
				}				
			}
	
			$CategoryList = FFCore::$Db->GetData('SELECT * FROM ?blog_category');
			
			foreach( $CategoryList as &$Category )
			{
				$Category['title'] = libText::LoadOne('blog_category', $Category['categoryid'], 'title', 'hr');
				
				$CategoryAccessLabel = 'blog_category_' . $Category['categoryid'];
				if( FFCore::$Access->Has($CategoryAccessLabel) )
				{
					$Category['can_edit'] = 1;
				}
				else
				{
					$Category['can_edit'] = 0;
				} 
				
				$CategoryAdminAccessLabel = 'blog_category_admin_' . $Category['categoryid'];
				if( FFCore::$Access->Has($CategoryAdminAccessLabel) )
				{
					$Category['can_publish'] = 1;
				}
				else
				{
					$Category['can_publish'] = 0;
				}
			}
			
			$AuthorList = FFCore::$Db->GetData('SELECT * FROM ?blog_author ORDER BY name ASC');
			
			$tplEditBlog->Set('AuthorList', $AuthorList);
			$tplEditBlog->Set('CategoryList', $CategoryList);
			
			$tplEditBlog->Set('Data', $Data);
			$tplEditBlog->Set('CanChangeCategory', $ChangeCategory);
			
			usrAdmin::SetCentralContent($tplEditBlog);
			
		}
		else if( OUTPUT === 'ajax' )
		{
			if( isset($_GET['action']) )
			{
				
				switch($_GET['action'])
				{
					case 'lock':
					
						if( isset($_POST['blogid']) && is_numeric($_POST['blogid']) )
						{
							usrAjax::SetOutputData(self::_Lock($_POST['blogid']));
						}
						else
						{
							usrAjax::SetOutputData(array('error' => 3));
							return false;
						}
						
					break;
					
					
					case 'unlock':
					
						if( isset($_POST['blogid']) && is_numeric($_POST['blogid']) )
						{
							usrAjax::SetOutputData(self::_UnLock($_POST['blogid']));
						}
						else
						{
							usrAjax::SetOutputData(array('error' => 3));
							return false;
						}
						
					break;
					
					case 'publish':
					
						if( isset($_POST['blogid']) && is_numeric($_POST['blogid']) )
						{
							usrAjax::SetOutputData(self::Publish($_POST['blogid']));
						}
						else
						{
							usrAjax::SetOutputData(array('error' => 3));
							return false;
						}
						
					break;
					
					case 'unpublish':
					
						if( isset($_POST['blogid']) && is_numeric($_POST['blogid']) )
						{
							usrAjax::SetOutputData(self::UnPublish($_POST['blogid']));
						}
						else
						{
							usrAjax::SetOutputData(array('error' => 3));
							return false;
						}
						
					break;
					
					
					case 'delete':
					
						if( isset($_POST['blogid']) && is_numeric($_POST['blogid']) )
						{
							libLoad::Lib('comments', 'comments');
							
							LibComments::DeleteFromItem('blog', $_POST['blogid']);
							usrAjax::SetOutputData(self::_Delete($_POST['blogid']));
						}
						else
						{
							usrAjax::SetOutputData(array('error' => 3));
							return false;
						}
						
					break;
				}
				
			}
		}
		
	}
	
	
	
	
	private static function _Save()
	{
		if( self::$_BlogId !== false )
		{
			$BlogData = FFCore::$Db->GetOne('SELECT * FROM ?blog WHERE blogid = ' . self::$_BlogId . ' LIMIT 1');	
			if( $BlogData['locked'] )
			{
				$LabelToEdit = 'blog_category_admin_' . $BlogData['categoryid'];
				if( isset($_POST['blog_category']) && 
					is_numeric($_POST['blog_category']) && 
					$BlogData['categoryid'] != $_POST['blog_category'] && 
					FFCore::$Access->Has($LabelToEdit) )
				{
					$LabelToEdit = 'blog_category_admin_' . $_POST['blog_category'];
				}
			}
			else
			{
				$LabelToEdit = 'blog_category_' . $BlogData['categoryid'];
				if( isset($_POST['blog_category']) && 
					is_numeric($_POST['blog_category']) && 
					$BlogData['categoryid'] != $_POST['blog_category'] && 
					FFCore::$Access->Has($LabelToEdit) )
				{
					$LabelToEdit = 'blog_category_' . $_POST['blog_category'];
				}
			}
		}
		else
		{
			if( isset($_POST['blog_category']) && is_numeric($_POST['blog_category']) )
			{
				$LabelToEdit = 'blog_category_' . $_POST['blog_category'];
			}
			else
			{
				echo libJSON::FromArray(array('error' => 3, 'url' => '#'));
				die();
			}
		}
		
		$BlogCoreData['last_change'] = libDateTime::Time();
		
		if( $_POST['blog_save'] == 'unpublish' )
		{
			$BlogCoreData['published'] = 0;
		}
		else if( $_POST['blog_save'] == 'publish' )
		{
			$BlogCoreData['published'] = 1;
		}
		
		if( isset($_POST['blog_category']) && is_numeric($_POST['blog_category']) )
		{
			$BlogCoreData['categoryid'] = $_POST['blog_category'];
		}
		
		if( isset($_POST['blog_author']) && is_numeric($_POST['blog_author']) )
		{
			$BlogCoreData['authorid'] = $_POST['blog_author'];
		}
		else
		{
			echo libJSON::FromArray(array('error' => 3, 'url' => '#')); die();
		}
		
		if( FFCore::$Access->Has('frontpage_blog') )
		{
			$BlogCoreData['frontpage'] = 0;
			if( isset($_POST['blog_frontpage']) && is_numeric($_POST['blog_frontpage']) )
			{
				if( $_POST['blog_frontpage'] == 2  )
				{
					$BlogCoreData['frontpage'] = 2;
					if( isset($_POST['blog_frontpage_stick']) )
					{
						$BlogCoreData['frontpage'] = 3;
					}
				}
				else if( $_POST['blog_frontpage'] == 1 )
				{
					$BlogCoreData['frontpage'] = 1;
				}
			}
			else
			{
				if( isset($BlogData['frontpage']) && $BlogData['frontpage'] == 2 && FFCore::$Access->Has('frontpage_blog') )
				{
					$BlogCoreData['frontpage'] = 0;
				}
				else if( !isset($BlogData['frontpage']) || $BlogData['frontpage'] == 1 )
				{
					$BlogCoreData['frontpage'] = 0;
				}
			}
		}
		
		if( isset($_POST['time_start']) && isset($_POST['date_start']) && trim($_POST['time_start']) != '' && trim($_POST['date_start']) != '' )
		{
			$BlogCoreData['publishtime'] = libDateTime::Parse($_POST['date_start'], $_POST['time_start']) ;
		}
		else
		{
			$BlogCoreData['publishtime'] = 0;
		}
		
		if( isset($_POST['time_end']) && isset($_POST['date_end']) && trim($_POST['time_end']) != '' && trim($_POST['date_end']) != '' ) 
		{
			$BlogCoreData['unpublishtime'] = libDateTime::Parse($_POST['date_end'], $_POST['time_end']);
		}
		else
		{
			$BlogCoreData['unpublishtime'] = 0;
		}
		
		if( FFCore::$Access->Has($LabelToEdit) )
		{
			$BlogCoreData['last_change'] = libDateTime::Time();
			
			libLoad::Lib('KeywordLink', 'keywords');
			
			if( self::$_BlogId !== false )
			{
				FFCore::$Db->UpdateByArray('?blog', $BlogCoreData, 'blogid = ' . self::$_BlogId );
				$Redirect = false;
			}
			else
			{
				FFCore::$Db->InsertArray('?blog', $BlogCoreData);
				self::$_BlogId = FFCore::$Db->GetInsertId();
				$Redirect = true;
			}
			
			foreach(FFConf::$ExistingLang as $LangKey => $LangName)
			{
				libKeywordLink::Save('blog_' . $LangKey, self::$_BlogId);
			}
			
			libText::ParseInput('blog', self::$_BlogId);
			
			// slike
			libLoad::Lib('ImageLink', 'images');
			libImageLink::Save('blog', self::$_BlogId);
			
			// dokumente
			libLoad::Lib('DocLink', 'repository');
			libDocLink::Save('blog', self::$_BlogId);
			
			// poveznice
			libLoad::Lib('WeblinksSave', 'weblinks');
			libWeblinksSave::Save('blog', self::$_BlogId);
			
			// ključne riječi
			libLoad::Lib('KeywordLink', 'keywords');
			libKeywordLink::Save('blog', self::$_BlogId);
			
			if( $Redirect )
			{
				echo libJSON::FromArray( array('error' => false, 'url' => '#!/blog/edit/blogid=' . self::$_BlogId) );
				die();
			}
			else
			{
				echo libJSON::FromArray( array('error' => false, 'url' => 'refresh') );
			}
			die();
		}
		else
		{
			echo libJSON::FromArray(array('error' => 1, 'url' => '#'));
			die();
		}
	}
	
	
	
	private static function _Lock($BlogId)
	{
		$BlogData = FFCore::$Db->GetOne('SELECT * FROM ?blog WHERE blogid = ' . $BlogId . ' LIMIT 1');

		$CategoryAdminAccessLabel = 'blog_category_admin_' . $BlogData['categoryid'];
		if( FFCore::$Access->Has($CategoryAdminAccessLabel) )
		{
			$LockArray['locked'] = true;
			if( FFCore::$Db->UpdateByArray('?blog', $LockArray, 'blogid = ' . $BlogId) )
			{
				return array('error' => false );
			}
			else
			{
				return array('error' => 2);
			}
		}
		else
		{
			return array('error' => 1);
		}
	}
	
	
	private static function _UnLock($BlogId)
	{
		$BlogData = FFCore::$Db->GetOne('SELECT * FROM ?blogi WHERE blogid = ' . $BlogId . ' LIMIT 1');

		$CategoryAdminAccessLabel = 'blog_category_admin_' . $BlogData['categoryid'];
		if( FFCore::$Access->Has($CategoryAdminAccessLabel) )
		{
			$LockArray['locked'] = false;
			if( FFCore::$Db->UpdateByArray('?blog', $LockArray, 'blogid = ' . $BlogId) )
			{
				return array('error' => false );
			}
			else
			{
				return array('error' => 2);
			}
		}
		else
		{
			return array('error' => 1);
		}
	}
	
	
	private static function _Delete($BlogId)
	{
		if( ! $BlogData = FFCore::$Db->GetOne('SELECT * FROM ?blog WHERE blogid = ' . $BlogId . ' LIMIT 1 ') )
		{
			return array('error' => 4);
		}
		
		if( ! FFCore::$Access->Has('blog_category_admin_' . $BlogData['categoryid']) )
		{
			return array('error' => 3);
		}
		
		if( ! FFCore::$Db->Query('DELETE FROM ?blog WHERE blogid = ' . $BlogId . ' LIMIT 1') )
		{
			return array('error' => 5);
		}
		else
		{
			return array('error' => false);
		}
	}
	
	public static function Publish($BlogId)
	{
		if( ! $BlogData = FFCore::$Db->GetOne('SELECT * FROM ?blog WHERE blogid = ' . $BlogId . ' LIMIT 1 ') )
		{
			return array('error' => 4);
		}
		
		if( ! FFCore::$Access->Has('blog_category_admin_' . $BlogData['categoryid']) )
		{
			return array('error' => 3);
		}
		
		$BlogArray['publishtime'] = libDateTime::Time();
		$BlogArray['published'] = true;
		
		if( FFCore::$Db->UpdateByArray('?blog', $BlogArray, 'blogid = ' . $BlogId) )
		{
			return array('error' => false);
		}
		else
		{
			return array('error' => 2);
		}
	}
	
	public static function UnPublish($BlogId)
	{
		if( ! $BlogData = FFCore::$Db->GetOne('SELECT * FROM ?blog WHERE blogid = ' . $BlogId . ' LIMIT 1 ') )
		{
			return array('error' => 4);
		}
		
		if( ! FFCore::$Access->Has('blog_category_admin_' . $BlogData['categoryid']) )
		{
			return array('error' => 3);
		}
		
		$BlogArray['published'] = false;
		
		if( FFCore::$Db->UpdateByArray('?blog', $BlogArray, 'blogid = ' . $BlogId) )
		{
			return array('error' => false);
		}
		else
		{
			return array('error' => 2);
		}
	}
	
}



EditBlog::Run();






?>