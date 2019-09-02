<?php

class Picture{

	public $db;

	public function __construct($db){
	
		$this -> db = $db;
		
	}
	
	public function Hide($picID)
	{
		$query = 'UPDATE sc_pics SET homepage="0" WHERE pic_id="' . $picID . '"';
		$result = $this->db->query($query);
	}

	public function Unhide($picID)
	{
		$query = 'UPDATE sc_pics SET homepage="1" WHERE pic_id="' . $picID . '"';
		$result = $this->db->query($query);
	}
	
	public function Remove($pic_id)
	{
		$return = false;
		
		$query = 'SELECT
			sc_pics.cat_id,
			db_cat.title,
			sc_pics.user_id,
			sc_users.fullname,
			sc_pics.description,
			sc_pics.src 
			
			FROM sc_pics 
			
		JOIN sc_users ON sc_pics.user_id=sc_users.user_id
		JOIN db_cat ON sc_pics.cat_id=db_cat.cat_id
		
		WHERE pic_id="' . $pic_id . '"';

		$result = $this->db->query($query);

		while($fetch = $result -> fetch_array())
		{
			$query = 'DELETE FROM sc_pics WHERE pic_id="' . $pic_id . '"';
			$result2 = $this -> db -> query($query);
			
			$query = 'DELETE FROM sc_pic_comments WHERE pic_id="' . $pic_id . '"';
			$result3 = $this -> db -> query($query);
			$return = $result2 !== false && $result3 !== false ? true : false;
			
			if($return === true){
			
				$success = unlink(DIR_PICS . $fetch['user_id'] . $fetch['src']);
				$success = unlink(DIR_THUMBS . $fetch['user_id'] . $fetch['src']);
				
				if($success){
					
					$specs = array('Photo ID' => $pic_id,
									'User\'s ID' => $fetch['user_id'],
									'User Name' => $fetch['fullname'],
									'Photo URL' => $fetch['src'],
									'Photo Description' => $fetch['description']
								);
								
					Logs::setLog('Admin ' . ADMIN_NAME . ' ' . ADMIN_ID_TAG .  ' deleted ' . $fetch['fullname'] . '\'s photo.', 'photo administration', $specs);
				}
			}
		}
		
		return $return;
		
	}
	
	public function listCriterias(){
		
		$query = 'SELECT DISTINCT date FROM sc_pics ORDER BY date DESC';
		$result = $this -> db -> query($query);
		
		if($result -> num_rows){
		
			$c = 0;
			
			while($fetch = $result -> fetch_array()){
			
				foreach($fetch as $value){
				
					$items[$c] = array('value' => $value, 'text' => $value, 'count' => true);
					
				}
				
				++$c;
			}
			
			$items[$c] = array('value' => 'all', 'text' => 'All', 'count' => true); ++$c;
			
			$items[$c + 1] = array('value' => 'hidden_homepage', 'text' => 'Hidden On Homepage', 'count' => true); ++$c;
			
			return $items;
			
		}else{
		
			return false;
			
		}
	}
	
	public function getCriteriaCount($criteria){
	
		switch($criteria){
			
			default:
				$query = 'SELECT COUNT(pic_id) AS count FROM sc_pics WHERE date = "' . $criteria. '"';
			break;
			
			case 'hidden_homepage':
				$query = 'SELECT COUNT(pic_id) AS count FROM sc_pics WHERE homepage="0"';
			break;
			
			case 'all':
				$query = 'SELECT COUNT(pic_id) AS count FROM sc_pics';
			break;
			
		}
		
		
		$result = $this -> db -> query($query);
		
		$fetch = $result -> fetch_array();
		
		$return = $result -> num_rows ? $fetch['count'] : false;
		
		
		return $return;
		
	}
	
	public function listEntitiesByCriteria($criteria){
		
		switch($criteria){
		
			default:
				$query = 
				'SELECT sc_pics.fav_count, sc_pics.homepage, sc_pics.pic_id, sc_pics.user_id, sc_pics.src, sc_pics.description, sc_users.fullname, sc_users.profile_pic, db_cat.title AS category_name
				FROM sc_pics
				JOIN sc_users ON sc_pics.user_id = sc_users.user_id
				JOIN db_cat ON sc_pics.cat_id = db_cat.cat_id
				WHERE sc_pics.date="' . $criteria . '"
				';
			break;
			
			case 'all':
				$query = 
				'SELECT sc_pics.fav_count, sc_pics.homepage, sc_pics.pic_id, sc_pics.user_id, sc_pics.src, sc_pics.description, sc_users.fullname, sc_users.profile_pic, db_cat.title AS category_name
				FROM sc_pics
				JOIN sc_users ON sc_pics.user_id = sc_users.user_id
				JOIN db_cat ON sc_pics.cat_id = db_cat.cat_id';
			break;
			
			case 'hidden_homepage':
				$query = 
				'SELECT sc_pics.fav_count, sc_pics.homepage, sc_pics.pic_id, sc_pics.user_id, sc_pics.src, sc_pics.description, sc_users.fullname, sc_users.profile_pic, db_cat.title AS category_name
				FROM sc_pics
				JOIN sc_users ON sc_pics.user_id = sc_users.user_id
				JOIN db_cat ON sc_pics.cat_id = db_cat.cat_id WHERE homepage="0" ';
			break;
		}
		
		
		
		$result = $this -> db -> query($query);
		
		if($result -> num_rows){
			
			$c = 0;
			
			while($fetch = $result -> fetch_array())
			{
				$return[$c]['id']	 		=  $fetch['pic_id'];
				$return[$c]['photo'] 		=  SRC_PICS . $fetch['user_id'] . $fetch['src'];
				$return[$c]['thumb'] 		=  SRC_THUMBS . $fetch['user_id'] . $fetch['src'];
				$return[$c]['righttext'] 	=  $fetch['fullname'];
				$return[$c]['righturl']		=  $fetch['user_id'];
				$return[$c]['rightpic'] 	=  $fetch['profile_pic'];
				$return[$c]['rtext1'] 		=  'Category: ' . $fetch['category_name'];
				$return[$c]['rtext2'] 		=  'Description: ' . $fetch['description'];
				$return[$c]['title'] 		= $fetch['description'];
				$return[$c]['rtext3'] 		=  'Fav Count: ' . $fetch['fav_count'];
				$return[$c]['rtext4'] 		=  'Shown on homepage: ' . ($fetch['homepage'] == 1 ? 'Yes' : 'No');
				
				++$c;
			}
			
			return $return;
			
		}else{
		
			return false;
			
		}
		
	}
	
}

/*


$query = '';
$result = $this -> db -> query($query);
$return = $result -> num_rows ? $result : false;
return $return;



*/