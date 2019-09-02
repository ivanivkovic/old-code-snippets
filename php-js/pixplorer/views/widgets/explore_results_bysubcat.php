<?php
	
	foreach($data as $result)
	{
		while($fetch = $result -> fetch(PDO::FETCH_ASSOC))
		{
			$user_data = ViewedUser::getDBUserData($fetch['user_id'], array('fullname', 'nav_user_pic'));
			$city = isset($city_id) ? $city_id : $fetch['city_id'];
			
			$mode = 'explore_results_by_subcat';
			include(Conf::$dir['widgets'] . 'masonry_box.php');
		}
	}	
	?>
	<div class="cleaner"></div>