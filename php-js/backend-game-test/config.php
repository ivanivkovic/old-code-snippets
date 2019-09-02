<?php

// Statična klasa koja sadrži konfiguracijske varijable.
class Conf
{
	public static $_DB = array(
							'server'	=> 'localhost',
							'user'		=> 'root',
							'pass'		=> '',
							'db'		=> 'game',
							'preset'	=> 'game'
	);
	
	// Include folderi, kako bi ih se u slučaju izmjene lakše globalno ažurirali.
	public static $_DIR = array(
							'classes' => 'classes/',
							'lib' => 'classes/lib/',
							'app' => 'classes/app/',
							'obj' => 'classes/app/obj/',
							'views' => 'views/'
	);
	
	// Globalne postavke igre.
	public static $_GAME_SETTINGS = array(
						'interfeerence_chance' => 3, // Šanse da se unutar bitke dogodi interfeerence (neki događaj koji ometa bitku)
						'interfeerence_enabled' => true // Toggle za interfeerence
	);
}

function __autoload($class)
{
	// Nepoznate klase su najčešće namjenje da budu autoloadane, ili imaju krivo ime.
	// Svaka klasa ima prefix od 3 slova (app, obj ili lib) - app je nešto vezano za aplikaciju poput appCombat (aplikacija pokreće bitku),
	// objTroops je objekt, neki podatak koji se izmjenjuje, a lib je library koji nam pomaže u obradi podataka npr. baza podataka, json, strings itd.
	
	$classPrefix = substr($class, 0, 3);
	$className = str_replace( $classPrefix, '', $class );
	
	// Odredi ime fajla bazirano na imenu nepoznate klase.
	$file = strtolower( Conf::$_DIR[$classPrefix] . $className . '.' . $classPrefix . '.class.php' );
	
	// Ako postoji, includeaj, ako ne, error.
	if( file_exists( $file ) )
	{
		include( $file );
	}
	else
	{
		throw new Exception('Class ' . $class . ' not found in path: ' . $file);
	}
}