<?php

class libRouter
{
	public $route;
	public $back;
	private $routeString;
	
	public function redirect($url)
	{
		header('Location: ' . $url); die();
	}
	
	// Postavi route info.
	public function setRoute()
	{
		$this->back = 'javascript:goBack()';
		
		if( isset( $_GET['rt'] ) )
		{
			$this->routeString = $_GET['rt'];
			
			$vars = explode('/', $this->routeString);
			
			if( isset($vars[0]) ){ $this->route['page'] = $vars[0]; }
			if( isset($vars[1]) ){ $this->route['action'] = $vars[1]; }
			
			if( count( $vars ) > 2 )
			{
				unset($vars[0]);
				unset($vars[1]);
				
				// Postavljanje dodatnih custom parametara.
				foreach ( $vars as $var )
				{
					$this->route['param'][] = $var;
				}
			}
			
			if( !isset($this->route['action']) )
			{
				$this->route['action'] = 'index';
			}
		}
		else
		{
			$this->route['page'] = 'index';
			$this->route['action'] = 'index';
		}
	}
	
	// Učitaj skriptu.
	public function loadScript($output)
	{
		switch( $output )
		{
			// Static
			
			case 'static':
			
				$file = 'app/pages/' . $this->route['page'] . '/' . $this->route['page'] . 'Controller.php';
				
				// Nađi kontroler i pokrei ga.
				
				libTemplate::$path = 'app/pages/' . $this->route['page'] . '/';
				libTemplate::$routeInfo = $this->route;
				
				if( file_exists( $file ) )
				{
					include($file);
				
					$page = $this->route['page'];
					$action = $this->route['action'];
					
					$controller = new Controller;
					$controller->__construct();
					
					// Success URL redirect.
					if( isset( $_GET['success'] ) && is_numeric( $_GET['success'] ) )
					{
						libTemplate::addSuccess( $_GET['success'] );
					}
					
					// Error URL redirect.
					if( isset( $_GET['error'] ) && is_numeric( $_GET['error'] ) )
					{
						libTemplate::addError( $_GET['error'] );
					}
					
					// Ako postoji metoda, pokreni ju i prenesi joj parametre.
					// Ako ne postoji metoda, pokreni ju i prenesi joj akciju kao prvi parametar i parametre kao drugi parametar (ako postoje).
					if( method_exists('Controller', $action) )
					{
						if( isset($this->route['param']) )
						{
							$controller->$action($this->route['param']);
						}
						else
						{
							$controller->$action();
						}
					}
					else
					{
						if( isset($this->route['param']) )
						{
							$controller->index($action, $this->route['param']);
						}
						else
						{
							$controller->index($action);
						}
					}
					
					die();
				}
				else
				{
					self::load404('Stranica nije pronađena', 6, Conf::$SETTINGS['timed_refresh_interval']);
				}
				
			break;
			
			// Ajax
			
			case 'ajax':
				
				switch( $this->route['page'] )
				{
					// Akcija nešto napravi i vrati json error:0, ili podatke ili error informacije.
					case 'action':
					
						if( substr( $this->route['param'][0], 0, 3 ) === 'lib' )
						{
							$class = $this->route['param'][0];
						}
						else
						{
							$class = 'model' . ucfirst( $this->route['param'][0] );
						}
						
						$method = $this->route['action'];
						
						if ( is_callable( $class, $method ) )
						{
							$return = $class::$method( $this->route['param'] );
							
							if( $return === true )
							{
								echo json_encode( array('error' => 0) );
							}
							else if ( $return === false)
							{
								echo json_encode( array('error' => 1) );
							}
							else if( is_array($return) )
							{
								echo json_encode( $return ); // return json data
							}
						}
						else
						{
							echo json_encode( array('error' => 'Could not ' . $method . '!'));
						}
						
						die();
						
					break;
					
					// Vraća html.
					case 'gethtml':
						
						if( substr( $this->route['param'][0], 0, 3 ) === 'lib' )
						{
							$class = $this->route['param'][0];
						}
						else
						{
							$class = 'model' . ucfirst( $this->route['param'][0] );
						}
						
						$method = $this->route['action'];
						
						if( $return = $class::$method( $this->route['param'] ) === false )
						{
							echo 'Error u dobavljanju informacije!';
						}
						
						die();
					
					break;
				}
				
			break;
		}
	}
	
	// Učitava custom 404 stranice.
	public function load404($title, $errorCode, $timedBack)
	{
		$controllerBase = new libControllerBase(); // Radi konstruktora.
		
		libTemplate::$path = 'app/pages/404/';
		libTemplate::set( array( 'timedBack' => $timedBack, 'title' => $title ) );
		libTemplate::addError($errorCode);
		libTemplate::loadPage('index');
		
		die();
	}
}