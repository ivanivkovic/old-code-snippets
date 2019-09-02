<?php

class libTemplate
{
	public static $routeInfo = array();
	public static $pageVars = array();
	public static $path;
	public static $lang = 'hr';
	public static $langs = array(
								'hr' => 'Hrvatski', 
								'en' => 'English'
	);
	
	public static function loadPage( $page )
	{
		$file = self::$path . $page . '.php';
		
		if( file_exists($file) )
		{
			self::loadTemplateFile( $file );
		}
		else
		{
			throw new Exception( 'Template file ' . $page .  ' not found!' );
		}
	}
	
	public static function loadTemplateFile($file)
	{
		self::initTxt();
		
		extract(self::$pageVars);
		
		include($file);
	}
	
	// Učitava widget s postavkama.
	public static function widget($widgetName, $settings = array())
	{
		include( Conf::DIR_WIDGETS . $widgetName . '/load.php' );
	}
	
	// Postavlja pageVars atribute za template.
	public static function set($key, $value = '')
	{
		if( is_array( $key ) )
		{
			foreach( $key as $k => $val )
			{
				self::$pageVars[$k] = $val;
			}
		}
		else if( ctype_alnum($key) && $value !== '')
		{
			self::$pageVars[$key] = $value;
		}
		else
		{
			return false;
		}
	}
	
	public static function addError( $error )
	{
		self::$pageVars['error'][] = $error;
	}
	
	public static function addSuccess( $success )
	{
		self::$pageVars['success'][] = $success;
	}
	
	public static function setLanguage($lang)
	{
		self::$lang = $lang;
	}
	
	// Vraća lang varijable.
	public static function txt( $code, $code2 = false)
	{
		self::initTxt();
		
		if( $code2 === false )
		{
			if( isset( self::$pageVars['txt'][$code] ) )
			{
				return self::$pageVars['txt'][$code];
			}
		}
		else
		{
			
			if( isset( self::$pageVars['txt'][$code][$code2] ) )
			{
				return self::$pageVars['txt'][$code][$code2];
			}
		}
		
		return false;
	}
	
	// Učitava lang fajl i sprema varijable u $pageVars atribut.
	public static function initTxt()
	{
		if( ! isset( self::$pageVars['txt'] ) )
		{
			$file = 'inc/lang/static_' . self::$lang . '.php';
			
			if( file_exists($file) )
			{
				include($file);
				self::$pageVars['txt'] = $txt;
			}
			else
			{
				echo 'Lang file for ' . self::$lang . ' missing!';
			}
		}
	}
	
	// Iz timestampa u 24. 12. 2009.
	public static function formatTimeString( $timestamp )
	{
		return $timestamp != 0 ? date('Y.n.j. H:i', $timestamp ) : self::txt(5) /* Never */ ;
	}
	
	// Iz timestampa u 24-12-2009
	public static function formatTimeTag( $timestamp )
	{
		return date('Y-m-d', $timestamp );
	}
	
	public static function convertTimeStringRange( $dateString )
	{
		// Ako string invalidan učitaj sve inforamcije.
		$timeRange['startDay'] = 0;
		$timeRange['endDay'] = strtotime( date('d F Y', strtotime('now')) ) + ( 3600 * 26 ) ; # Tommorow
		
		// Ako je string iz datepickera "danas".
		if( $dateString === libDateTime::$days[libTemplate::$lang]['today'] )
		{
			$timeRange['startDay'] = strtotime( date('d F Y', strtotime('now')) );
			$timeRange['endDay'] = $timeRange['startDay'] + 86400;
		}
		// Ako je string validan string datum.
		else if(preg_match( '/^([0-9]+){2}. ([-a-zA-ZčžćšđČĆŽŠĐ]+) ([0-9]+){4}/', $dateString ))
		{
			$parts = explode(' ', $dateString);
			
			$day = str_replace('.', '', $parts[0]);
			$year = str_replace('.', '', $parts[2]);
			
			$monthString = $parts[1];
			$monthKey = array_search( $monthString, libDateTime::$months[libTemplate::$lang] );
			
			if( $monthKey )
			{
				$string = $day . ' ' . libDateTime::$months['en'][$monthKey] . ' ' . $year;
				
				$timeRange['startDay'] = strtotime( $string );
				$timeRange['endDay'] = $timeRange['startDay'] + 86400;
			}
		}
		
		return $timeRange;
	}
	
	public static function convertTimeString( $dateString )
	{
		// Ako string invalidan učitaj sve inforamcije.
		$timestamp = 0;
		
		// Ako je string iz datepickera "danas".
		if( $dateString === libDateTime::$days[libTemplate::$lang]['today'] )
		{
			$timestamp = strtotime( date('d F Y', strtotime('now')) );
		}
		// Ako je string validan string datum.
		else if( preg_match( '/^([0-9]+){2}. ([-a-zA-ZčžćšđČĆŽŠĐ]+) ([0-9]+){4}/', $dateString ) )
		{
			$parts = explode(' ', $dateString);
			
			$day = str_replace('.', '', $parts[0]);
			$year = str_replace('.', '', $parts[2]);
			
			$monthString = $parts[1];
			$monthKey = array_search( $monthString, libDateTime::$months[libTemplate::$lang] );
			
			if( $monthKey )
			{
				$string = $day . ' ' . libDateTime::$months['en'][$monthKey] . ' ' . $year;
				
				$timestamp = strtotime( $string );
			}
		}
		
		return $timestamp;
	}
	
	public static function getFullDate( $date )
	{
		return date('d. ', $date) . libDateTime::$months[ self::$lang ][ date('n', $date) ] . date(' Y.');
	}
}
