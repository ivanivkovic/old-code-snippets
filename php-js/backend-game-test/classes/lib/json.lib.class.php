<?php

// Statična klasa za rad s JSON-om.
class libJSON
{
	// Privatni atribut koji sadrži popis JSON response-a.
	private static $cache = array();
	
	// Metoda koja dadaje JSON response u popis JSON response-a. 
	public static function loadCache( $array )
	{
		array_push( self::$cache, $array );
	}
	
	// Izbaci sve JSON response odjednom.
	public static function flushCache()
	{
		foreach(self::$cache as $cache)
		{
			echo json_encode( $cache );
		}
	}
}