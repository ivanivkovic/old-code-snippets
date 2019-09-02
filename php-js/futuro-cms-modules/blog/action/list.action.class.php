<?php 

class BlogList
{

	public static function Run()
	{
		
		if( OUTPUT === 'html' )
		{
			$tplBlogList = new libTempleate('list.tpl', 'blog');
			
			$CategoryList = FFCore::$Db->GetData('SELECT * FROM ?blog_category');
			
			foreach($CategoryList as &$Category ) 
			{
				$Category['title'] = libText::LoadOne('blog_category', $Category['categoryid'], 'title', 'hr');
			}
			
			$AuthorList = FFCore::$Db->GetData('SELECT * FROM ?blog_author ORDER BY name ASC');
			
			$tplBlogList->Set('CategoryList', $CategoryList);
			$tplBlogList->Set('AuthorList', $AuthorList);
			
			usrAdmin::SetCentralContent($tplBlogList);
		}
		else if( OUTPUT === 'ajax' )
		{
			if( isset($_POST['orderby']) && in_array($_POST['orderby'], array('blogid', 'publishtime')) )
			{
				$OrderBy = $_POST['orderby'];
			}
			else
			{
				$OrderBy = 'blogid';
			}
			
			if( isset($_POST['orderdirection']) && $_POST['orderdirection'] == 'asc' )
			{
				$OrderDirection = 'ASC';
			}
			else
			{
				$OrderDirection = 'DESC';
			}
			
			if( isset($_POST['page']) && is_numeric($_POST['page']) )
			{
				$Page = $_POST['page'];
			} 
			else
			{
				$Page = 0;
			}
			
			
			if( isset($_POST['perpage']) && is_numeric($_POST['perpage']) )
			{
				$Limit = $_POST['perpage'];
			} 
			else
			{
				$Limit = 20;
			}
			
			if( isset($_POST['categoryid']) && is_numeric($_POST['categoryid']) && $_POST['categoryid'] )
			{
				$CategoryId = $_POST['categoryid'];
			}
			else
			{
				$CategoryId = false;
			}
			
			if( isset($_POST['authorid']) && is_numeric($_POST['authorid']) && $_POST['authorid'] != 0 )
			{
				$AuthorId = $_POST['authorid'];
			}
			else
			{
				$AuthorId = false;
			}
			
			$SelectQuery = 'SELECT * FROM ?blog ';
			$CountQuery = 'SELECT COUNT(*) AS blogno FROM ?blog ';
			
			if( $CategoryId || $AuthorId )
			{
				$SelectQuery .= 'WHERE';
				$CountQuery .= 'WHERE';
			}
			
			if( $CategoryId )
			{
				$SelectQuery .= ' categoryid = ' . $CategoryId . ' ';
				$CountQuery .= ' categoryid = ' . $CategoryId . ' ';
			}
			
			if( $AuthorId )
			{
				if( $CategoryId )
				{
					$SelectQuery .= 'AND';
					$CountQuery .= 'AND';
				}
				
				$SelectQuery .= ' authorid = ' . $AuthorId . ' ';
				$CountQuery .= ' authorid = ' . $AuthorId . ' ';
			}
			
			$Offset = $Page * $Limit;
			
			$SelectQuery .= ' ORDER BY ' . $OrderBy . ' ' . $OrderDirection . ' LIMIT ' . $Limit . ' OFFSET ' . $Offset . ' ';
			
			$BlogList = FFCore::$Db->GetData($SelectQuery);
			$BlogCount = FFCore::$Db->GetOne($CountQuery);
			$PageNo = ceil($BlogCount['blogno'] / $Limit);
			
			foreach( $BlogList as &$Blog)
			{
				$Blog['title'] = libText::LoadOne('blog', $Blog['blogid'], 'title');
				$Blog['category'] = FFCore::$Db->GetOne('SELECT * FROM ?blog_category WHERE categoryid = ' . $Blog['categoryid'] . ' LIMIT 1');
				
				$Blog['categoryName'] = libText::LoadOne('blog_category', $Blog['category']['categoryid'], 'title', 'hr');
				
				$Blog['author'] = FFCore::$Db->GetOne('SELECT authorid, name FROM ?blog_author WHERE authorid = ' . $Blog['authorid'] . ' LIMIT 1');
			
				$CategoryAccessLabel = 'blog_category_' . $Blog['categoryid'];
				if( FFCore::$Access->Has($CategoryAccessLabel) )
				{
					$Blog['editable'] = true;
				}
				
				$CategoryAdminAccessLabel = 'blog_category_admin_' . $Blog['categoryid'];
				if( FFCore::$Access->Has($CategoryAdminAccessLabel) )
				{
					$Blog['editable'] = true;
					$Blog['delitable'] = true;
				}
			
				$Blog['publishtime'] = libDateTime::Date('H:i d.m.Y.', $Blog['publishtime']);
				$Blog['num_comments'] = FFCore::$Db->GetOne('SELECT COUNT(commentid) AS number FROM ?comments WHERE interid = "' . $Blog['blogid'] . '" AND datafrom="blog"');
			
			}
			
			usrAjax::SetOutputData(array('error' => false, 'pageno' => $PageNo, 'blog' => $BlogList) );
			return false;
		}
	}
}


BlogList::Run();


?>