<?php

class Conf{
	
	/**
	* Naknadno æe se napuniti polja.
	*/
	public static $settings = array();
	public static $db = array();
	
	/**
	* Site urls.
	*/
	public static $url = array(
					'facebook' => 'http://www.facebook.com/',
					'facebook_short' => 'www.facebook.com',
					
					'twitter' => 'http://www.twitter.com/',
					'twitter_short' => 'www.twitter.com',
					
					'pinterest' => 'http://www.twitter.com/',
					'pinterest_short' => 'www.twitter.com',
					
					'tumblr' => 'http://www.tumblr.com/',
					'tumblr_short' => 'www.tumblr.com',
					
					'stumbleupon' => 'http://www.stumbleupon.com/',
					'stumbleupon_short' => 'www.stumbleupon.com',
					
					'linkedin' => 'http://www.linkedin.com/',
					'linkedin_short' => 'www.linkedin.com',
					
					'googleplus' => 'https://plus.google.com/',
					'googleplus_short' => 'plus.google.com',
					
					'wikiquery' => 'http://www.wikipedia.org/wiki/',
					'booking' => 'http://www.booking.com/'
				);
	
	/**
	* Site pages links.
	*/
	public static $src = array(
					'generate' => 'dynamic_src.php?file=',
					'pics' => 'content/pics/',
					'thumbs' => 'content/thumbs/',
					'scripts' => 'src/scripts/',
					'styles' => 'src/styles/',
					'images' => 'src/images/'
				);
	
	/**
	* Urlovi prema stranicama.
	*/
	public static $page = array(
					'index' 				=> '',
					'album_generated' 		=> 'album/generated/',
					'categories' 			=> 'categories/',
					'404' 					=> 'err404/',
					'search_categories'		=> 'search/categories/',
					'search_keyword'	 	=> 'search/',
					'profile_home' 			=> 'user/home/',
					'profile_view' 			=> 'user/profile/',
					'my_albums' 			=> 'user/albums/home/',
					'user_albums' 			=> 'user/albums/',
					'my_favorite_photos' 	=> 'user/favorites/home',
					'user_favorite_photos' 	=> 'user/favorites/',
					'photo_view' 			=> 'photo/view/',
					'fb_login' 				=> 'fb-login/',
					'terms' 				=> 'terms/',
					'privacy' 				=> 'privacy/',
					'upload_result' 		=> 'upload/result/',
					'upload_add_info' 		=> 'upload/addinfo/',
					'logout' 				=> 'logout/',
					'ajax' 					=> 'ajax/',
					'default_login'			=> 'fb-login/',
					'notifications'			=> 'notifications/'
				);
	
	/**
	* Direktoriji na stranici.
	*/
	public static $dir = array(
					'widgets' 		=> '/views/widgets/',
					'widget_data' 	=> '/views/widget-data/',
					'includes' 		=> '/includes/',
					'styles' 		=> '/src/styles/',
					'scripts' 		=> '/src/scripts/',
					'images' 		=> '/src/images/',
					
					'common_scripts' 	=> '/src/scripts/common/',
					'preloaded_styles'	=> '/src/styles/preloaded/',
					
					'pics'		=> '/content/pics/',
					'thumbs'	=> '/content/thumbs/'
				);

	/**
	* Inicijalizira konfiguraciju, dodaje pune puteve gornjim urlovima.
	* @return void
	*/
	
	public static function include_app()
	{
		$f = array('Registry', 'Template', 'Router', 'BaseController', 'AjaxController', 'DB');

		foreach($f as $file)
		{
			include(ROOT_SITE_PATH . '/application/' . $file . '.php');
		}
	}
	
	/**
	* Dodaje pune puteve gornjim urlovima, postavlja konstante.
	* @return void
	*/
	
	public static function init()
	{	
		function __autoload($class)
		{
		
			$file = 'model/' . $class . '.php';
			
			if(file_exists($file))
			{
				include($file);
			}
			else{
				echo 'Model ' . $class . ' not found!';
			}
		}
		
		define('HOSTING_LOCATION', 'TEST_SERVER'); # Modovi: WEB_SERVER, TEST_SERVER
		
		include(ROOT_SITE_PATH . '/includes/dbconfig.php'); self::$db = $dbConfig;
		
		if(class_exists('DB', false))
		{
			
			/**
			* Konekcija, pogledaj u kojem je modeu stranica postavljena preko admina.
			*/ 
			DB::connect(Conf::$db['SERVER'], Conf::$db['USER'], Conf::$db['PASS'], Conf::$db['DB']);
			
			$result = DB::sql('SELECT setting1 FROM site_settings WHERE setting_name="site_mode"');
			$fetch = $result -> fetch(PDO::FETCH_ASSOC);

			define('MODE', $fetch['setting1']); # Modovi: DEVELOPMENT, ONLINE, MAINTENANCE (postavlja se u admin suèelju u sluèaju da stranica poðe u ...)
		}
		else
		{
			define('MODE', 'ONLINE');
		}
		
		switch( HOSTING_LOCATION )
		{
			
			case 'TEST_SERVER';
				define('SITE_URL', 'http://pixplorer.ivanivkovich.com/');
			break;
			
			case 'WEB_SERVER';
				define('SITE_URL', 'http://www.pixplorer.net/');
			break;
		}
		
		# ID-evi za referencu. Recimo ako je facebook onda je 0 svugdje i u bazi i u aplikaciji. Primjer: if($fetch['social_id'] == FACEBOOK_ID)
		define('FACEBOOK_ID' , 0);
		define('TWITTER_ID' , 1);
		
		define('FACEBOOK_APP_ID', 177259275703895);
		
		# Personal info
		define('DEV_TEAM_MAIL', 'team@pixplorer.net');
		define('SITE_MAIL', 'contact@pixplorer.net');
		
		define('SITE_URL_STRING', 'www.pixplorer.net');
		
		/**
		* Error reporting ako je online ili development.
		*/
		$setting = MODE === 'DEVELOPMENT' ? -1 : 0;
		$setting2 = MODE === 'DEVELOPMENT' ? true : false;
		
		error_reporting( $setting );
		ini_set('display_errors', $setting2);
		
		# Site function constants.
		define('BOX_DESCRIPTION_LIMIT', 80); # For boxes text limit.
		define('PHOTO_DESCRIPTION_LIMIT', 150); # For boxes text limit.
		define('PAGE_LOAD_ITEM_LIMIT', 50); # When the page loads, item boxes limit.
		define('AJAX_LOAD_ITEM_LIMIT', 25); # Infinite scroll item boxes limit.
		define('PERIODICAL_UPDATE_INTERVAL', 2000); # Infinite scroll item boxes limit.
		
		define('SITE_NAME', 'Pixplorer');
		
		self::setFullPaths();
		
		if( isset($_SERVER['HTTP_REFERER']) )
		{
			$prev_page = 
				
				!hasUrl( self::$url['facebook_short'], $_SERVER['HTTP_REFERER'] ) &&
				!hasUrl( self::$url['googleplus_short'], $_SERVER['HTTP_REFERER'] ) &&
				!hasUrl( self::$url['twitter_short'], $_SERVER['HTTP_REFERER'] ) &&
				!hasUrl( self::$url['pinterest_short'], $_SERVER['HTTP_REFERER'] ) &&
				!hasUrl( self::$url['tumblr_short'], $_SERVER['HTTP_REFERER'] ) &&
				!hasUrl( self::$url['linkedin_short'], $_SERVER['HTTP_REFERER'] ) &&
				!hasUrl( self::$url['stumbleupon_short'], $_SERVER['HTTP_REFERER'] )
				
			? $_SERVER['HTTP_REFERER'] : SITE_URL;
		}
		else
		{
			$prev_page = SITE_URL;
		}
		
		self::$page['previous_page'] = $prev_page;
	}
	
	/**
	* Sets full paths to current ones.
	* @return void
	*/
	
	private static function setFullPaths()
	{
		foreach(self::$page as $key => $value)
		{
			self::$page[$key] = SITE_URL . $value;
		}
		
		foreach(self::$src as $key => $value)
		{
			self::$src[$key] = SITE_URL . $value;
		}
		
		foreach(self::$dir as $key => $value)
		{
			self::$dir[$key] = ROOT_SITE_PATH . $value;
		}
	}
	
}