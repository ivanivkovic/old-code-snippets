<?php

# A set of methods that drain data for a specific user, but the object is used only to retrieve data, not to store data.

class ViewedUser{
	
	# Get user data from social networks directly, in general.
	# Parameters > user_id, array of items requested.
	
	public static function getDBUserData($id, $array)
	{
		$query = 'SELECT ';
		
		foreach($array as $key => $item)
		{
			$query .= $item;
			if($key != (count($array) -1))
			{
				$query .= ',';
			}
		}
		
		if($id != 0)
		{
			$query .= ' FROM sc_users WHERE user_id = "' . $id . '"';
			$result = DB::sql($query);
			
			if($result->rowCount())
			{
				$fetch = $result->fetch(PDO::FETCH_ASSOC);
				return $fetch;
				# Return fetched data.
			}else{
				return false;
			}
		}
		
		
	}
	# If the social user (i.e. facebook user) ever was on this site, has records in DB.
	public static function findInDB($id)
	{
		$query = 'SELECT user_id FROM sc_users WHERE user_id = "' . $id . '"';
		$result = DB::sql($query);
		
		if($result->rowCount())
		{
			$fetch = $result->fetch(PDO::FETCH_ASSOC);
			return $fetch['user_id']; # Returns user id.
		}else{
			return false; # Not found in DB.
		}
	}
	
}