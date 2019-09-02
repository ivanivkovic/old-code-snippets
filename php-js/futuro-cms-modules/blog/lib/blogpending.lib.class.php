<?php 



class libBlogPending
{

	public static function GetList()
	{
		$CutOfTime = libDateTime::Time() - (14 * 24 * 60 * 60);
		$PendingBlog = FFCore::$Db->GetData('SELECT * FROM ?blog WHERE published = 0 AND publishtime > ' . $CutOfTime . ' ORDER BY publishtime DESC LIMIT 100');
		
		$PendingBlogOutput = array();
		foreach($PendingBlog as $Blog)
		{
			if( FFCore::$Access->Has('blog_category_admin_' . $Blog['categoryid']) )
			{
				$Blog['title'] = libText::LoadOne('blog', $Blog['blogid'], 'title');
				$Category = FFCore::$Db->GetOne('SELECT title FROM ?blog_category WHERE categoryid = ' . $Blog['categoryid'] . ' LIMIT 1');
				$Blog['category'] = $Category['title'];
				$PendingBlogOutput[] = $Blog;
			}
		}
		
		return $PendingBlogOutput;
	}
	
	
	public static function GetForFrontpage()
	{

		$PendingBlog = FFCore::$Db->GetData('SELECT * FROM ?blog WHERE frontpage = 1');
		
		foreach($PendingBlog as &$Blog)
		{
			$Blog['title'] = libText::LoadOne('blog', $Blog['blogid'], 'title');
			$Category = FFCore::$Db->GetOne('SELECT title FROM ?blog_category WHERE categoryid = ' . $Blog['categoryid'] . ' LIMIT 1');
			$Blog['category'] = $Category['title'];
		}
		
		return $PendingBlog;
	}
}




?>