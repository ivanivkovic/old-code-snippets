<?php 

class Controller extends AjaxController{

	public function index()
	{
		# Invalid method, invalid request message.
		echo '<div id=\'warning\'>' . $this->registry->template->loadString('err_default_error') . '</div>';
	}
	
	# Controller for the categories popup window.
	public function search_criteria()
	{
		if(Security::checkNumNotNull($this->criteria) !== false)
		{
			$this->registry->template->category = Categories::fetchCatName($this->criteria);
			
			# If the set main category has children, load the children to the template.
			if(Categories::fetchCategories($this->criteria) !== false){
				$this->registry->template->categories = Categories::fetchCategories($this->criteria);
			}
			
			$this->registry->template->criteria = $this->criteria;
			$this->registry->template->user = $this->registry->user;
			
			$this->registry->template->countries = WorldDatabase::fetchAllCountries();
			$this->registry->template->loadTemplate(__FUNCTION__);
		}else{
			echo '<div id=\'warning\'>' . $this->registry->template->loadString('err_default_error') . '</div>';
		}
	}
	
	# Controller for the country/region/city selection in the popup forms (categories, upload..).
	public function world_select_update()
	{
		$criteria = $this->criteria;
		$criteria2 = $this->criteria2;
		
		if(Security::checkNumNotNull($criteria2) != false)
		{
			if(is_numeric($criteria2)){
				# Looking for a country child. (Region or city)
				if($criteria !== 'city'){
					$data = WorldDatabase::getChild($criteria2, $criteria);
				}
				$this->registry->template->type = $data[0];
				$this->registry->template->data = $data[1];
				$this->registry->template->loadTemplate(__FUNCTION__);
			}
		}		
	}
	
	# Controller for the upload form in the popup.
	public function upload_form()
	{
		if($this->registry->user->logged_in){
		
			$categories = Categories::fetchCategories($this->criteria);
			
			if($categories !== false){
				$this->registry->template->categories = $categories;
			}
			
			$this->registry->template->criteria = $this->criteria;
			$this->registry->template->criteria2 = $this->criteria2;
			
			$this->registry->template->user = $this->registry->user;
			
			$this->registry->template->countries = WorldDatabase::fetchAllCountries();
			$this->registry->template->loadTemplate(__FUNCTION__);
			
		}else{
			echo '<div id="warning">' . $this->registry->template->loadString('err_uploader_not_logged_in') . '</div>';
		}
	}
	
	# Controller for AJAX category shuffle.
	public function dyn_categories()
	{
	
		if(Security::checkNumNotNull($this->criteria) != false)
		{
			$result = Categories::fetchCategories($this->criteria);
			
			if($result !== false)
			{
				$this->registry->template->categories = $result;
				$this->registry->template->loadTemplate(__FUNCTION__);
			}
		}
		
	}
	
	# Controller for all photo options (delete, edit, report 'n stuff)
	public function photo_options()
	{
		$action = $this->criteria;
		
		if(!isset($_POST['id']))
		{
			if(isset($this->criteria2))
			{
				$pic_id = $this->criteria2;
			}
		}
		else
		{
			$pic_id = $_POST['id'];
		}
		
		if(isset($pic_id) && Security::checkNumNotNull($pic_id) && Picture::exists($pic_id))
		{
			switch($action)
			{
				case 'delete':
				
					if(Picture::ownedByUser($pic_id, $this->registry->user->id))
					{
						$result = Picture::delete($pic_id);
						
						if($result === true)
						{
							echo 'success';
						}
						else
						{
							echo $this->registry->template->loadString('err_could_not_delete');
						}
					}
					else
					{
						echo $this->registry->template->loadString('error_you_dont_own');
					}
				break;
				
				# When calling the edit function, this loads the edit popup.
				case 'edit':
					if(Picture::ownedByUser($pic_id, $this->registry->user->id))
					{
						$this->registry->template->pic_id = $pic_id;
						$this->registry->template->description = Picture::getDescription($pic_id);
						$this->registry->template->loadTemplate('modify_photo_info');
					}
					else
					{
						echo $this->registry->template->loadString('error_you_dont_own');
					}
				break;
				
				# Updates photo info.
				case 'edit_info':
					if(Picture::ownedByUser($pic_id, $this->registry->user->id))
					{
						$desc = Security::secureTextArea($_POST['description']);
						
						if(Picture::uploadInfo($pic_id, $desc))
						{
							echo 'success';
						}
						else
						{
							echo $this->registry->template->loadString('err_edit_failed');
						}
					}
					else
					{
						echo $this->registry->template->loadString('error_you_dont_own');
					}
				break;
			}
		}
		else
		{
			echo $this->registry->template->loadString('err_pic_does_not_exist');
		}
	}
	
	public function infinite_scroll()
	{
		header("Content-Type: application/json");
		
		$page = $this->criteria;
		
		switch($page)
		{
			case 'index':
			$pic_id = $this->criteria2;
			
			if(Picture::exists($pic_id))
			{
				$items = Picture::getInfiniteScrollData(AJAX_LOAD_ITEM_LIMIT, $pic_id);
				if($items !== false)
				{
					$c = 0;
					
					while($fetch = $items->fetch(PDO::FETCH_ASSOC))
					{
						ob_start();
							include(Conf::$dir['widgets'] . 'naked_masonry_box.php');
					
						$data[$c]['box'] = ob_get_clean();
						$data[$c]['id'] = $fetch['pic_id'];
							
						++$c;
					}
					
				}
				else
				{
					$data = array(
						'error' => 'failed'
					);
				}
			}
			else
			{
				$data = array(
					'error' => 'unknown_id'
				);
			}
			
			break;
			
			
			case 'search':
			
			$latest_item_count = $this->criteria2;
			$keyword = $this->criteria3;
			
			$limit = AJAX_LOAD_ITEM_LIMIT;
			
			$items = Search::searchByKeywords($keyword, "$latest_item_count, $limit");
			if($items !== false)
			{
				
				$c = 0;
					
				while($fetch = $items->fetch(PDO::FETCH_ASSOC))
				{
					switch($fetch['type'])
					{
						default:
							$mode = 'search_' . $fetch['type'];
						break;
					}
					
					ob_start();
					
						include(Conf::$dir['widgets'] . 'naked_masonry_box.php');
				
					$data[$c]['box'] = ob_get_clean();
					
					if(isset($fetch['id']) && $fetch['type'] !== 'user')
					{
						$data[$c]['id'] = $fetch['id'];
					}
					++$c;
				}
				
			}else{
				$data = array(
					'error' => 'failed'
				);
			}
						
			break;
			
			
			default:
				$data = array(
					'error' => 'unknown_page'
				);
			break;
		}
		
		echo json_encode($data);
		
	}
	
	public function photo_viewer()
	{
		$pic_id = $this->criteria;
		
		if(Picture::exists($pic_id))
		{
			$data = Picture::getAllPicRelatedData($pic_id);
			$this->registry->template->data = $data; # FOR PHOTO VIEWER
			$this->registry->template->title = $data['description'] != '' ? $data['description'] : $data['fullname'] . ' ' . $this->registry->template->loadString('conj_in') . ' ' . $data['cityName'];
			$this->registry->template->loadTemplate(__FUNCTION__);
		}
		else
		{
			echo '<script>alert("' . $this->registry->template->loadString('err_pic_does_not_exist') . '");</script>';
		}
		
	}
	
	# Retrieves ajax data and posts a comment.
	public function post_comment()
	{
		$template = $this->registry->template;
		header("Content-Type: application/json");
		
		$pic_id = isset($_POST['pic_id']) ? $_POST['pic_id'] : '';
		$content = isset($_POST['content']) ? $_POST['content'] : '';
		
		if($this->registry->user->logged_in)
		{
			if(Picture::exists($pic_id))
			{
				$comment_info = Picture::postComment($pic_id, $this->registry->user->id, $content);
				if($comment_info != false)
				{
					$data['fullname'] = $comment_info['fullname'];
					$data['user_id'] = $comment_info['user_id'];
					$data['comm_id'] = $comment_info['comm_id'];
					$data['nav_user_pic'] = $comment_info['nav_user_pic'];
				}
				else
				{
					$data = array('error' => $template->loadString('err_could_not_upload'));
				}			
			}else
			{
				$data = array('error' => $template->loadString('err_pic_does_not_exist'));
			}
		}else
		{
			$data = array('error' => $template->loadString('err_you_are_not_logged_in'));
		}
		echo json_encode($data);
	}
	
	# Deletes a comment.
	public function del_comment()
	{
		
		header("Content-Type: application/json");
		
		$comm_id = isset($_POST['comm_id']) ? $_POST['comm_id'] : '';
		
		if($this->registry->user->logged_in && Picture::isCommentOwner($this->registry->user->id, $comm_id))
		{
			$success = Picture::deleteComment($comm_id);
			$data = $success === true ? array('success' => true) : array('error' => 'failed');
		}
		
		echo json_encode($data);
	}
	
	# Toggles photo favorites.
	public function togglePhotoFavorites()
	{
		
		$user = $this->registry->user;
		
		if($user->logged_in)
		{
			$pic_id = $this->criteria;
			$current_state = $this->criteria2;
			
			if(Security::checkNumNotNull($pic_id) !== false && Picture::ownedByUser($pic_id, $user->id) === false)
			{
				if($current_state !== 'false' && $current_state !== 'true')
				{
					echo $this->registry->template->loadString('err_alert_default_error');
				}
				else
				{
					if($current_state === 'false')
					{
						$success = PhotoFavorites::addToFavorites($user->id, $pic_id);
						
						if($success === true)
						{
							echo 'success_added';
							PhotoFavorites::incrementFavoritesCount($pic_id);
						}
						else
						{
							echo $this->registry->template->loadString('err_alert_default_error');
						}
					}else
					{
						$success = PhotoFavorites::removeFromFavorites($user->id, $pic_id);
						
						if($success === true)
						{
							echo 'success_removed';
							PhotoFavorites::decrementFavoritesCount($pic_id);
						}
						else
						{
							echo $this->registry->template->loadString('err_alert_default_error');
						}
					}
					
				}
			}
			else
			{
				echo $this->registry->template->loadString('err_alert_default_error');
			}
		}
		else
		{
			echo $this->registry->template->loadString('err_you_are_not_logged_in');
		}
		
	}
	
	public function update_photo()
	{
	
		header("Content-Type: application/json");
		
		$pic_id = $this->criteria;
		
		$source = Picture::getSrc($pic_id);
		
		echo json_encode($source);
	}
	
	# Updates photo info for the photo viewer.
	public function update_info()
	{	
		header("Content-Type: application/json");
		
		$pic_id = $this->criteria;
		$info = Picture::getUserAndPhotoInfo($pic_id);
		
		if($info !== false)
		{
			$info['location'] 		= WorldDatabase::getCityAndParent($info['city_id'], ', ');
			$info['category'] 		= Categories::getCategoryAndParent($info['cat_id'], ', ');
			$info['wiki'] 			= Conf::$url['wikiquery'] . WorldDatabase::fetchName($info['city_id'], 'city');
			$info['location_link'] 	= Conf::$page['search_categories'] . $info['city_id'];
			$info['category_link'] 	= Conf::$page['search_categories'] . '0/' . $info['cat_id'];
		}
		else
		{
			$info['error'] = 'could_not_fetch';
		}
		
		echo json_encode($info);
	}
	
	# Updates photo likes.
	public function update_likes()
	{
		$pic_id = $this->criteria;
		
		if(Picture::exists($pic_id))
		{
			$data = Picture::getAllPicRelatedData($pic_id);
			$location = WorldDatabase::getCityAndParent($data['city_id'], ', ');
			
			$data = Picture::addSocialData($data, $location);
			
			include(Conf::$dir['widgets'] . 'photo_likes_ads.php');
		}
	}
	
	# Updates photo comments.
	public function update_comments()
	{
		$pic_id = $this->criteria;
		
		if(Picture::exists($pic_id))
		{
			$data['pic_id'] = $pic_id;
			$this->registry->template->data = $data;
			$this->registry->template->loadWidget('photo_comments');
		}

	}
	
	# Updates bottom part of the photo viewer.
	public function update_bottom_part()
	{
		$pic_id = $this->criteria;
		
		if(Picture::exists($pic_id))
		{
			$data = Picture::getAllPicRelatedData($pic_id);
			$location = WorldDatabase::getCityAndParent($data['city_id'], ', ');
			
			$data = Picture::addSocialData($data, $location);
			$this->registry->template->data = $data;
			$this->registry->template->loadWidget('photo_bottom_part');
		}

	}
	
	# Global update of the site content, periodically updated.
	public function global_update()
	{
		header("Content-Type: application/json");
		
		if($this->registry->user->logged_in)
		{
			# Notifications.
			$data['notifications'] = Notifications::getUnreadNotificationsCount($this->registry->user->id);
		}
		else
		{
			$data['notifications'] = 0;
		}
		
		echo json_encode($data);
	}
	
	# Ajax command for clearing unread notifications.
	public function clear_notif()
	{
		header("Content-Type: application/json");
		
		if($this->registry->user->logged_in)
		{
			$success = Notifications::markAsRead($this->registry->user->id, 'all');
		}
		
		echo json_encode($success);
	}
}