<?php

class libDB{

	private $db;
	private $preset;
	
	/**
	* @return void
	*/

	public function __construct($config)
	{
		$this->db = new PDO('mysql:host=' . $config['server'] . ';dbname=' . $config['db'], $config['user'], $config['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));	
		$this->preset = $config['preset'] . '_';
	}

	/**
	* @return void
	*/
	
	public function disconnect()
	{
		$this->db = null;
	}
	
	/**
	* 
	* Jednostavna sql metoda. Pokreće SQL query i vraća PDO rezultat.
	*
	* @param string $query
	* @return object|bool PDO result ili false ako se nismo spojili na bazu.
	*
	*/
	
	public function SQL($query)
	{
		if( ! is_object($this->db))
		{
			return false;
		}
		else
		{
			$query = str_replace("\t", ' ', $query);
			$query = preg_replace('/ +\?(?=[a-z]+)/i', ' ' . $this->preset, $query);
			$query = str_replace('\?', '?', $query);
			
			$result = $this->db->prepare($query);
			
			if( ! $result )
			{
				echo $this->db->errorInfo(); return false;
			}
			
			if( ! $result->execute() ){
				echo $query;
			}
			
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
	
	public function insert($info, $table)
	{
		if( is_array($info) && !empty($info) )
		{
			$query = 'INSERT INTO ' . $this->preset . $table . ' SET ';
			
			foreach($info as $column => $value)
			{
				if(isset($fdone)){ $query .= ','; }
				
				if(strpos($value, '(') && strpos($value, ')'))
				{
					$query .= $column . ' = ' . $value;
				}
				else
				{
					$query .= $column . ' = "' . $value . '"';
				}
				
				$fdone = true; # Za prvi interval nemoj dodati zareze u queryu.
			}
			
			//echo $query;
			
			if( $this->SQL($query) !== false) # Ako query ne uspije vraćamo false.
			{
				return $this->getInsertId();
			}
			else
			{
				return false;				
			}
		}
		else
		{
			return false;
		}
	}
	
	/* 
	*
	* Jednostavni update.
	*
	* @param $col array ime kolona za update i vrijednosti
	* @param $table string ime tablice
	* @param $where string uvjeti za update
	*
	* @return bool uspjeh
	* 
	*/
	
	public function update($col, $table, $where = '')
	{
		if( is_array($col) && !empty($col) )
		{
			$query = 'UPDATE ' . $this->preset . $table . ' SET ';
			
			foreach($col as $column => $value)
			{
				if(isset($fdone)){ $query .= ','; }
				
				/** 
				* Ova IF izjava omogućuje query within query u col parametru.
				*/
				if( ! is_array($value) )
				{
					if(strpos($value, '(') && strpos($value, ')'))
					{
						$query .= $column . ' = ' . $value . ' ';
					}
					else
					{
						$query .= $column . ' = "' . $value . '" ';			
					}
				}
				
				$fdone = true; # Za prvi interval nemoj dodati zareze u queryu.
			}
			
			if( is_array( $where ) )
			{
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
					
					$fdone2 = true; # Prvi interval gotov, dodaj zareze gore.
				}
			}
			
			// echo $query;
			
			return $this->SQL($query) !== false ? true : false; # Ako query ne uspije vraćamo false.
		}
		else
		{
			return false;
		}
	}
	
	/**
	* @param $col array vrijednosti i kolona
	*/
	
	public function query($col, $table, $where = array(), $limit = '', $order = array())
	{
		$query = 'SELECT ';
		
		if(is_array($col))
		{
			$c = 0;
			
			foreach($col as $key => $value)
			{
				if($c !== 0)
				{
					$query .= ',';
				}
				
				if( is_numeric($key) )
				{	
					$query .= $value;
				}
				else if( is_string( $key ) )
				{
					$query .= $key . ' AS ' . $value;
				}
				
				++$c;
			}
		}
		else
		{
			$query .= $col;
		}
		
		$query .= ' FROM ' . $this->preset . $table;
		
		if( ! empty( $where ) )
		{
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
		}
	
		if( $limit !== '' )
		{
			$query .= ' LIMIT ' . $limit;
		}
		
		if( ! empty( $order ) )
		{
			$query .= ' ORDER BY ' . key($order) . ' ' . $order[key($order)];
		}

		# echo str_replace('?', 'pos_', $query);

		return $this->SQL($query);
	}
	
	public function fetch($col, $table, $where = array(), $limit = '', $order = array() )
	{
		$result = $this->query($col, $table, $where, $limit, $order);
		
		if( $result->rowCount() )
		{
			$c = 0;
			
			while( $fetch = $result->fetch(PDO::FETCH_ASSOC) )
			{
				$data[$c] = $fetch;
				++$c;
			}
			
			return $data;
		}
		else
		{
			return array();
		}
	}
	
	// Vraća jedan rezultat. Ako se traži samo user id iz jedne tablice vratiti će direktno user id. Ako se traži više rezultata vraća array.
	/*
	* @return bool|array|string
	*/
	public function fetchOne($col, $table, $where = '', $order = array())
	{
		$result = $this->query($col, $table, $where, 1, $order);
		
		if($result !== false)
		{
			$fetch = $result->fetch(PDO::FETCH_ASSOC);
			
			if( ! empty ( $fetch ) && $fetch !== false )
			{
				if( $col === '*' )
				{
					return $fetch;
				}
				else if( is_string($col) && $col !== '*')
				{
					return $fetch[$col];
				}
				else if( is_array($col) )
				{
					foreach( $col as $v )
					{
						$data[$v] = $fetch[$v];
					}
					
					return $data;
				}
			}
			else
			{
				return array();
			}
		}
		else
		{
			return false;
		}
	}
	
	/* 
	* One varijabla na TRUE omogućuje da se vrati samo jedan rezultat.
	*/
	public function fetchSQL($query, $one = false)
	{
		$result = $this->SQL($query);
		
		if($result !== false)
		{
			if( $one === true )
			{
				$data = $result->fetch(PDO::FETCH_ASSOC);
			}
			else
			{
				$c = 0;
				
				$data = array();
				
				while($fetch = $result->fetch(PDO::FETCH_ASSOC))
				{
					$data[$c] = $fetch;
					++$c;
				}
			}
			
			return $data;
		}
		else
		{
			return false;
		}
	}
	
	// Brisanje item-a.
	public function delete($where, $table)
	{
		$query = 'DELETE FROM ?' . $table . ' WHERE ';
		
		if( is_array( $where ) )
		{
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
		}
		
		// echo str_replace('?', $this->preset, $query);
		
		return $this->SQL($query);
	}
	
	public function getInsertId()
	{
		return $this->db->lastInsertId();
	}
}