<?php 


class apiBlogLoad
{
	public static function LoadBlogById($BlogId, $lang = 'hr')
	{
		if( ! $BlogData = FFCore::$Db->GetOne('SELECT * FROM ?blog WHERE blogid = ' . $BlogId . ' AND published="1"') )
		{
			return 6; // ne postojeći sadržaj
		}

		if( $BlogData['published'] != 1 || $BlogData['publishtime'] > libDateTime::Time() || ($BlogData['unpublishtime'] != 0 && $BlogData['unpublishtime'] > libDateTime::Time()) )
		{
			if( ! FFCore::$Access->Has('blog_category_admin_' . $BlogData['categoryid']) && ! FFCore::$Access->Has('blog_category_' . $BlogData['categoryid']) )
			{
				return 1;
			}
		}
		
		$CategoryData = FFCore::$Db->GetOne('SELECT * FROM ?blog_category WHERE categoryid = "' . $BlogData['categoryid'] . '" LIMIT 1');
		
		$CategoryData['title'] = libText::LoadOne('blog_category', $CategoryData['categoryid'], 'title', 'hr');
		
		$BlogData['tags'] = self::_LoadBlogTags($BlogId, $lang);
		
		if( $BlogData['next'] = FFCore::$Db->GetOne('SELECT * FROM ?blog 
					WHERE categoryid = ' . $BlogData['categoryid'] . ' AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ')
					AND publishtime > ' . $BlogData['publishtime'] . ' 
					ORDER BY publishtime ASC LIMIT 1') )
		{
			$BlogData['next']['title'] = libText::LoadOne('blog', $BlogData['next']['blogid'], 'title');
			$BlogData['next']['url'] = FFConf::GetUrl() . 'blog/' . libString::ToLabel($CategoryData['title']) . '-' . $CategoryData['categoryid'] . '/' . libString::ToLabel($BlogData['next']['title']) . '-' . $BlogData['next']['blogid'] . '.html';
		}
		
		if( $BlogData['prew'] = FFCore::$Db->GetOne('SELECT * FROM ?blog 
					WHERE categoryid = ' . $BlogData['categoryid'] . ' AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ')
					AND publishtime < ' . $BlogData['publishtime'] . ' 
					ORDER BY publishtime DESC LIMIT 1') )
		{
			$BlogData['prew']['title']  = libText::LoadOne('blog', $BlogData['prew']['blogid'], 'title');
			$BlogData['prew']['url'] = FFConf::GetUrl() . 'blog/' . libString::ToLabel($CategoryData['title']) . '-' . $CategoryData['categoryid'] . '/' . libString::ToLabel($BlogData['prew']['title']) . '-' . $BlogData['prew']['blogid'] . '.html';
		}
		
		$BlogData['archive'] = FFConf::GetUrl() . 'blog/' . libString::ToLabel($CategoryData['title']) . '-' . $CategoryData['categoryid'] . '/';
		
		
		$BlogData['text'] = self::_LoadBlogTexts($BlogData['blogid'], $lang);
		$BlogData['images'] = self::_LoadBlogImages($BlogData['blogid']);
		/*if( ! isset($BlogData['images']['inlinelist']['head']) )
		{
			$BlogData['images']['inlinelist']['head'] = apiLoadImages::LoadFirstFromList('blog', $BlogData['blogid']);
		}*/
		$BlogData['doc'] = self::_LoadBlogDocuments($BlogData['blogid']);
		$BlogData['weblinks'] = self::_LoadBlogWeblinks($BlogData['blogid']);
		
		$BlogData['blog'] = self::LoadFromCategory($BlogData['categoryid'], 0, 5, $BlogData['blogid']);
		$BlogData['share'] = FFConf::GetUrl() . 'share/blog/' . libString::ToLabel($CategoryData['title']) . '-' . $CategoryData['categoryid'] . '/' . libString::ToLabel($BlogData['text']['title']) . '-' . $BlogData['blogid'] . '.html';
		
		//apiInitContent::HeadVar('PageTitle', 'Blog: ' . $BlogData['text']['title']);
		//apiInitContent::HeadVar('PageDescription', $BlogData['text']['short_text']);
		
		$BlogData['authordata'] = FFCore::$Db->GetOne('SELECT * FROM ?blog_author WHERE authorid="' . $BlogData['authorid'] . '"');
		
		return $BlogData;
	}
	
	public static function LoadCategories($AuthorId, $lang)
	{
		$AuthorExt = $AuthorId != 0 ? 'AND ?blog.authorid="' . $AuthorId . '"' : '';
		
		$Data = FFCore::$Db->GetData('SELECT DISTINCT ?blog.categoryid FROM ?blog 
										WHERE published="1"
										AND ?blog.publishtime < ' . libDateTime::Time() . '
										AND ( ?blog.unpublishtime = 0 OR ?blog.unpublishtime > ' . libDateTime::Time() . ')' . $AuthorExt);
		
		if($Data && !empty($Data))
		{
			foreach($Data as &$Item)
			{
				$Item['title'] = libText::LoadOne('blog_category', $Item['categoryid'], 'title', $lang);
			}
		}
		else
		{
			return array();
		}
		
		return $Data;
	}
	
	private static function _LoadBlogTags($BlogId, $Lang = 'hr')
	{
		$Tags = FFCore::$Db->GetData('
									SELECT 
										DISTINCT ?keyword_link.keywordid,
										word
									FROM ?keyword_link
									JOIN ?keyword
										ON ?keyword_link.keywordid = ?keyword.keywordid
									JOIN ?blog
										ON ?blog.blogid = ?keyword_link.interid
									WHERE datafrom="blog_' . $Lang . '"
										 AND interid="' . $BlogId . '" 
										 AND published="1"
										AND ?blog.publishtime < ' . libDateTime::Time() . '
										AND ( ?blog.unpublishtime = 0 OR ?blog.unpublishtime > ' . libDateTime::Time() . ')
									ORDER BY word DESC
								');
		
		if( ! $Tags || is_numeric( $Tags ) || empty( $Tags ) )
		{
			return array();
		}
		else
		{
			return $Tags;
		}
	}
	
	public static function LoadAuthorTags($AuthorId, $lang = 'hr')
	{
		$AuthorExt = $AuthorId != 0 ? ' ?blog.authorid="' . $AuthorId . '" AND ' : '';
		
		$Tags = FFCore::$Db->GetData('
									SELECT DISTINCT 
										 ?keyword_link.keywordid AS keywordid,
										 ?keyword.word AS word
									FROM ?blog
									JOIN ?keyword_link
										ON datafrom = "blog_' . $lang . '"
										AND ?blog.blogid =  fly_keyword_link.interid
									JOIN ?keyword
										ON ?keyword_link.keywordid = ?keyword.keywordid
									WHERE ' . $AuthorExt . ' ?blog.published="1"');
		
		if( ! $Tags || is_numeric( $Tags ) || empty( $Tags ) )
		{
			return array();
		}
		else
		{
			return $Tags;
		}
	}
	
	private static function _LoadBlogTexts($BlogId = false, $Lang = 'hr')
	{
		return libText::Load('blog', $BlogId, $Lang);
	}
	
	private static function _LoadBlogImages($BlogId)
	{
		libLoad::Api('LoadImages', 'images');
		return apiLoadImages::LoadImagesForElement('blog', $BlogId);
	}
	
	private static function _LoadBlogDocuments($BlogId)
	{
		libLoad::Api('LoadDoc', 'repository');
		return apiLoadDoc::LoadDocumentsForElement('blog', $BlogId);
	}
	
	private static function _LoadBlogWeblinks($BlogId)
	{
		libLoad::Api('LoadWeblinks', 'weblinks');
		return apiLoadWeblinks::LoadWeblinksForElement('blog', $BlogId);
	}
	
	public static function LoadFromCategory($CategoryId, $Page = 0, $Limit = 5, $IgnoreId = false, $Year = false)
	{
		$CategoryData = FFCore::$Db->GetOne('SELECT * FROM ?blog_category WHERE categoryid = ' . $CategoryId . ' LIMIT 1');
		$CategoryData['title'] = libText::LoadOne('blog_category', $CategoryData['categoryid'], 'title', 'hr');
		
		if( $CategoryId != 0 && ! empty($CategoryData) )
		{
			$CategoryData['archive'] = FFConf::GetUrl() . 'blog/' . libString::ToLabel($CategoryData['title']) . '-' . $CategoryData['categoryid'] . '/';
			$CategoryData['share'] = FFConf::GetUrl() . 'share/blog/' . libString::ToLabel($CategoryData['title']) . '-' . $CategoryData['categoryid'] . '/';
			$CategoryData['rss'] = FFConf::GetUrl() . 'rss/' . libString::ToLabel($CategoryData['title']) . '-' . $CategoryData['categoryid'] . '.xml';
		}
		else
		{
			$CategoryData['archive'] = FFConf::GetUrl() . 'arhiva-blog/';
			$CategoryData['share'] = FFConf::GetUrl() . 'share/arhiva-blog/';
			$CategoryData['rss'] = FFConf::GetUrl() . 'rss/blog-s-pocetne-stranice-0.xml';
			$CategoryData['title'] = 'Blogovi s početne stranice';
			$CategoryData['categoryid'] = 0;
		}
		
		$Offset = $Page * $Limit;

		if( $Year === false || $Year == 0 )
		{		
			if( $IgnoreId === false )
			{
				if( $CategoryId != 0 )
				{
					$BlogList = FFCore::$Db->GetData('SELECT * FROM ?blog 
						WHERE categoryid = ' . $CategoryId . ' AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ')
						ORDER BY publishtime DESC LIMIT ' . $Limit . ' OFFSET ' . $Offset . ' ');
			
					$BlogCount = FFCore::$Db->GetOne('SELECT COUNT(*) AS blogno FROM ?blog 
						WHERE categoryid = ' . $CategoryId . ' AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ') ');
				}
				else
				{
					$BlogList = FFCore::$Db->GetData('SELECT * FROM ?blog 
						WHERE (categoryid = ' . self::$_FrontpageCategoryId . ' OR frontpage = 2) AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ')
						ORDER BY publishtime DESC LIMIT ' . $Limit . ' OFFSET ' . $Offset .' ');
			
					$BlogCount = FFCore::$Db->GetOne('SELECT COUNT(*) AS blogno FROM ?blog 
						WHERE (categoryid = ' . self::$_FrontpageCategoryId . ' OR frontpage = 2) AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ') ');
				}
			}
			else
			{
				if( $CategoryId != 0 )
				{
					$BlogList = FFCore::$Db->GetData('SELECT * FROM ?blog 
							WHERE categoryid = ' . $CategoryId . ' AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ')
							AND blogid <> ' . $IgnoreId . ' 
							ORDER BY publishtime DESC LIMIT ' . $Limit . ' OFFSET ' . $Offset .' ');
				
					$BlogCount = FFCore::$Db->GetOne('SELECT COUNT(*) AS blogno FROM ?blog 
							WHERE categoryid = ' . $CategoryId . ' AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ') AND blogid <> ' . $IgnoreId . '  ');
				}
				else
				{
					$BlogList = FFCore::$Db->GetData('SELECT * FROM ?blog 
							WHERE (categoryid = ' . self::$_FrontpageCategoryId . ' OR frontpage = 2) AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ')
							AND blogid <> ' . $IgnoreId . ' 
							ORDER BY publishtime DESC LIMIT ' . $Limit . ' OFFSET ' . $Offset .' ');
				
					$BlogCount = FFCore::$Db->GetOne('SELECT COUNT(*) AS blogno FROM ?blog
							WHERE (categoryid = ' . self::$_FrontpageCategoryId . ' OR frontpage = 2) AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ') AND blogid <> ' . $IgnoreId . '  ');
				}
			}
		}
		else
		{
			$YearStartTime = libDateTime::MKTime(0,0,0,1,1,$Year);
			$YearEndTime = libDateTime::MKTime(23,59,59,31,12,$Year);
			if( $IgnoreId === false )
			{
				if( $CategoryId != 0 )
				{
					$BlogList = FFCore::$Db->GetData('SELECT * FROM ?blog
						WHERE categoryid = ' . $CategoryId . ' AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ')
						AND ( publishtime > ' . $YearStartTime . ' AND publishtime < ' . $YearEndTime . ' )
						ORDER BY publishtime DESC ');
			
					$BlogCount = FFCore::$Db->GetOne('SELECT COUNT(*) AS blogno FROM ?blog
						WHERE categoryid = ' . $CategoryId . ' AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ') ');
				}
				else
				{
					$BlogList = FFCore::$Db->GetData('SELECT * FROM ?blog 
						WHERE (categoryid = ' . self::$_FrontpageCategoryId . ' OR frontpage = 2) AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ')
						AND ( publishtime > ' . $YearStartTime . ' AND publishtime < ' . $YearEndTime . ' )
						ORDER BY publishtime DESC ');
			
					$BlogCount = FFCore::$Db->GetOne('SELECT COUNT(*) AS blogno FROM ?blog 
						WHERE (categoryid = ' . self::$_FrontpageCategoryId . ' OR frontpage = 2) AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ') ');
				}
			}
			else
			{
				if( $CategoryId != 0 )
				{
					$BlogList = FFCore::$Db->GetData('SELECT * FROM ?blog 
							WHERE categoryid = ' . $CategoryId . ' AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ')
							AND blogid <> ' . $IgnoreId . ' 
							AND ( publishtime > ' . $YearStartTime . ' AND publishtime < ' . $YearEndTime . ' )
							ORDER BY publishtime DESC ');
				
					$BlogCount = FFCore::$Db->GetOne('SELECT COUNT(*) AS blogno FROM ?blog 
							WHERE categoryid = ' . $CategoryId . ' AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ') AND blogid <> ' . $IgnoreId . '  ');
				}
				else
				{
					$BlogList = FFCore::$Db->GetData('SELECT * FROM ?blog 
							WHERE (categoryid = ' . self::$_FrontpageCategoryId . ' OR frontpage = 2) AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ')
							AND blogid <> ' . $IgnoreId . ' 
							AND ( publishtime > ' . $YearStartTime . ' AND publishtime < ' . $YearEndTime . ' )
							ORDER BY publishtime DESC LIMIT ');
				
					$BlogCount = FFCore::$Db->GetOne('SELECT COUNT(*) AS blogno FROM ?blog 
							WHERE (categoryid = ' . self::$_FrontpageCategoryId . ' OR frontpage = 2) AND published = 1 AND publishtime < ' . libDateTime::Time() . ' AND (unpublishtime = 0 OR unpublishtime > ' . libDateTime::Time() . ') AND blogid <> ' . $IgnoreId . '  ');
				}
			}
		}
		
		libLoad::Api('LoadImages', 'images');
		foreach( $BlogList as &$Blog)
		{
			$Blog['title'] = libText::LoadOne('blog', $Blog['blogid'], 'title');
			$Blog['short_text'] = libText::LoadOne('blog', $Blog['blogid'], 'short_text');
			if( ! $Blog['image'] = apiLoadImages::LoadSingleInline('blog', $Blog['blogid'], 'head') )
			{
				$Blog['image'] = apiLoadImages::LoadFirstFromList('blog', $Blog['blogid']);
			}
			
			if( $CategoryId != 0 )
			{
				$Blog['url'] = FFConf::GetUrl() . 'blog/' . libString::ToLabel($CategoryData['title']) . '-' . $CategoryData['categoryid'] . '/' . libString::ToLabel($Blog['title']) . '-' . $Blog['blogid'] . '.html';
			}
			else
			{
				$CategoryDataTmp = FFCore::$Db->GetOne('SELECT categoryid, title FROM ?blog_category WHERE categoryid = ' . $Blog['categoryid'] . ' LIMIT 1');
				$Blog['url'] = FFConf::GetUrl() . 'blog/' . libString::ToLabel($CategoryDataTmp['title']) . '-' . $CategoryDataTmp['categoryid'] . '/' . libString::ToLabel($Blog['title']) . '-' . $Blog['blogid'] . '.html';
			}
			
			
		}
		
		$CategoryData['list'] = $BlogList;
		$CategoryData['pageno'] = ceil( $BlogCount['blogno'] / $Limit );
		$CategoryData['page'] = $Page;
		
		//apiInitContent::HeadVar('PageTitle', $CategoryData['title']);
		//apiInitContent::HeadVar('PageDescription', 'Blogovi iz kategorije: ' . $CategoryData['title']);
		
 		return $CategoryData;
	}
	
	
	
	public static function LoadFromCategoryLang($CategoryId, $Page = 0, $Limit = 5, $Lang = 'hr')
	{
		$Offset = $Page * $Limit;
		
		$BlogList = FFCore::$Db->GetData('SELECT * FROM ?blog JOIN ?text ON ?text.datafrom = \'blog\' AND ?text.lang = \''.$Lang.'\' AND ?text.varname = \'title\' AND ?blog.blogid = ?text.interid 
						WHERE ?blog.published = 1 AND ?blog.publishtime < ' . libDateTime::Time() . ' AND ( ?blog.unpublishtime = 0 OR ?blog.unpublishtime > ' . libDateTime::Time() . ') AND ?text.text <> \'\'
						ORDER BY ?blog.publishtime DESC LIMIT ' . $Limit . ' OFFSET ' . $Offset .' ');
			

		$BlogCount = FFCore::$Db->GetOne('SELECT COUNT(*) AS blogno FROM ?blog JOIN ?text ON ?text.datafrom = \'blog\' AND ?text.lang = \''.$Lang.'\' AND ?text.varname = \'title\' AND ?blog.blogid = ?text.interid 
						WHERE ?blog.published = 1 AND ?blog.publishtime < ' . libDateTime::Time() . ' AND ( ?blog.unpublishtime = 0 OR ?blog.unpublishtime > ' . libDateTime::Time() . ') AND ?text.text <> \'\' ');
	
	
		libLoad::Api('LoadImages', 'images');
		foreach( $BlogList as &$Blog)
		{
			$Blog['title'] = libText::LoadOne('blog', $Blog['blogid'], 'title', $Lang);
			$Blog['short_text'] = libText::LoadOne('blog', $Blog['blogid'], 'short_text', $Lang);
			if( ! $Blog['image'] = apiLoadImages::LoadSingleInline('blog', $Blog['blogid'], 'head') )
			{
				$Blog['image'] = apiLoadImages::LoadFirstFromList('blog', $Blog['blogid']);
			}
		}
		
		$CategoryData['list'] = $BlogList;
		$CategoryData['pageno'] = ceil( $BlogCount['blogno'] / $Limit );
		$CategoryData['page'] = $Page;
		
		//apiInitContent::HeadVar('PageTitle', $CategoryData['title']);
		//apiInitContent::HeadVar('PageDescription', 'Blogovi iz kategorije: ' . $CategoryData['title']);
		
 		return $CategoryData;
	}
	
	public static function LoadBlogsByCriteria($AuthorId, $categoryId, $tagId, $limit, $page, $date, $lang = 'hr')
	{
		if($date === 0)
		{
			return array();
		}
		else
		{
			$AuthorExt = $AuthorId != 0 ? 'AND authorid="' . $AuthorId . '"' : '';
			$TimeStampEnd = $date + date('t', $date) * 24 * 60 * 60;
			
			$SQLDateExtension = ' AND ( ?blog.publishtime >= ' . $date . ' AND ?blog.publishtime <= ' . $TimeStampEnd . ')';
			
			$offset = $page * $limit;
			$BlogsData = array();
			
			// Učitaj blogove po kategoriji
			if($categoryId != 0 && $tagId === 0 )
			{
				$BlogsData = FFCore::$Db->GetData('
										SELECT
											blogid, publishtime, authorid
										FROM ?blog
										WHERE
											 ?blog.publishtime < ' . libDateTime::Time() . '
											AND ( ?blog.unpublishtime = 0 OR ?blog.unpublishtime > ' . libDateTime::Time() . ')
											AND categoryid="' . $categoryId . '"
											' . $AuthorExt . '
											 ' . $SQLDateExtension . '
										LIMIT ' . $limit . ' OFFSET ' . $offset
									);
			}
			
			// Učitaj blogove po tagu.
			if($tagId != 0 && $categoryId === 0)
			{
				$BlogsData = FFCore::$Db->GetData('
										SELECT interid AS blogid
										FROM ?keyword_link
										JOIN ?blog ON ?blog.blogid = ?keyword_link.interid
										WHERE keywordid="' . $tagId . '"
											AND datafrom = "blog_' . $lang . '"
											' . $AuthorExt . '
											AND published="1"
											AND ?blog.publishtime < ' . libDateTime::Time() . '
											AND ( ?blog.unpublishtime = 0 OR ?blog.unpublishtime > ' . libDateTime::Time() . ')
											 ' . $SQLDateExtension . '
										LIMIT ' . $limit . ' OFFSET ' . $offset
									);
			}
			
			// Učitaj blogove po kategoriji i tagu.
			if($tagId != 0 && $categoryId != 0)
			{
				$BlogsData = FFCore::$Db->GetData('
										SELECT interid AS blogid
										FROM ?keyword_link
										JOIN ?blog ON ?blog.blogid = ?keyword_link.interid
										WHERE keywordid="' . $tagId . '"
											AND datafrom = "blog_' . $lang . '"
											' . $AuthorExt . '
											AND published="1"
											AND categoryid="' . $categoryId . '"
											AND ?blog.publishtime < ' . libDateTime::Time() . '
											AND ( ?blog.unpublishtime = 0 OR ?blog.unpublishtime > ' . libDateTime::Time() . ')
											 ' . $SQLDateExtension . '
										LIMIT ' . $limit . ' OFFSET ' . $offset
									);
			}
		
			foreach($BlogsData as &$Blog)
			{
				$BlogData = self::LoadBlogById($Blog['blogid'], $lang);
				
				if($BlogData !== 6)
				{
					$Blog = $BlogData;
				}
				else
				{
					$Blog = array();
				}
			}
		}
		return $BlogsData;
	}
	
	public static function LoadLatestBlogs($AuthorId, $Limit, $Page, $Date, $Lang)
	{
		$Offset = $Page * $Limit;
		$AuthorExt = $AuthorId !== 0 ? ' AND authorid="'  . $AuthorId . '"' : '';
		
		$TimeStampEnd = $Date + date('t', $Date) * 24 * 60 * 60;
			
		$SQLDateExtension = ' AND ( ?blog.publishtime >= ' . $Date . ' AND ?blog.publishtime <= ' . $TimeStampEnd . ')';
		
		$BlogsData = FFCore::$Db->GetData('
										SELECT
											blogid, publishtime, authorid
										FROM ?blog
										WHERE
											 ?blog.publishtime < ' . libDateTime::Time() . '
											AND ( ?blog.unpublishtime = 0 OR ?blog.unpublishtime > ' . libDateTime::Time() . ')
											' . $AuthorExt .
											$SQLDateExtension . ' 
											ORDER BY publishtime DESC
										LIMIT ' . $Limit . ' OFFSET ' . $Offset
									);
		
		foreach($BlogsData as &$Blog)
		{
			$BlogData = self::LoadBlogById($Blog['blogid'], $Lang);
			
			if($BlogData !== 6)
			{
				$Blog = $BlogData;
			}
			else
			{
				$Blog = array();
			}
		}
		
		return $BlogsData;
	}
	
	/**
	* Radi i za jednog autora i za više njih.
	*/
	
	public static function LoadBlogAuthors($lang, $sort = false)
	{
		$sortExt = '';
		
		if ( $sort !== false )
		{
			switch( $sort )
			{
				case 'num_posts':
					$sortExt = ' ORDER BY num_posts DESC';
				break;
				
				case 'sort':
					$sortExt = ' ORDER BY sort ASC';
				break;
			}
			
		}
		
		$AuthorsData = FFCore::$Db->GetData('
								SELECT
								
									 ?blog_author.*,
									COUNT( ?blog.blogid) AS num_posts,
									MAX( ?blog.publishtime) AS last_publishtime
									
								FROM ?blog_author
								
								LEFT JOIN ?blog
									ON ?blog_author.authorid = ?blog.authorid
										AND published="1"
										AND ?blog.publishtime < ' . libDateTime::Time() . '
										AND ( ?blog.unpublishtime = 0 OR ?blog.unpublishtime > ' . libDateTime::Time() . ')
								
								GROUP BY ?blog_author.authorid
								' . $sortExt
							);
		
		if($AuthorsData === 6 || empty($AuthorsData) || ! $AuthorsData)
		{
			return array();
		}
		else
		{
			// Konvertaj key array-a u authorid i nadopuni s libText informacijom.
			foreach($AuthorsData as $AuthorData)
			{
				libLoad::Api('LoadImages', 'images');
				
				$NewAuthorsData[$AuthorData['authorid']] = $AuthorData;
				$NewAuthorsData[$AuthorData['authorid']]['title'] = libText::LoadOne('blog_author', $AuthorData['authorid'], 'title', $lang);
				$NewAuthorsData[$AuthorData['authorid']]['role'] = libText::LoadOne('blog_author', $AuthorData['authorid'], 'role', $lang);
				$NewAuthorsData[$AuthorData['authorid']]['image'] = apiLoadImages::LoadSingleInline('blog', $AuthorData['authorid'], 'author_image');
			}
			
			return $NewAuthorsData;
		}
	}
	
	public static function LoadAllBlogsTags($lang = 'hr')
	{
		return self::_LoadBlogTags(false, $lang);
	}
	
	public static function LoadBlogForElement($DataFrom, $InterId = 0, $Page, $Year = false)
	{
		if( $InterId )
		{
			if( ! $AddonData = FFCore::$Db->GetOne('SELECT * FROM ?blog_link WHERE datafrom = \'' . $DataFrom . '\' AND interid = ' . $InterId . '  LIMIT 1') )
			{
				return false;
			}
			
			return self::LoadFromCategory($AddonData['categoryid'], $Page, 5, false, $Year);
		}
		else
		{
			return false;
		}
	}

	public static function LoadBlogMonths($AuthorId)
	{
		$AuthorExt = ' AND ' . $AuthorId != 0 ? 'authorid="' . $AuthorId : '';
		
		$Dates = FFCore::$Db->GetData('SELECT publishtime AS timestamp
										FROM ?blog
										WHERE published="1"
										'
										. $AuthorExt .
										'
											AND ?blog.publishtime < ' . libDateTime::Time() . '
											AND ( ?blog.unpublishtime = 0 OR ?blog.unpublishtime > ' . libDateTime::Time() . ')
										ORDER BY publishtime DESC');
		
		$NewDates = array();
		
		if( $Dates !== 6 && !empty($Dates) )
		{
			// Stvori novi array bez duplića.
			foreach( $Dates as $key => $Date )
			{
				$y = date('Y', $Date['timestamp']);
				$m = date('n', $Date['timestamp']);
				$f = date('F', $Date['timestamp']);
				
				$continue = 'yes';
				
				if(isset($NewDates))
				{
					foreach( $NewDates as $nDate )
					{
						if( isset($nDate['value']) && $nDate['value'] == $m . '-'. $y )
						{
							$continue = 'no';
						}
					}
				}
				
				if($continue === 'yes')
				{
					$NewDates[$key]['month'] = $m;
					$NewDates[$key]['year'] = $y;
					$NewDates[$key]['value'] = $m . '-' . $y;
					$NewDates[$key]['timestamp'] = strtotime('first day of ' . $f . $y);
				}
			}
		}
		
		return $NewDates;
	}
}

?>