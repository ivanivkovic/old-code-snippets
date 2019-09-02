<?php

class Picture{ # extends baseModel
	
	# Upload it.
	public static function upload($user_id)
	{
		$dir = Conf::$dir['pics'] . $user_id;
		
		if(isset($_POST['subcategory']) && is_numeric($_POST['subcategory']))
		{
			$category = $_POST['subcategory'];
		}
		else
		{
			$category = $_POST['category'];
		}
		
		if(!is_dir($dir))
		{
			mkdir($dir);
		}
		
		for($i = 0; $i != count($_FILES['pics']['tmp_name']); ++$i)
		{
			$parts = explode('.', $_FILES['pics']['name'][$i]);
			$end = end($parts);
			$ext = '/' . time() . rand(1,rand(2,67888)) . '.' . $end;
			$dest = $dir . $ext;
			
			move_uploaded_file($_FILES['pics']['tmp_name'][$i], $dest);
			
				$getdate = getdate();
				$date = $getdate['year'] . '-' . $getdate['mon'] . '-' . $getdate['mday'];
				
				$query = 'INSERT INTO sc_pics SET user_id="' . $user_id . '", cat_id="' . $category . '", date="' . $date . '", city_id="' . $_POST['city'] . '", src="' . $ext . '", time=CURTIME(), homepage="1"';
				$result = DB::sql($query);
				
				$result2 = DB::sql('SELECT src, cat_id, user_id, city_id, pic_id FROM sc_pics WHERE pic_id = "' . DB::$db->lastInsertId() . '"');
				
			$files[$i] = $result2;
			
			$loc2 = Conf::$dir['thumbs'] . $user_id;
			
			# Generate thumbnail
			if(!is_dir($loc2))
			{
				mkdir($loc2);
			}
			
			self::generateThumbnail($dest, Conf::$dir['thumbs'] . $user_id . $ext);
		}
		return $files;
	}
	
	# Facebook publish uploads
	public static function facebookPublishUploads($uploads, $user, $num_rows, $template)
	{
		// GET CATEGORY NAME AND COUNTRY NAME AND CREATE A PLACE OBJECT WITH COUNTRY NAME
		
		$city_name = WorldDatabase::fetchName($uploads['city_id'], 'city');
		$parent = WorldDatabase::getParent($uploads['city_id']);
		
		$album = Categories::fetchCatName($uploads['cat_id']) . ' - ' . $parent['name'] . ', ' . $city_name;
		
		$permissions = $user->fb_object ->api('/me/permissions');
		
		if(array_key_exists('publish_stream', $permissions['data'][0])){
		
            $user->fb_object->api('/me/feed', 'POST',
				array(
					'name' => $album,
					'link' => Conf::$page['album_generated'] . $user->id . '/' . $uploads['cat_id'] . '/' . $uploads['city_id'],
					'message' => sprintf($template->loadString('upload_result_facebook_publish'), $user->data['fullname'], $num_rows, $album)
				)
			);
        }
	}
	
	public static function generateThumbnail($ex_image, $newfname)
	{
		$image = new ImageCropper();
		
		$image->setImage($ex_image);
		$image->createThumb(275, 275);
		$success = $image->renderImage($newfname);
		
		if($success)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	# Checks for input and file types, returns errors/warnings or true.
	public static function processUpload()
	{
		if(!empty($_POST))
		{
			if(isset($_POST['city']) && isset($_POST['category']) && !empty($_FILES['pics']))
			{
				# Processing category and everything else.
				if(is_numeric($_POST['category']) && $_POST['category'] != 0)
				{
					if(Security::checkNum($_POST['category']) != false)
					{
						if(Security::checkNum($_POST['city']) != false)
						{
							if(WorldDatabase::exists('city', $_POST['city']))
							{
								if(self::isValidType())
								{
									# Processing subcategory.
									if(isset($_POST['subcategory']))
									{
										if(Security::checkNum($_POST['subcategory']) != false)
										{
											$parent = Categories::fetchParent($_POST['subcategory']);
											
											if(Categories::fetchCatName($_POST['subcategory']) == false || $parent['cat_id'] == 0)
											{
												$return['success'] = false;
												$return['message'] = 'err_subcat_no_exist';
											}
											else
											{
												$return['success'] = true;
											}
										}
										else
										{
											$return['success'] = false;
											$return['message'] = 'err_subcat_no_exist';
										}
									}else{
										$return['success'] = false;
										$return['message'] = 'err_subcat_no_exist';
									}
								}else{
									$return['success'] = false;
									$return['message'] = 'err_subcat_no_exist';
								}
							}else{
								$return['success'] = false;
								$return['message'] = 'err_city_no_exist';
							}
						}else{
							$return['success'] = false;
							$return['message'] = 'err_invalid_city';
						}
					}else{
						$return['success'] = false;
						$return['message'] = 'err_invalid_category';
					}
				}else{
					$return['success'] = false;
					$return['message'] = 'err_category_no_exist';
				}
			}else{
				$return['success'] = false;
				$return['message'] = 'err_incomplete';
			}
		}else{
			$return['success'] = false;
			$return['message'] = 'err_no_upl_files';
		}
		return $return;
	}
	
	# Checks for file types.
	private static function isValidType()
	{
		for($i = 0; $i != count($_FILES['pics']['tmp_name']); ++$i)
		{
			$array = array('gif', 'jpg', 'png', 'jpeg', 'GIF', 'JPG', 'PNG', 'JPEG', 'PNG');
			$parts = explode('.', $_FILES['pics']['name'][$i]);
			$ext = end($parts);
			
			if(!in_array($ext, $array))
			{
				$error = 0;
			}
		}
		
		if(isset($error))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public static function fetchAlbumsByCity($city_id)
	{
		$query = 'SELECT DISTINCT db_cities.cityName, sc_users.user_id, sc_users.fullname, sc_users.nav_user_pic, sc_pics.city_id FROM sc_pics JOIN db_cities ON sc_pics.city_id=db_cities.cityID JOIN sc_users ON sc_users.user_id = sc_pics.user_id WHERE sc_pics.city_id = "' . $city_id . '" ORDER BY fullname ASC';
		$result = DB::sql($query);
		
		if($result->rowCount()){
		
			$c = 0;
			while($fetch = $result->fetch(PDO::FETCH_ASSOC)){
			
				$query = 'SELECT src FROM sc_pics WHERE city_id="' . $city_id . '" AND user_id="' . $fetch['user_id'] . '" LIMIT 1';
				$result2 = DB::sql($query);
				
				while($fetch2 = $result2->fetch(PDO::FETCH_ASSOC)){
					$return[$c]['pic'] = $fetch2['src'];
				}
				
				$return[$c]['id'] = $fetch['user_id'];
				$return[$c]['city_id'] = $fetch['city_id'];
				$return[$c]['name'] = $fetch['fullname'];
				$return[$c]['city_name'] = $fetch['cityName'];
				$return[$c]['nav_user_pic'] = $fetch['nav_user_pic'];
				++$c;
			}
			
		}else{
		
			$return = false;
			
		}
		
		return $return;
	}
	
	# Creates an album by querying a group of sc_pic columns with the same city_id and user_id value.
	public static function fetchAlbumsByLocation($user_id)
	{
	
		$query = 'SELECT DISTINCT city_id FROM sc_pics JOIN db_cities ON db_cities.cityID = city_id WHERE user_id = "' . $user_id . '" ORDER BY cityName ASC';
		$result = DB::sql($query);
		
		if($result->rowCount()){
			$c = 0;
			
			while($fetch = $result->fetch(PDO::FETCH_ASSOC))
			{
				$query = 'SELECT src FROM sc_pics WHERE city_id="' . $fetch['city_id'] . '" AND user_id="' . $user_id . '"';
				$result2 = DB::sql($query);
				
				while($fetch2 = $result2->fetch(PDO::FETCH_ASSOC))
				{
					$return[$c]['pic'] = $fetch2['src'];	
				}
				
				$return[$c]['city_id'] = $fetch['city_id'];
				$result2 = DB::sql('SELECT pic_id, src, city_id FROM sc_pics WHERE city_id="' . $fetch['city_id'] . '" AND user_id="' . $user_id . '"');
				$return[$c]['num_photos'] = $result2->rowCount();
				++$c;
			}			
		}
		else
		{
			$return = false;
		}
		
		return $return;
	}
	
	# Returns links to categories the user has upload photos to.
	public static function fetchCategoryAlbumsByUser($user_id)
	{	
		$query = 'SELECT DISTINCT sc_pics.cat_id FROM sc_pics JOIN db_cat ON sc_pics.cat_id=db_cat.cat_id WHERE sc_pics.user_id = "' . $user_id . '" AND parent!="0"';
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			$c = 0;
			while($fetch = $result->fetch(PDO::FETCH_ASSOC))
			{
				$query = 'SELECT src FROM sc_pics WHERE cat_id="' . $fetch['cat_id'] . '" AND user_id="' . $user_id . '" LIMIT 1';
				$result2 = DB::sql($query);
				
				while($fetch2 = $result2->fetch(PDO::FETCH_ASSOC))
				{
					$return[$c]['pic'] = $fetch2['src'];
				}
				
				$return[$c]['user_id'] = $user_id;
				$return[$c]['cat_id'] = $fetch['cat_id'];
				$result2 = DB::sql('SELECT pic_id FROM sc_pics WHERE cat_id="' . $fetch['cat_id'] . '" AND user_id="' . $user_id . '"');
				$return[$c]['num_photos'] = $result2->rowCount();
				++$c;
			}
		}
		else
		{
			$return = false;
		}
		return $return;
	}
	
	# Outputs city_id and user_id.
	public static function fetchAlbumsByCategory($cat_id)
	{
		$counter = 0;
		$return = false;
		$query = 'SELECT DISTINCT sc_pics.city_id, sc_pics.user_id, sc_users.social_type, sc_users.social_id FROM sc_pics JOIN sc_users ON sc_pics.user_id = sc_users.user_id WHERE cat_id="' . $cat_id . '"';
		
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			$return[$counter] = $result;
			++$counter;
		} 
		
		# Checks for category children and checks if there are albums with them tagged.
		$query = 'SELECT cat_id FROM db_cat WHERE parent="' . $cat_id . '"';
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			while($fetch = $result->fetch(PDO::FETCH_ASSOC))
			{
				$query2 = 'SELECT DISTINCT sc_pics.cat_id, sc_pics.city_id, sc_pics.user_id, sc_users.social_type, sc_users.social_id FROM sc_pics JOIN sc_users ON sc_pics.user_id = sc_users.user_id WHERE cat_id="' . $fetch['cat_id'] . '"';
				
				$result2 = DB::sql($query2);
				if($result2->rowCount())
				{
					$return[$counter] = $result2;
					++$counter;
				}
			}
		}
		return $return;
	}
	
	# Made specifically for category search. (When clicked on categories, popup form $_GET result.) Returns the user who has the album. 
	public static function fetchAlbumsByLocationAndCategory($cat_id, $city_id)
	{
		$counter = 0;
		$return = false;
		
		$query = 'SELECT DISTINCT sc_pics.user_id, sc_users.social_type, sc_users.social_id FROM sc_pics JOIN sc_users ON sc_pics.user_id = sc_users.user_id WHERE cat_id="' . $cat_id. '" AND city_id="' . $city_id . '"';
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			$return[$counter] = $result;
			++$counter;
		}
		else # If albums tagged with the category are not found, checks for category children and checks if there are albums with them tagged.
		{
			$query = 'SELECT cat_id FROM db_cat WHERE parent="' . $cat_id . '"';
			$result = DB::sql($query);
			
			if($result->rowCount())
			{
				while($fetch = $result->fetch(PDO::FETCH_ASSOC))
				{
					$query2 = 'SELECT DISTINCT 
								sc_pics.cat_id,
								sc_pics.city_id,
								sc_pics.user_id,
								sc_users.social_type,
								sc_users.social_id
							FROM sc_pics 
							JOIN sc_users 
								ON sc_pics.user_id = sc_users.user_id
							WHERE sc_pics.cat_id="' . $fetch['cat_id'] . '"
								AND sc_pics.city_id = "' . $city_id . '"';
								
					$result2 = DB::sql($query2);
					
					if($result2->rowCount())
					{
						$return[$counter] = $result2;
						++$counter;
					}
				}
			}
		}
		return $return;
	}
	
	# Made specifically for category search. (When clicked on categories, popup form $_GET result.) Returns the user who has the album. 
	public static function fetchAlbumsByParentAndCategory($cat_id, $parent_id, $parent_type)
	{
		$return = false;
		
		if($parent_type == 'country')
		{
			$query = 'SELECT cityID FROM db_cities WHERE countryID="' . $parent_id . '"';
			$result = DB::sql($query);
			
			while($fetch = $result->fetch(PDO::FETCH_ASSOC))
			{
				$query = 'SELECT DISTINCT
							sc_pics.city_id,
							sc_pics.cat_id,
							sc_pics.user_id,
							sc_users.social_type,
							sc_users.social_id
						FROM sc_pics
						JOIN sc_users
							ON sc_pics.user_id = sc_users.user_id
						WHERE sc_pics.cat_id="' . $cat_id. '"
							AND sc_pics.city_id="' . $fetch['cityID'] . '"';
							
				$result2 = DB::sql($query);
				if($result2->rowCount())
				{
					$return[$fetch['cityID']] = $result2;
				}
				else
				{
					$query = 'SELECT cat_id FROM db_cat WHERE parent="' . $cat_id . '"';
					$result2 = DB::sql($query);
					
					if($result2->rowCount())
					{
						while($fetch2 = $result2->fetch(PDO::FETCH_ASSOC))
						{
							$query = 'SELECT DISTINCT
										sc_pics.city_id,
										sc_pics.cat_id,
										sc_pics.city_id,
										sc_pics.user_id,
										sc_users.social_type,
										sc_users.social_id
									FROM sc_pics
									JOIN sc_users
										ON sc_pics.user_id = sc_users.user_id
									WHERE sc_pics.cat_id="' . $fetch2['cat_id'] . '"
										AND sc_pics.city_id = "' . $fetch['cityID'] . '"';
							$result3 = DB::sql($query);
							$return[$fetch['cityID']][$fetch2['cat_id']] = $result3->rowCount() ? $result3 : false;
							
							if(!isset($success) && $result3->rowCount())
							{
								$success = true;
							}
						}
						
						if(!isset($success))
						{
							$return = false;
						}
					}
				}
			}
		}
		
		if($parent_type == 'region')
		{
			$query = 'SELECT cityID FROM db_cities WHERE regionID="' . $parent_id . '"';
			$result = DB::sql($query);
			
			while($fetch = $result->fetch(PDO::FETCH_ASSOC))
			{
				$query = 'SELECT DISTINCT
							sc_pics.city_id,
							sc_pics.cat_id,
							sc_pics.user_id,
							sc_users.social_type,
							sc_users.social_id
						FROM sc_pics
						JOIN sc_users
							ON sc_pics.user_id = sc_users.user_id
						WHERE sc_pics.cat_id="' . $cat_id. '"
							AND sc_pics.city_id="' . $fetch['cityID'] . '"';
				
				$result2 = DB::sql($query);
				
				if($result2->rowCount())
				{
					$return[$fetch['cityID']] = $result2;
				}
				else{
					$query = 'SELECT cat_id FROM db_cat WHERE parent="' . $cat_id . '"';
					$result2 = DB::sql($query);
					
					if($result2->rowCount())
					{
						while($fetch2 = $result2->fetch(PDO::FETCH_ASSOC))
						{
							$query = 'SELECT DISTINCT sc_pics.city_id, sc_pics.cat_id, sc_pics.city_id, sc_pics.user_id, sc_users.social_type, sc_users.social_id FROM sc_pics JOIN sc_users ON sc_pics.user_id = sc_users.user_id WHERE sc_pics.cat_id="' . $fetch2['cat_id'] . '" AND sc_pics.city_id = "' . $fetch['cityID'] . '"';
							$result3 = DB::sql($query);
							$return[$fetch['cityID']][$fetch2['cat_id']] = $result3->rowCount() ? $result3 : false;
							
							if(!isset($success) && $result3->rowCount())
							{
								$success = true;
							}
						}
						
						if(!isset($success))
						{
							$return = false;
						}
					}
				}
			}
		}
		return $return;
	}
	
	# Gets all pics with the same city_id and user_id value.
	public static function fetchAlbumPics($user_id, $city_id, $cat_id)
	{
		if($cat_id == 0 && $user_id != 0 && $city_id != 0){
			$query = 
			'SELECT sc_pics.pic_id, sc_pics.fav_count, sc_pics.view_count, sc_pics.description, sc_pics.src, sc_pics.city_id, sc_pics.user_id, sc_users.fullname, sc_users.nav_user_pic FROM sc_pics 
			JOIN sc_users ON sc_pics.user_id = sc_users.user_id
			WHERE sc_pics.user_id="' . $user_id . '" AND sc_pics.city_id="' . $city_id . '"';
		}
		if($cat_id != 0 && $city_id != 0 && $user_id != 0){
			$query = 
			'SELECT sc_pics.pic_id, sc_pics.fav_count, sc_pics.view_count, sc_pics.description, sc_pics.src, sc_pics.city_id, sc_pics.user_id, sc_users.fullname, sc_users.nav_user_pic FROM sc_pics 
			JOIN sc_users ON sc_pics.user_id = sc_users.user_id
			WHERE sc_pics.user_id="' . $user_id . '" AND sc_pics.city_id="' . $city_id . '" AND sc_pics.cat_id="' . $cat_id . '"';
		}
		
		if($cat_id != 0 && $city_id == 0 && $user_id != 0){
			$query = 
			'SELECT sc_pics.pic_id, sc_pics.fav_count, sc_pics.view_count, sc_pics.description, sc_pics.src, sc_pics.city_id, sc_pics.user_id, sc_users.fullname, sc_users.nav_user_pic FROM sc_pics 
			JOIN sc_users ON sc_pics.user_id = sc_users.user_id
			WHERE sc_pics.user_id="' . $user_id . '" AND sc_pics.cat_id="' . $cat_id . '"';
		}
		$result = DB::sql($query);
		$return = $result->rowCount() ? $result : false;
		return $return;
	}
	
	# Obvious.
	public static function exists($pic_id)
	{
		$query = 'SELECT pic_id FROM sc_pics WHERE pic_id="' . $pic_id . '"';
		$result = DB::sql($query);
		$return = $result->rowCount() ? $result : false;
		return $return;
	}
	
	# Obvious.
	public static function ownedByUser($pic_id, $user_id)
	{
		$query = 'SELECT pic_id FROM sc_pics WHERE pic_id="' . $pic_id . '" AND user_id="' . $user_id . '"';
		$result = DB::sql($query);
		$return = $result->rowCount() ? true : false;
		return $return;
	}
	
	# Obvious.
	public static function delete($pic_id)
	{
		$result = DB::query(array('user_id', 'src'), 'sc_pics', array('pic_id' => $pic_id));
		
		while($fetch = $result->fetch(PDO::FETCH_ASSOC))
		{
			$query = 'DELETE FROM sc_pics WHERE pic_id="' . $pic_id . '"';
			$result = DB::sql($query);
			$return = $result !== false ? true : false;
			
			if($return == true)
			{
				$success = unlink(Conf::$dir['pics'] . $fetch['user_id'] . $fetch['src']);
				$success = unlink(Conf::$dir['thumbs'] . $fetch['user_id'] . $fetch['src']);
			}
			
			return $return;
		}
	}
	
	# Uploads title and description for a picture.
	public static function uploadInfo($pic_id, $desc)
	{
		$query = 'UPDATE sc_pics SET description="' . strip_tags($desc) . '" WHERE pic_id="' . $pic_id . '"';
		$result = DB::sql($query);
		$return = $result ? true : false;
		return $return;
	}
	
	# Obvious.
	public static function getDescription($pic_id, $limit = '')
	{
		$query = 'SELECT description FROM sc_pics WHERE pic_id="' . $pic_id . '"';
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			while($fetch = $result->fetch(PDO::FETCH_ASSOC))
			{
				if($limit === '')
				{
					return $fetch['description'];
				}
				else
				{
					return limitString($fetch['description'], $limit, '...');
				}
			}
		}
		else
		{
			return false;
		}
	}
	
	# Returns random pics. (onscroll, onload effect)
	public static function getInfiniteScrollData($limit, $last_id)
	{
		$ext = $last_id == 0 ? '' : 'WHERE pic_id < ' . $last_id ;
		$query = 'SELECT
					sc_pics.pic_id,
					sc_pics.fav_count,
					sc_pics.view_count,
					sc_pics.src,
					sc_pics.user_id,
					sc_pics.cat_id,
					sc_pics.description,
					sc_pics.city_id,
					sc_users.fullname,
					sc_users.nav_user_pic
				FROM sc_pics
				JOIN sc_users
					ON sc_pics.user_id = sc_users.user_id ' . $ext . ' 
				AND sc_pics.homepage="1"
				ORDER BY pic_id DESC LIMIT ' . $limit;
				
		$result = DB::sql($query);
		
		if($result == true)
		{
			if($result->rowCount())
			{
				return $result;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	# Returns most data about the requested pic. Parameter : pic id
	public static function getAllPicRelatedData($pic_id)
	{
		$query = 'SELECT
					sc_pics.pic_id,
					sc_pics.description,
					db_cities.cityName,
					sc_pics.src,
					sc_pics.cat_id,
					sc_pics.city_id,
					sc_users.user_id,
					sc_users.fullname,
					sc_users.nav_user_pic
				FROM sc_pics
				JOIN sc_users
					ON sc_pics.user_id=sc_users.user_id
				JOIN db_cities
					ON sc_pics.city_id=db_cities.cityID
				WHERE pic_id="' .  $pic_id . '"';
		
		$result = DB::sql($query);
		$return = $result->rowCount() ? $result->fetch(PDO::FETCH_ASSOC) : false;
		
		return $return;
	}
	
	
	# Returns a result object.
	public static function fetchPicComments($pic_id)
	{
		$query = 'SELECT
						sc_pic_comments.comm_id,
						sc_pic_comments.content,
						sc_pic_comments.user_id,
						sc_users.fullname,
						sc_users.nav_user_pic
					FROM sc_pic_comments
					JOIN sc_users
						ON sc_pic_comments.user_id=sc_users.user_id
					WHERE pic_id="' . $pic_id . '" 
					ORDER BY comm_id DESC';
					
		$result = DB::sql($query);
		$return = $result->rowCount() ? $result : false;
		
		return $return;
	}
	
	# Inserts a comment.
	public static function postComment($pic_id, $user_id, $content)
	{
		# Get picture owner.
		$ownerID = self::getOwner($pic_id);
		
		$result = DB::insert(array('pic_id' => $pic_id, 'content' => $content, 'user_id' => $user_id ), 'sc_pic_comments');
		$return = $result !== false ? true : $query;
		
		if($return === true){
		
			$lID = DB::$db->lastInsertId();
			
			# If somebody who DOES NOT OWN the picture commented, post a notification to the owner.
			if($ownerID !== $user_id)
			{
				Notifications::setNotification(array('type' => 0, 'time' => 'NOW()', 'subject_id' => $pic_id, 'alt_id' => $lID, 'user_id' => $ownerID, 'user_poster_id' => $user_id));
			}
			
			$result = self::getCommentBox($lID);
			return $result;
		}
		else
		{
			return false;
		}
		
	}
	
	public static function getCommentContent($id) # IN CONSTRUCTION
	{
		$result = DB::query(array('content'), 'sc_pic_comments', array('comm_id' => $id));
	}
	
	# Gets a comment box based on comment id.
	private static function getCommentBox($id){
	
		$result = DB::sql('SELECT 
					sc_pic_comments.comm_id,
					sc_users.fullname,
					sc_users.nav_user_pic,
					sc_users.user_id
				FROM sc_pic_comments 
				JOIN sc_users 
					ON sc_pic_comments.user_id = sc_users.user_id
				WHERE comm_id="' . $id . '"');
				
		$return = $result->rowCount() ? $result->fetch(PDO::FETCH_ASSOC) : false;
		return $return;
	}
	
	public static function deleteComment($comm_id)
	{
		$query = 'DELETE FROM sc_pic_comments WHERE comm_id="' . $comm_id . '"';
		
		$result = DB::sql($query);
		$return = $result ? true : false;
		
		return $return;
	}
	
	public static function isCommentOwner($user_id, $comm_id)
	{
		$query = 'SELECT user_id FROM sc_pic_comments WHERE comm_id="' . $comm_id . '"';
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			while($fetch = $result->fetch(PDO::FETCH_ASSOC))
			{
				$return = $fetch['user_id'] == $user_id ? true: false;
				return $return;
			}
		}
		else
		{
			return false;
		}
	}
	
	# Returns pic owner.
	public static function getOwner($pic_id)
	{
		$query = 'SELECT user_id FROM sc_pics WHERE pic_id="' . $pic_id. '"';
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			$fetch = $result->fetch(PDO::FETCH_ASSOC);
			return $fetch['user_id'];
		}else{
			return false;
		}
	}
	
	public static function getSrc($id)
	{
		$query = 'SELECT sc_pics.user_id, sc_pics.pic_id, sc_pics.src FROM sc_pics WHERE sc_pics.pic_id="' . $id . '"';
		
		$result = DB::sql($query);
		$fetch = $result->fetch(PDO::FETCH_ASSOC);
		
		if(isset($fetch['src']) && isset($fetch['user_id']) && isset($fetch['pic_id']))
		{
			$return['src'] = Conf::$src['pics'] . $fetch['user_id'] . $fetch['src'];
			$return['id'] = $fetch['pic_id'];
			return $return;
		}
		else
		{
			return false;
		}
	}
	
	public static function getUserAndPhotoInfo($id)
	{
		$query = 'SELECT sc_pics.pic_id, sc_pics.description, sc_pics.cat_id, sc_pics.city_id, sc_pics.user_id, sc_users.fullname, sc_users.nav_user_pic FROM sc_pics 
		JOIN sc_users ON sc_users.user_id=sc_pics.user_id
		WHERE sc_pics.pic_id="' . $id . '"';
		
		$result = DB::sql($query);
		$fetch = $result->fetch(PDO::FETCH_ASSOC);
		
		if($result->rowCount())
		{
			$return['pic_id'] = $fetch['pic_id'];
			$return['cat_id'] = $fetch['cat_id'];
			$return['city_id'] = $fetch['city_id'];
			$return['user_url'] = Conf::$page['profile_view'] . $fetch['user_id'];
			$return['username'] = $fetch['fullname'];
			$return['nav_user_pic'] = $fetch['nav_user_pic'];
			$ext = $fetch['description'] !== '' ? '"' : '';
			$return['description'] = $ext . $fetch['description'] . $ext;
			return $return;
		}
		else
		{
			return false;
		}
	}
	
	# Adds social titles and descriptions to the array of pic details.
	public static function addSocialData($data, $location)
	{
		$data['link'] = Conf::$page['photo_view'] . $data['pic_id'];
		$data['title'] = 'Photo by ' . $data['fullname'] . ', ' . $location;
		$data['image'] = Conf::$src['pics'] . $data['user_id'] . $data['src'];
		$data['caption'] = '';
		$data['description'] = $data['description'];
		
		return $data;
	}
	
	// NOT USED
	public static function updateViewCount($id)
	{
		$query = 'UPDATE sc_pics SET view_count = view_count + 1 WHERE pic_id="' . $id . '"';
		
		$result = DB::sql($query);
		$return = $result ? true : false;
		
		return $return;
	}
	
}