<?php

class Notifications{
	
	# Gets unread user notifications.
	public static function getAllNotifications($user_id){
		
		$result = DB::sql('SELECT 
							sc_notifications.*, sc_users.fullname, sc_users.nav_user_pic
						  FROM sc_notifications
						  JOIN sc_users ON sc_notifications.user_poster_id = sc_users.user_id
						  WHERE sc_notifications.user_id="' . $user_id . '" 
						  ORDER BY id DESC');
	
		if($result->rowCount())
		{
			$c = 0;
			
			while($fetch = $result->fetch(PDO::FETCH_ASSOC))
			{
				$data[$fetch['type']][$c]['subject_id'] = $fetch['subject_id'];
				$data[$fetch['type']][$c]['user_poster_id'] = $fetch['user_poster_id'];
				$data[$fetch['type']][$c]['nav_user_pic'] = $fetch['nav_user_pic'];
				$data[$fetch['type']][$c]['fullname'] = $fetch['fullname'];
				$data[$fetch['type']][$c]['time'] = $fetch['time'];
				$data[$fetch['type']][$c]['viewed'] = $fetch['viewed'];
				$data[$fetch['type']][$c]['alt_id'] = $fetch['alt_id'];
				
				++$c;
			}
			
			$return = isset($data) ? $data : false;
			return $return;
			
		}else{
			return false;
		}
		
	}
	
	# Gets unread notification by user.
	public static function getUnreadNotificationsCount($user_id)
	{
		$result = DB::sql('SELECT COUNT(id) AS count FROM sc_notifications WHERE viewed=\'0\' AND user_id="' . $user_id . '"');
		$fetch = $result->fetch(PDO::FETCH_ASSOC);
		$return = $result->rowCount() == '1' ? $fetch['count'] : false;
		return $return;
	}
	
	# Sets a notification.
	public static function setNotification($info)
	{
		DB::insert($info, 'sc_notifications');
	}
	
	# Clears notifications to be read.
	public static function markAsRead($user_id, $criteria)
	{
		if($criteria === 'all')
		{
			$result = DB::update(array('viewed' => 1), 'sc_notifications', array('user_id' => $user_id, 'viewed' => '0'));
			
			if($result === false)
			{
				return array('success' => 'failed_to_update_notifications');
			}
			else
			{
				return array('success' => 'success');
			}
		}
	}
}