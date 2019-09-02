<?php

/*
* Common Errors
* 0 = ok
* 1 = missing / not found
*/


function __autoload($className)
{
	switch(substr($className, 0, 3))
	{
		case 'lib':
			$file = strtolower( 'lib/' . str_replace( 'lib', '', $className ) . '.lib.class.php' );
		break;
		
		case 'mod':
			$file = strtolower( 'model/' . str_replace( 'model', '', $className ) . '.model.class.php' );
		break;
	}
	
	if( file_exists( $file ) )
	{
		include( $file );
	}
}



class Conf
{
	public static $INI = array(
							'db_min_match_letters' => 8
	);
	
	public static $SETTINGS = array(
							'timed_refresh_interval' => 3000
	);

	public static $DB_SET = array(
								'server'	=> 'localhost',
								'user'		=> 'ivanivk_collab',
								'pass'		=> '-N2F.c3Gy.},',
								'db'		=> 'ivanivk_collab',
								'preset'	=> 'pos'
							);

	public static $GTMOffset = +1;
	
	public static $HrCharConvSearch = array('Č', 'č', 'Ć', 'ć', 'Ž', 'ž', 'Đ', 'đ', 'Š', 'š');
	public static $HrCharConvReplace = array('C', 'c', 'C', 'c', 'Z', 'z', 'D', 'd', 'S', 's');


	public static $EnglishAlphabet = array ('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
											'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
											' ', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');


	public static $LettersLower = array('č', 'ć', 'ž', 'đ', 'š');
	public static $LettersUpper = array('Č', 'Ć', 'Ž', 'Đ', 'Š');

	public static $EmailRegEx = "/^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)$/i";
	public static $FindEmailRegEx = "/\b[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)\b/i";

	public static $NoNiceChar = array('&', '<', '>', "'", '"');
	public static $HtmlReplacment = array('&amp;', '&lt;', '&gt;', '&#039;', '&quot;');

	const DIR_WIDGETS = 'app/widgets/';
	const DIR_INCLUDES = 'app/includes/';
	const DIR_FILES = 'data/files/';
}


class Core
{
	public static $db;
	public static $user;
	public static $router;

	public static function init()
	{
		self::$db = new libDB( Conf::$DB_SET );
		self::$user = new libUser();
		self::$router = new libRouter();
	}
}

Core::init();