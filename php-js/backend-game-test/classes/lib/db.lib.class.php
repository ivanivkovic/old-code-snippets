<?php

// Klasa za rad s mysql bazom podataka
class libDB{

	private $db;
	private $preset = '';

	// Spajanje s bazom podataka.
	public function __construct($config)
	{
		$this->db = new PDO('mysql:host=' . $config['server'] . ';dbname=' . $config['db'], $config['user'], $config['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));	
		
		// Ako je preset određen, učitaj ga u objekt.
		if( strlen( $config['preset'] ) )
		{
			$this->preset = $config['preset'] . '_';
		}
	}
	
	public function disconnect()
	{
		$this->db = null;
	}
	
	
	// Jednostavna sql metoda. Pokreće SQL query i vraća PDO rezultat.
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
			
			// Ako ima greške u prepareu izbaci grešku.
			if( ! $result )
			{
				echo $this->db->errorInfo(); return false;
			}
			
			// Pokreni query.
			$result->execute();
			
			return $result;
		}
	}
	
	// Insert metoda.
	// Info je array informacija za kolone.
	// Vraća vrijednost false ili ID unešenog rowa.
	
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
			
			# echo $query;
			
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
	
	// Jednostavni update.
	// Updatea tablice. $col je array vrijednosti i polja koje treba izmijeniti.
	
	public function update($col, $table, $where = '')
	{
		if( is_array($col) && !empty($col) )
		{
			$query = 'UPDATE ' . $this->preset . $table . ' SET ';
			
			foreach($col as $column => $value)
			{
				if(isset($fdone)){ $query .= ','; }
				
				// Ova IF izjava omogućuje query within query u col parametru.
				
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
			
			if( is_array( $where ) )
			{
				$query .= ' WHERE ';
				
				foreach($where as $column => $value)
				{
					if(isset($fdone2)){ $query .= ' AND '; }
					
					// Query unutar querya.
					if(strpos($value, '(') && strpos($value, ')'))
					{
						$query .= $column . ' = ' . $value . ' ';
					}
					else
					{
						$query .= $column . ' = "' . $value . '" ';
					}
					
					$fdone2 = true; // Prvi interval gotov, dodaj zareze gore.
				}
			}
			
			return $this->SQL($query) !== false ? true : false; // Ako query ne uspije vraćamo false.
		}
		else
		{
			return false;
		}
	}
	
	// Generator query-a. Vraća rezultat query-a, ali ne i podatke.
	// $col array kolona za upit unutar tablice.
	
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
	
	// Pokreće query baziran na parametrima i vraća podatke.
	// Opcija limita i sortiranja u zadnjim parametrima.
	
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
	
	// Vraća jedan rezultat. Ako se traži samo user id iz jedne tablice vratiti će direktno user id vrijednost. Ako se traži više rezultata vraća array.
	
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
	
	// $one varijabla na TRUE omogućuje da se vrati samo jedan rezultat.
	// Pokreće query i vraća podatke bazirano na $query SQL string parametru.
	
	public function fetchSQL($query, $one = false)
	{
		#echo str_replace('?', $this->preset, $query);
		
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
	
	// Brisanje item-a iz baze. Ako where nije određen, briše cijelu tablicu.
	public function delete($table, $where = false)
	{
		$query = 'DELETE FROM ?' . $table;
		
		if( is_array( $where ) )
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
		
		#echo str_replace('?', $this->preset, $query);
		
		return $this->SQL($query);
	}
	
	// Vraća posljednji unešen ID u trenutnoj mysql sesiji.
	public function getInsertId()
	{
		return $this->db->lastInsertId();
	}
}