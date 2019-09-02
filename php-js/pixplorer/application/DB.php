<?php

class DB{
	
	public static $db;
	
	/**
	* @return void
	*/
	public static function connect($server, $user, $pass, $db)
	{
		self::$db = new PDO('mysql:host=' . $server . ';dbname=' . $db, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));	
	}
	
	/**
	* @return void
	*/
	public static function disconnect()
	{
		self::$db = null;
	}
	
	
	/**
	* Jednostavna sql metoda. Pokreæe SQL query i vraæa PDO rezultat.
	*
	* @param string $query
	* @return object|bool PDO result ili false ako se nismo spojili na bazu.
	*/
	public static function sql($query)
	{
		if(!is_object(self::$db))
		{
			return false;
		}
		else
		{
			$result = self::$db->prepare($query);
			$result->execute();
			return $result;
		}
	}
	
	/**
	* Insert metoda.
	*
	* @param array $info array/polje kolona i vrijednosti. Primjer : array('user_id' => 34391)
	* @param string $table
	*
	* @return bool uspjeh
	*/
	public static function insert($info, $table)
	{
		if(is_array($info) && !empty($info))
		{
			$query = 'INSERT INTO ' . $table . ' SET ';
			
			foreach($info as $column => $value)
			{
				if(isset($fdone)){ $query .= ','; }
				
				if(strpos($value, '(') && strpos($value, ')'))
				{
					$query .= $column . ' = ' . $value . ' ';
				}
				else
				{
					$query .= $column . ' = "' . $value . '" ';
				}
				
				$fdone = true; # Za prvi interval nemoj dodati zareze u queryu.
			}
			
			return self::sql($query) !== false ? true : false; # Ako query ne uspije vraæamo false.
		}
		else
		{
			return false;
		}
		
	}
	
	/* 
	* Jednostavni update.
	*
	* @param $col array ime kolona za update i vrijednosti
	* @param $table string ime tablice
	* @param $where string uvjeti za update
	*
	* @return bool uspjeh
	*/
	public static function update($col, $table, $where = ''){
		
		if(is_array($col) && !empty($col)){
		
			$query = 'UPDATE ' . $table . ' SET ';
			
			foreach($col as $column => $value){
				
				if(isset($fdone)){ $query .= ','; }
				
				/** 
				* This if statement enables query within query.
				*/
				
				if(strpos($value, '(') && strpos($value, ')'))
				{
					$query .= $column . ' = ' . $value . ' ';
				}
				else
				{
					$query .= $column . ' = "' . $value . '" ';			
				}
				
				$fdone = true; # Za prvi interval nemoj dodati zareze u queryu.
				
			}
			
			if(is_array($where)){
			
				$query .= ' WHERE ';
				
				foreach($where as $column => $value)
				{
					if(isset($fdone2)){ $query .= ' AND '; }
					
					# This if statement enables query within query.
					if(strpos($value, '(') && strpos($value, ')'))
					{
						$query .= $column . ' = ' . $value . ' ';
					}
					else
					{
						$query .= $column . ' = "' . $value . '" ';
					}
					
					$fdone2 = true; # First interval of the loop done, ready to put commas above.
				}
			}
			return self::sql($query) !== false ? true : false; # Ako query ne uspije vraæamo false.
		}
		else
		{
			return false;
		}
		
	}
	
	/**
	* Jednostavna uni-sex query metoda, nije gotova vjerojatno. Napisati join u metodu?
	* 
	* @param $col array vrijednosti i kolona
	*/
	
	public static function query($col, $table, $where = '', $limit = '') # in construction
	{ 
		
		$query = 'SELECT ';
		
		if(is_array($col)){
		
			foreach($col as $key => $value)
			{
				if($key !== 0)
				{
					$query .= ',';
				}
				
				$query .= $value;
			}
		}
		else
		{
			$query .= $col;
		}
		
		$query .= ' FROM ' . $table;
		
		if(is_array($where)){
		
			$query .= ' WHERE ';
			
			$c = 0;
			
			foreach($where as $key => $value)
			{
				if($c !== 0)
				{
					$query .= ' AND ';
				}
			
				$query .= $key . '="' . $value . '"';
				
				++$c;
			}
			
			unset($c);
		}
		
		
		if($limit !== '')
		{
			$query .= ' LIMIT ' . $limit;
		}
		
		return self::sql($query);
	}
	
	public static function fetchOne($col, $table, $where = '')
	{
		$result = self::query($col, $table, $where, 1);
		
		if($result !== false)
		{
			$fetch = $result->fetch(PDO::FETCH_ASSOC);
			return $fetch[$col];
		}
		else
		{
			return false;
		}
	}
	
	public static function fetch($col, $table, $where = '' ){ # in construction
		
		$result = self::query($col, $table, $where);
		
		if($result->rowCount())
		{
			return $result->fetchAll();
		}
		else
		{
			return false;
		}
		
	}
	
}