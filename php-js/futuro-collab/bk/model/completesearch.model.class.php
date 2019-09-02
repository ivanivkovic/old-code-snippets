<?php

class modelCompletesearch
{
	// Paginator.
	public static function getNumPages()
	{
		if ( isset($_POST['keyword']) )
		{
			$k = $_POST['keyword'];
			
			$perPage = $_POST['limit'];
			
			if( $k > Conf::$INI['db_min_match_letters'] )
			{
				$query = '
					SELECT * FROM(
						
						SELECT
							"project" AS type,
							COUNT(projectid) AS numpages
						FROM ?project
							WHERE MATCH ( title ) AGAINST ("' . $k . '")
						
						UNION
						
						SELECT
							"user" AS type,
							COUNT(userid) AS numpages
						FROM ?user
							WHERE MATCH ( name, lastname ) AGAINST ("' . $k . '")
					
						UNION
						
						SELECT
							"client" AS type,
							COUNT(clientid) AS numpages
						FROM ?client
							WHERE MATCH ( name ) AGAINST ("' . $k . '")
						
						UNION
						
						SELECT
							"file" AS type,
							COUNT(fileid) AS numpages
						FROM ?file
							WHERE MATCH ( filename ) AGAINST ("' . $k . '")
						
					)
					
					AS items
				';
			}
			else
			{
				$query = '
					SELECT * FROM(
						
						SELECT
							"project" AS type,
							COUNT(projectid) AS numpages
						FROM ?project
							WHERE title LIKE"%' . $k . '%"
						
						UNION
						
						SELECT
							"user" AS type,
							COUNT(userid) AS numpages
						FROM ?user
							WHERE CONCAT(name, " ", lastname) LIKE "%' . $k . '%"
						
						UNION
						
						SELECT
							"client" AS type,
							COUNT(clientid) AS numpages
						FROM ?client
							WHERE name LIKE "%' . $k . '%"
						
						UNION
						
						SELECT
							"file" AS type,
							COUNT(fileid) AS numpages
						FROM ?file
							WHERE filename LIKE "%' . $k . '%"
					
					)
					
					AS items
				';
			}
			
			$query = str_replace("\t", '', $query);
			
			$data = Core::$db->fetchSQL($query);
			
			// Računa koliko podataka ima.
			$counter = 0;
			
			foreach($data as $item)
			{
				$counter += $item['numpages'];
			}
			
			return array( 'numpages' => ceil ( $counter / $perPage ), 'numresults' => $counter);
		}
		else
		{
			return array( 'numpages' => 0, 'numresults' => 0);
		}
	}
	
	public static function getPage()
	{
		$k = $_POST['keyword'];
		
		$limit = $_POST['limit'];
		$offset = $limit * $_POST['page'];
		
		if( $k > Conf::$INI['db_min_match_letters'] )
		{
			$query = '
				SELECT * FROM(
					
					SELECT
						"project" AS type,
						projectid AS itemid,
						title AS field1,
						"" AS field2,
						MATCH ( title ) AGAINST ("' . $k . '") AS score
					FROM ?project
						WHERE MATCH ( title ) AGAINST ("' . $k . '")
					
					UNION
					
					SELECT
						"user" AS type,
						userid AS itemid,
						name AS field1,
						lastname AS field2,
						MATCH(name, lastname) AGAINST ("' . $k . '") AS score
					FROM ?user
						WHERE MATCH ( name, lastname ) AGAINST ("' . $k . '")
				
					UNION
					
					SELECT
						"client" AS type,
						clientid AS itemid,
						name AS field1,
						"" AS field2,
						MATCH(name) AGAINST ("' . $k . '") AS score
					FROM ?client
						WHERE MATCH ( name, lastname ) AGAINST ("' . $k . '")
					
					UNION
					
					SELECT
						"file" AS type,
						fileid AS itemid,
						filename AS field1,
						datafrom AS field2,
						MATCH( filename ) AGAINST ("' . $k . '") AS score
					FROM ?file
						WHERE MATCH ( filename ) AGAINST ("' . $k . '")
				
				)
				AS items
				ORDER BY score ASC
				LIMIT ' . $limit . ' OFFSET ' . $offset ;
		}
		else
		{
			$query = '
				SELECT * FROM(
					
					SELECT
						"project" AS type,
						projectid AS itemid,
						title AS field1,
						"" AS field2
					FROM ?project
						WHERE title LIKE"%' . $k . '%"
					
					UNION
					
					SELECT
						"user" AS type,
						userid AS itemid,
						name AS field1,
						lastname AS field2
					FROM ?user
						WHERE CONCAT(name, " ", lastname) LIKE "%' . $k . '%"
					
					UNION
					
					SELECT
						"client" AS type,
						clientid AS itemid,
						name AS field1,
						"" AS field2
					FROM ?client
						WHERE name LIKE "%' . $k . '%"
					
					UNION
					
					SELECT
						"file" AS type,
						fileid AS itemid,
						filename AS field1,
						datafrom AS field2
					FROM ?file
						WHERE filename LIKE "%' . $k . '%"
				
				)
				
				AS items
			LIMIT ' . $limit . ' OFFSET ' . $offset;
		}
		
		$query = str_replace("\t", '', $query);
		
		$data = Core::$db->fetchSQL($query);
		
		include(Conf::DIR_INCLUDES . 'pagination/search-list.php');
	}
}