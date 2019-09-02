<?php

/**
*
* Ovaj fajl poslužuje css i javascript datoteke u kompresiranom ili standardnom formatu, ovisno o modeu stranice. 
* Èeste fajlove uèitava u jedan veliki fajl a rijetke u zaseban fajl.
*
*/

ini_set('display_errors', true);
error_reporting(E_ALL);

$p = realpath(dirname(__FILE__));
define('ROOT_SITE_PATH', $p);

/**
* Globalne funkcije / config / template klasa / baza podataka.
*/

include(ROOT_SITE_PATH . '/includes/functions.php');
include(ROOT_SITE_PATH . '/application/Config.php');
include(ROOT_SITE_PATH . '/application/Template.php');
include(ROOT_SITE_PATH . '/application/DB.php');

Conf::init();

/** 
* Ako je stranica online, kompresiraj datoteke, ako ne onda ih posluži u originalnoj verziji.
*/

define('MINIFY', (MODE === 'ONLINE' ? true : false));

/** Uèitavamo template klasu kako bismo uèitali tekstove sa stranice unutar javascript fajlova. */

$template = new TPL();

if(isset($_GET['file']))
{
	/**
	* Cache-aj css i js 2 tjedna.
	*/
	
	$expires = 14 * 24 * 14;
	header('Pragma: public');
	header('Cache-Control: max-age=' . $expires);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
	
	/**
	* Èitamo i ime ekstenziju fajla.
	*/
	$file_ext = substr(strrchr($_GET['file'],'.'),1);
	$file_name = preg_replace('/\.[^.]*$/', '', $_GET['file']);
	
	/**
	* Za dimensions fajl èitamo visinu i širinu ekrana korisnika, a za ostale poslužimo fajl koji je zatražen ili sve zajedno (all).
	*/
	
	switch($file_name)
	{
		case 'dimensions':
			if(
				isset($_GET['h']) && isset($_GET['w'])
				&& is_numeric($_GET['h']) && is_numeric($_GET['w']) && $file_ext === 'css'
				){
				
				$w = $_GET['w'];
				$h = $_GET['h'];
				
				/**
				* Uèitavamo postavke za monitore, zatim dimensions.php
				*/
				
				include(ROOT_SITE_PATH . '/includes/css_settings.php');
				$file = Conf::$dir['styles'] . $file_name . '.php';
			}
		break;
		
		default:

			switch(strtolower($file_ext))
			{
			
				case 'css':
				
					include(ROOT_SITE_PATH . '/includes/css_settings.php');
					$file = Conf::$dir['styles'] . $file_name . '.php';
					
				break;
			
				case 'js':
				
					$file = Conf::$dir['scripts'] . $file_name . '.php';
					
				break;
				
				default:
				
					die();
					
				break;
			}
	
		break;
		
		case 'all':
		
			switch(strtolower($file_ext))
			{
			
				case 'css':
				
					include(ROOT_SITE_PATH . '/includes/css_settings.php');
					$dir = Conf::$dir['preloaded_styles'];
					
				break;
		
				case 'js':
				
					$dir = Conf::$dir['common_scripts'];
					
				break;
				
				default:
				
					die();
					
				break;
				
			}
			
			$files = array();
					
			if($handle = opendir($dir))
			{
				while($file = readdir($handle))
				{
					if($file !== '.' && $file !== '..')
					{
						array_push($files, $dir . $file);
					}
				}
				closedir($handle);
			}
		
		break;
	}
	
	/**
	* Poèinjemo èitati fajl.
	*/
	ob_start();
	
	if(isset($file))
	{
		if(is_file($file))
		{
			include($file);
		}
	}
	
	/**
	* Ako je više fajlova.
	*/
	if(isset($files))
	{
		foreach($files as $file)
		{
			if(is_file($file))
			{
				include($file);
			}
		}
	}
	
	switch(strtolower($file_ext))
	{	
		case 'css':
		
			header('Content-Type: text/css; charset: UTF-8;');
				/**
				* Kompresiramo / poslužujemo.
				*/
				$css = MINIFY === true ? compressCSS(ob_get_clean()) : ob_get_clean();
			echo $css;
			
		break;
	
		case 'js':
		
			header('Content-Type: application/x-javascript; charset: UTF-8;');
			
			/**
			* Kompresiramo.
			*/
			if(MINIFY === true)
			{
				$JSMin = new JSMin(ob_get_clean());
				echo $JSMin->min();
			}
			else
			{
				echo ob_get_clean();
			}
			
		break;
		
		default:
		
			die();
			
		break;
	}
}
else
{
	echo ' /* Error : Script not found. */ ';
}