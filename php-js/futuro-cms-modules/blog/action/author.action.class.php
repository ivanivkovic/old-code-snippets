<?php




class BlogAuthor
{

	public static function Run()
	{


		if( OUTPUT === 'html' )
		{
			$tplAuthor = new libTempleate('author.tpl', 'blog');
			
			$AuthorList = FFCore::$Db->GetData('SELECT * FROM ?blog_author ORDER BY name ASC');
			
			$tplAuthor->Set('AuthorList', $AuthorList);
			usrAdmin::SetCentralContent($tplAuthor);
		}
		else if( OUTPUT === 'ajax' )
		{
			if( isset($_GET['action']) )
			{
				switch($_GET['action'])
				{
					case 'save':
					
						if( isset($_POST['authorid']) && is_numeric($_POST['authorid']) && $_POST['authorid'] )
						{
							$AuthorId = $_POST['authorid'];
						}
						else
						{
							$AuthorId = false;
						}
						
						$AuthorPhone = isset($_POST['phone']) && $_POST['phone'] ? $_POST['phone'] : '';
						$AuthorEmail = isset($_POST['email']) && $_POST['email'] && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : '';
						$AuthorName = $_POST['name'];
						
						if( ! isset($_POST['name']) || trim($_POST['name']) == '' )
						{
							usrAjax::SetOutputData(array('error' => 3));
							return false;
						}
						
						usrAjax::SetOutputData(self::_Save($AuthorId, $AuthorName, $AuthorPhone, $AuthorEmail));
						return false;

					break;
						
					case 'delete':
					
						if( ! isset($_POST['authorid']) || ! is_numeric($_POST['authorid']) || !$_POST['authorid'] )
						{
							usrAjax::SetOutputData(array('error' => 3));
							return false;
						}

						usrAjax::SetOutputData(self::_Delete($_POST['authorid']));
						return false;

					break;
					
					case 'load':
							
						if( ! isset($_POST['authorid']) || ! is_numeric($_POST['authorid']) || !$_POST['authorid'] )
						{
							usrAjax::SetOutputData(array('error' => 3));
							return false;
						}

						usrAjax::SetOutputData(self::_Load($_POST['authorid']));
						return false;

					break;
				}
			}
		}
	}

	private static function _Save($AuthorId, $AuthorName, $AuthorPhone, $AuthorEmail)
	{
		function saveImages($AuthorId)
		{
			if(	isset($_POST['ImageId'])
				&& isset($_POST['ImageIncarnation'])
				&& isset($_POST['ImageCaption'])
				&& isset($_POST['ImageWaterMark'])
			)
			{
				if($_POST['ImageId'] != 0)
				{
					$ImageArray = array(
									'datafrom' => 'blog',
									'interid' => $AuthorId,
									'denotation' => 'author_image',
									'imageid' => $_POST['ImageId'],
									'incarnationid' => $_POST['ImageIncarnation'],
									'watermark' => $_POST['ImageWaterMark']
								);
					if( ! FFCore::$Db->GetOne('SELECT interid FROM ?pic_inline_link WHERE interid="' . $AuthorId . '" AND denotation="author_image"') )
					{
						FFCore::$Db->InsertArray('?pic_inline_link', $ImageArray);
						unset($ImageArray['denotation']);
						
						FFCore::$Db->InsertArray('?pic_image_link', $ImageArray);
						$UpdateIncarnation = FFCore::$Db->GetInsertId();
						
					}
					else
					{
						FFCore::$Db->UpdateByArray('?pic_inline_link', $ImageArray, 'interid="' . $AuthorId . '" AND denotation="author_image"');
					}
				}
			}
		}
		
		$AuthorArray['name'] = $AuthorName;
		$AuthorArray['email'] = $AuthorEmail;
		$AuthorArray['phone'] = $AuthorPhone;
		
		if( $AuthorId )
		{
			if( ! FFCore::$Db->UpdateByArray('?blog_author', $AuthorArray, 'authorid = ' . $AuthorId) )
			{
				libLoad::Lib('ImageLink', 'images');
				libImageLink::Save('author_image', $AuthorId);
				
				return array('error' => 2);
			}
			else
			{
				saveImages($AuthorId);
			}
		}
		else
		{
			if( ! FFCore::$Db->InsertArray('?blog_author', $AuthorArray) )
			{
				return array('error' => 2);
			}
			else
			{
				$AuthorId = FFCore::$Db->GetInsertId();
				saveImages($AuthorId);
			}
		}
		
		libText::ParseInput('blog_author', $AuthorId);
		
		return array('error' => false, 'authorid' => $AuthorId);
	}

	private static function _Delete($AuthorId)
	{
		if( ! FFCore::$Db->Query('DELETE FROM ?blog_author WHERE authorid = ' . $AuthorId . ' LIMIT 1') )
		{
			libLoad::Lib('ImageLink', 'images');
			libImageLink::Delete('author_image', $AuthorId);
			
			libText::Delete('blog_author', $AuthorId);
			
			return array('error' => 5);
		}

		return array('error' => false);
	}

	private static function _Load($AuthorId)
	{
		if( $AuthorData = FFCore::$Db->GetOne('SELECT * FROM ?blog_author WHERE authorid = ' . $AuthorId . ' LIMIT 1') )
		{
			return array('error' => false, 'author' => $AuthorData, 'texts' => libText::LoadAll('blog_author', $AuthorId));
		}
		else
		{
			return array('error' => 4);
		}
	}
}


BlogAuthor::Run();



?>