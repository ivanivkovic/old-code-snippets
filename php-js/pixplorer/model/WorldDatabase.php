<?php

# A set of methods for easier region/country handling. Unable to find a simpler database.

class WorldDatabase{

	# Fetch all countries.
	public static function fetchAllCountries()
	{
		$query = 'SELECT countryID, countryName FROM db_countries ORDER BY countryName ASC';
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
	
	# Returns the array of child value and child type. Example: (region, 54) or (city, 6754)
	public static function getChild($id, $type)
	{
		if($type == 'country'){
		
			$query = 'SELECT regionID, regionName FROM db_regions WHERE countryID = "' . $id . '" ORDER BY regionName ASC';
			$result = DB::sql($query);
			
			if($result->rowCount())
			{
				$return[0] = 'region';
				$return[1] = $result;
				
				return $return;
				
			}
			else
			{
				$query = 'SELECT cityID, cityName FROM db_cities WHERE countryID = "' . $id . '" ORDER BY cityName ASC';
				$result = DB::sql($query);
				
				if($result->rowCount())
				{
					$return[0] = 'city';
					$return[1] = $result;
					
					return $return;
					
				}else
				{
					return false;
				}
			}
		}
		
		if($type == 'region')
		{
			$query = 'SELECT cityID, cityName FROM db_cities WHERE regionID = "' . $id . '" ORDER BY cityName ASC';
			$result = DB::sql($query);
			
			if($result->rowCount())
			{
				$return[0] = 'city';
				$return[1] = $result;
				
				return $return;
			}
			else
			{
				return false;
			}
		}
	}
	
	# Checks if exists as an entity.
	public static function exists($type, $id)
	{
		switch($type)
		{
			case 'city':
				$table = 'cities';
			break;
			
			case 'country':
				$table = 'countries';
			break;
			
			case 'region':
				$table = 'regions';
			break;
		}
		
		if(isset($table))
		{
			$query = 'SELECT ' . $type . 'ID FROM db_' . $table . ' WHERE ' . $type . 'ID = "' . $id . '"';
			$result = DB::sql($query);
			
			if($result->rowCount())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}

	# Fetches an entity's name.
	public static function fetchName($id, $type)
	{
		switch($type)
		{
			case 'city':
				$table = 'cities';
			break;
			
			case 'country':
				$table = 'countries';
			break;
			
			case 'region':
				$table = 'regions';
			break;
		}
		
		if(isset($table))
		{
			$query = 'SELECT ' . $type . 'Name FROM db_' . $table . ' WHERE ' . $type . 'ID = "' . $id . '"';
			$result = DB::sql($query);
		}
		
		if(isset($result) && $result->rowCount())
		{
			$fetch = $result->fetch(PDO::FETCH_ASSOC);
			return str_replace('*', '', $fetch[$type . 'Name']);
		}
		else
		{
			return false;
		}
	}
	
	
	# Get parent of a city.
	public static function getParent($city_id)
	{
		
		$query = 'SELECT db_cities.regionID, db_regions.regionName FROM db_cities JOIN db_regions ON db_cities.regionID=db_regions.regionID WHERE cityID = "' . $city_id . '"';
		$result = DB::sql($query);
		
		while($fetch = $result->fetch(PDO::FETCH_ASSOC))
		{
			if($fetch['regionID'] != NULL)
			{
				$info['type'] = 'region';
				$info['name'] = $fetch['regionName'];
				$info['id'] = $fetch['regionID'];
				
				return $info;
			}
		}
		
		$query = 'SELECT db_cities.countryID, db_countries.countryName FROM db_cities JOIN db_countries ON db_cities.countryID=db_countries.countryID WHERE cityID = "' . $city_id .'"';
		$result = DB::sql($query);
		
		while($fetch = $result->fetch(PDO::FETCH_ASSOC))
		{
			if($fetch['countryID'] != NULL)
			{
				$info['type'] = 'country';
				$info['name'] = $fetch['countryName'];
				$info['id'] = $fetch['countryID'];
				
				return $info;
			}
			else
			{
				return false;
			}
		}
	}
	
	# Returns a country under which the region is
	public static function getRegionParent($region_id)
	{
		$query = 'SELECT db_countries.countryID, db_countries.countryName FROM db_regions JOIN db_countries ON db_regions.countryID=db_countries.countryID WHERE regionID = "' . $region_id .'"';
		$result = DB::sql($query);
		
		while($fetch = $result->fetch(PDO::FETCH_ASSOC))
		{
			if($fetch['countryID'] != NULL)
			{
				$info['type'] = 'country';
				$info['name'] = $fetch['countryName'];
				$info['id'] = $fetch['countryID'];
				
				return $info;
			}else
			{
				return false;
			}
		}
	}
	
	
	# Returns a full location of a city. Ex. United States$separatorWashington$separatorSeattle
	public static function getFullLocation($city_id, $separator, $link = false)
	{
		$class = 'class="hover_underline"';
		
		$city_name = $link === true ? '<a ' . $class . ' href="' . Conf::$page['search_keyword'] . urlencode( self::fetchName($city_id, 'city') ) . '">' . self::fetchName($city_id, 'city') . '</a>' : self::fetchName($city_id, 'city');
		
		$full_name = $city_name . $separator;
		
		$parent = self::getParent($city_id);
		
		$full_name .= $link === true ? '<a ' . $class . ' href="' . Conf::$page['search_keyword'] . urlencode( $parent['name'] ) . '">' . $parent['name'] . '</a>' : $parent['name'];
		
		if($parent['type'] === 'region')
		{
			$parent = self::getRegionParent($parent['id']);
			
			if($parent !== false)
			{
				$full_name .= $link === true ? $separator . '<a ' . $class . ' href="' . Conf::$page['search_keyword'] . urlencode( $parent['name'] ) . '">' . $parent['name'] . '</a>' : $separator . $parent['name'];
			}
		}
		
		return clearAsterisk($full_name);
	}
	
	
	# Returns "London, England" instead of "London, England, United Kindom"
	public static function getCityAndParent($city_id, $separator)
	{
		$city_name = self::fetchName($city_id, 'city');
		
		$full_name = $city_name;
		
		$parent = self::getParent($city_id);
		
		$full_name .=  $separator . $parent['name'];
		
		return clearAsterisk($full_name);
	}
	
}