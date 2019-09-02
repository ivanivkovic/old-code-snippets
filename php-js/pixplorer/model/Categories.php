<?php

class Categories{
	
	# Fetches all categories.
	public static function fetchCategories($id = '')
	{
		if($id === ''){ $id = 0; }
		
		$query = 'SELECT * FROM db_cat WHERE parent="' . $id . '"  ORDER BY title ASC';
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	public static function getPhotosCount($catId)
	{
		$result = DB::query('cat_id', 'db_cat', array('parent' => $catId));
		
		$count = 0;
		while($fetch = $result->fetch(PDO::FETCH_ASSOC))
		{
			$query = 'SELECT COUNT(pic_id) AS counted FROM sc_pics WHERE cat_id="' . $fetch['cat_id'] . '"';
			$result2 = DB::sql($query);
			$fetch2 = $result2->fetch(PDO::FETCH_ASSOC);
			$count += $fetch2['counted'];
		}
		return $count;
	}
	
	# Fetches category $id's name.
	public static function fetchCatName($id)
	{
		$query = 'SELECT title FROM db_cat WHERE cat_id="' . $id . '"';
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			$fetch = $result->fetch(PDO::FETCH_ASSOC);
			return $fetch['title'];
		}
		else
		{
			return false;
		}
	}
	
	# Returns possible parent category ID and title or false value.
	public static function fetchParent($id)
	{
		$query = 'SELECT parent FROM db_cat WHERE cat_id="' . $id . '"';
		$result = DB::sql($query);
		
		while($fetch = $result->fetch(PDO::FETCH_ASSOC))
		{
			if($fetch['parent'] != 0)
			{
				$query = 'SELECT * FROM db_cat WHERE cat_id="' . $fetch['parent'] . '"';
				$result2 = DB::sql($query);
				return $result2->fetch(PDO::FETCH_ASSOC);
			}
			else
			{
				return false;
			}
		}
	}
	
	public static function getCategoryAndParent($id, $separator, $link = false)
	{
		
		$class = 'class="hover_underline"';
		
		$parent 		= self::fetchParent($id);
		$cat_name 		= $link === true ? '<a ' . $class . ' href="' . Conf::$page['search_categories'] . '0/' . $parent['cat_id'] . '/' . $id . '">' . self::fetchCatName($id) . '</a>' : self::fetchCatName($id);
		$parent_title 	= $link === true ? '<a ' . $class . ' href="' . Conf::$page['search_categories'] . '0/' . $parent['cat_id'] . '">' . $parent['title'] . '</a>' : $parent['title'];
		$separator		 = $parent !== false ? $separator : '';
		
		return $cat_name . $separator . $parent_title;
	}
	
}