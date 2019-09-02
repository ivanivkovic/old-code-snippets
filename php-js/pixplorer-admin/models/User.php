<?php

class User{
	
	private $db;
	
	public function __construct($db){
		$this -> db = $db;
	}
	
	public function Ban($id)
	{
	
		$query = 'UPDATE sc_users SET active="0" WHERE user_id="' . $id . '"';
		$this -> db -> query($query);
		$user_name = $this -> getUserFullname($id);
		$specs = array('User ID' => $id, 'User Name' => $user_name);
		Logs::setLog('Admin ' . ADMIN_NAME . ' ' . ADMIN_ID_TAG . ' banned user ' . $user_name . '.', 'user administration', $specs);
		
	}
	
	public function Unban($id)
	{
	
		$query = 'UPDATE sc_users SET active="1" WHERE user_id="' . $id . '"';
		$this -> db -> query($query);
		$user_name = $this -> getUserFullname($id);
		$specs = array('User ID' => $id, 'User Name' => $user_name);
		Logs::setLog('Admin ' . ADMIN_NAME . ' ' . ADMIN_ID_TAG . ' unbanned user ' . $user_name . '.', 'user administration', $specs);
		
	}
	
	public function listCriterias(){
		
		$c = 0;
		
		$items[$c] = array('value' => 'all', 'text' => 'All', 'count' => true); ++$c;
		$items[$c] = array('value' => 'facebook', 'text' => 'Facebook Connect', 'count' => true); ++$c;
		$items[$c] = array('value' => 'active', 'text' => 'Active', 'count' => true); ++$c;
		$items[$c] = array('value' => 'inactive', 'text' => 'Inactive', 'count' => true); ++$c;
		
		return $items;
		
	}
	
	public function getCriteriaCount($criteria){
		
		if(!isset($criteria)){
			$criteria = 'all';
		}
		
		if($criteria === 'all'){
			$query = 'SELECT COUNT(user_id) AS count FROM sc_users';
		}
		
		if($criteria === 'facebook'){
			$query = 'SELECT COUNT(user_id) AS count FROM sc_users WHERE social_type="0"';
		}
		
		if($criteria === 'active'){
			$query = 'SELECT COUNT(user_id) AS count FROM sc_users WHERE active="1"';
		}
		
		if($criteria === 'inactive'){
			$query = 'SELECT COUNT(user_id) AS count FROM sc_users WHERE active="0"';
		}
		
		$result = $this -> db -> query($query);
		
		$fetch = $result -> fetch_array();
		
		$return = $result -> num_rows ? $fetch['count'] : false;
		
		return $return;
		
	}
	
	public function listEntitiesByCriteria($criteria){
		
		if(!isset($criteria)){
			$criteria = 'all';
		}
	
		if($criteria === 'all'){
			$query = 
			'SELECT 
				sc_users.user_id AS id,
				sc_users.profile_pic AS photo,
				sc_users.nav_user_pic AS thumb,
				sc_users.fullname AS fullname,
				sc_users.active AS active
			FROM sc_users
			';
		}
		
		if($criteria === 'facebook'){
			$query = 
			'SELECT 
				sc_users.user_id AS id,
				sc_users.profile_pic AS photo,
				sc_users.nav_user_pic AS thumb,
				sc_users.fullname AS fullname,
				sc_users.active AS active
			FROM sc_users
			WHERE social_type="0"
			';
		}
		
		if($criteria === 'active'){
			$query = 
			'SELECT 
				sc_users.user_id AS id,
				sc_users.profile_pic AS photo,
				sc_users.nav_user_pic AS thumb,
				sc_users.fullname AS fullname
			FROM sc_users
			WHERE active="1"
			';
		}
		
		if($criteria === 'inactive'){
			$query = 
			'SELECT 
				sc_users.user_id AS id,
				sc_users.profile_pic AS photo,
				sc_users.nav_user_pic AS thumb,
				sc_users.fullname AS fullname
			FROM sc_users
			WHERE active="0"
			';
		}
		
		
		$result = $this -> db -> query($query);
		
		if($result -> num_rows){
			
			$c = 0;
			
			while($fetch = $result -> fetch_array()){
				
				$return[$c]['id']		=  $fetch['id'];
				$return[$c]['photo'] 	=  $fetch['photo'];
				$return[$c]['thumb']	=  $fetch['thumb'];
				$return[$c]['rtext2'] 	=  'Name: ' . $fetch['fullname'];
				$return[$c]['title'] 	=  $fetch['fullname'];
				
				if(isset($fetch['active'])){
				
					if($fetch['active'] === '0'){
					
						$return[$c]['rtext1'] = 'BANNED USER';
						
					}else if($fetch['active'] === '1'){
					
						$return[$c]['rtext1'] = 'ACTIVE USER';
						
					}
					
				}
				
				++$c;
			}
			
			return $return;
			
		}else{
		
			return false;
			
		}
	
	}
	
	private function getUserFullname($id){
	
		$result = $this -> db -> query('SELECT fullname FROM sc_users WHERE user_id="' . $id . '"');
		
		if($result -> num_rows){
		
			$fetch = $result -> fetch_array();
			return $fetch['fullname'];
			
		}else{
		
			return false;
			
		}
	}
	
}