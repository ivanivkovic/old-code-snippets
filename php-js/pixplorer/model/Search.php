<?php

class Search{
	
	public static function searchByKeywords($keyword, $limit = false)
	{
		$limit = $limit !== '' ? ' LIMIT ' . $limit : '';
	/*
	
	SELECT 
		"city" AS type,
		db_cities.cityID AS id,
		db_cities.cityName AS name,
		sc_pics.src AS src,
		sc_pics.user_id AS user_id,
		"" AS fullname,
		"" AS nav_user_pic,
		"" AS city_id,
		MATCH (cityName) AGAINST ("' . $keyword . '") AS rel
	FROM
		db_cities
			LEFT JOIN sc_pics ON db_cities.cityID = sc_pics.city_id
	WHERE
		MATCH (cityName) AGAINST ("' . $keyword . '")
		AND src != ""
	GROUP BY
		ID
UNION

	
	*/
		$query = '
SELECT * FROM(
	SELECT 
		"user" AS type,
		user_id AS id,
		fullname AS name,
		profile_pic AS src,
		"" AS user_id,
		"" AS fullname,
		"" AS nav_user_pic,
		"" AS city_id,
		"" as fav_count,
		"" as view_count,
		MATCH (fullname) AGAINST ("' . $keyword . '") AS rel
	FROM
		sc_users
	WHERE
		MATCH (fullname) AGAINST ("' . $keyword . '")

UNION

	SELECT 
		"img" AS type,
		sc_pics.pic_id AS id,
		sc_pics.description AS name,
		sc_pics.src AS src,
		sc_pics.user_id AS user_id,
		sc_users.fullname AS fullname,
		sc_users.nav_user_pic AS nav_user_pic,
		sc_pics.city_id AS city_id,
		sc_pics.fav_count as fav_count,
		sc_pics.view_count as view_count,
		MATCH (description) AGAINST ("' . $keyword . '") AS rel
	FROM
		sc_pics
			LEFT JOIN sc_users ON sc_pics.user_id = sc_users.user_id
	WHERE
		MATCH (description) AGAINST ("' . $keyword . '")

UNION

	SELECT 
		"city" AS type,
		sc_pics.pic_id AS id,
		sc_pics.description AS name,
		sc_pics.src AS src,
		sc_pics.user_id AS user_id,
		sc_users.fullname AS fullname,
		sc_users.nav_user_pic AS nav_user_pic,
		sc_pics.city_id AS city_id,
		sc_pics.fav_count as fav_count,
		sc_pics.view_count as view_count,
		MATCH (cityName) AGAINST ("' . $keyword . '") AS rel
	FROM
		sc_pics
			LEFT JOIN sc_users ON sc_pics.user_id = sc_users.user_id
			LEFT JOIN db_cities ON sc_pics.city_id = db_cities.cityID
	WHERE
		MATCH (cityName) AGAINST ("' . $keyword . '")

UNION

	SELECT 
		"region" AS type,
		sc_pics.pic_id AS id,
		sc_pics.description AS name,
		sc_pics.src AS src,
		sc_pics.user_id AS user_id,
		sc_users.fullname AS fullname,
		sc_users.nav_user_pic AS nav_user_pic,
		sc_pics.city_id AS city_id,
		sc_pics.fav_count as fav_count,
		sc_pics.view_count as view_count,
		MATCH (regionName) AGAINST ("' . $keyword . '") AS rel
	FROM
		sc_pics
			LEFT JOIN sc_users ON sc_pics.user_id = sc_users.user_id
			LEFT JOIN db_cities ON sc_pics.city_id = db_cities.cityID
			RIGHT JOIN db_regions ON db_regions.regionID = db_cities.regionID
	WHERE
		MATCH (regionName) AGAINST ("' . $keyword . '")

	
UNION

	SELECT 
		"country" AS type,
		sc_pics.pic_id AS id,
		sc_pics.description AS name,
		sc_pics.src AS src,
		sc_pics.user_id AS user_id,
		sc_users.fullname AS fullname,
		sc_users.nav_user_pic AS nav_user_pic,
		sc_pics.city_id AS city_id,
		sc_pics.fav_count as fav_count,
		sc_pics.view_count as view_count,
		MATCH (countryName) AGAINST ("' . $keyword . '") AS rel
	FROM
		sc_pics
			LEFT JOIN sc_users ON sc_pics.user_id = sc_users.user_id
			LEFT JOIN db_cities ON sc_pics.city_id = db_cities.cityID
			RIGHT JOIN db_countries ON db_countries.countryID = db_cities.countryID
	WHERE
		countryName LIKE"%' . $keyword . '%"
		AND pic_id != ""

ORDER BY
	rel DESC
)

AS items
GROUP BY id' . $limit . '';

		$result = DB::sql($query);
		$return = $result->rowCount() ? $result : false;
		return $return;
	}
}