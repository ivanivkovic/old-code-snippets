<?php
	
	if(isset($data -> num_rows)){
	
		while($fetch = $data  -> fetch(PDO::FETCH_ASSOC)){
		
			$user_data = ViewedUser::getDBUserData($fetch['user_id'], array('fullname', 'nav_user_pic'));
			$city = isset($city_id) ? $city_id : $fetch['city_id'];
			
			$mode = 'explore_results_by_cat_0';
			include(Conf::$dir['widgets'] . 'masonry_box.php');
			
		}
	}else{
	
		foreach($data as $value){
		
			if($value !== false){
			
				while($fetch = $value -> fetch(PDO::FETCH_ASSOC)){
				
					$city = isset($city_id) ? $city_id : $fetch['city_id'];
					
					$user_data = ViewedUser::getDBUserData($fetch['user_id'], array('fullname', 'nav_user_pic'));
					
					$cat_id = isset($fetch['cat_id']) ? $fetch['cat_id'] : $cat_id; # If it's a child category, select from fetch array, if not then fetch the search criteria category id. (parent)
					
					$mode = 'explore_results_by_cat_1';
					
					include(Conf::$dir['widgets'] . 'masonry_box.php');
				}
			}
		}
	}
?>
<div class="cleaner"></div>