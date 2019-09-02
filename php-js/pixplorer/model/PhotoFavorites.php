<?php

class PhotoFavorites{
	
	# Checks if the user has favorited the viewing photo.
	public static function isFavorite($user_id, $fav_id)
	{
		$result = DB::sql('SELECT id FROM sc_pic_favorites WHERE user_id="' . $user_id . '" AND fav_id="' . $fav_id . '"');
		$return = $result->rowCount() ? true : false;
		
		return $return;
	}
	
	# Adds a photo to favorites.
	public static function addToFavorites($user_id, $fav_id)
	{
		$result = DB::sql('INSERT INTO sc_pic_favorites SET user_id="' . $user_id . '", fav_id="' . $fav_id . '"');
		
		$lID = DB::$db->lastInsertId();
		Notifications::setNotification(array('type' => 1, 'time' => 'NOW()', 'subject_id' => $fav_id, 'user_id' => Picture::getOwner($fav_id), 'user_poster_id' => $user_id));
		
		$return = $result !== false ? true : false;
		return $return;
	} 
	
	# Rempves any picture sfrom favorites.
	public static function removeFromFavorites($user_id, $fav_id)
	{
		$result = DB::sql('DELETE FROM sc_pic_favorites WHERE user_id="' . $user_id . '" AND fav_id="' . $fav_id . '"');
		$return = $result !== false ? true : false;
		
		return $return;
	}
	
	# Fetches user's favorites.
	public static function fetchFavorites($user_id)
	{
		$result = DB::sql('SELECT
							sc_pic_favorites.fav_id,
							sc_pics.src,
							sc_pics.cat_id,
							sc_pics.city_id,
							sc_users.user_id,
							sc_users.fullname,
							sc_users.nav_user_pic
						FROM sc_pic_favorites
						JOIN sc_pics
							ON sc_pic_favorites.fav_id=sc_pics.pic_id
						JOIN sc_users
						ON sc_pics.user_id=sc_users.user_id
						WHERE sc_pic_favorites.user_id="' . $user_id . '"'
					);
		
		$return = $result !== false ? $result : false;
		return $return;
	}
	
	# Returns true if user has favorited any photos.
	public static function userHasFavorites($user_id)
	{
		$result = DB::sql('SELECT sc_pic_favorites.fav_id FROM sc_pic_favorites WHERE user_id="' . $user_id . '" LIMIT 1');
		$return = $result !== false ? true : false;
		
		return $return;
	}
	
	# Returns picture's favorite count.
	public static function getSubjectFavoritesCount($subject_id)
	{
		$result = DB::sql('SELECT sc_pics.fav_count FROM sc_pics WHERE pic_id="' . $subject_id . '"');
		
		if($result->rowCount())
		{
			$fetch = $result->fetch(PDO::FETCH_ASSOC);
			$return = $fetch['fav_count'];
		}
		else
		{
			$return = 0;
		}
		return $return;
	}
	
	# Increments the count.
	public static function incrementFavoritesCount($subject_id)
	{
		$result = DB::sql('UPDATE sc_pics SET fav_count = fav_count + 1 WHERE pic_id="' . $subject_id . '"');
		$return = $result !== false ? true : false;
		
		return $return;
	}
	
	# Decrements the count.
	public static function decrementFavoritesCount($subject_id)
	{
		$result = DB::sql('UPDATE sc_pics SET fav_count = fav_count - 1 WHERE pic_id="' . $subject_id . '"');
		$return = $result !== false ? true : false;
		
		return $return;
	}
}