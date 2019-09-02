<?php

class Router{
	
	private $registry;
	
	public $route;
	
	public $page;
	public $action;
	public $criteria;
	public $criteria2;
	public $criteria3;
	public $criteria4;
	public $criteria5;
	
	public $dir_path;
	
	public function __construct($registry)
	{
	
		$this->getRouterInfo();
		$this->registry = $registry;
		
	}
	
	# Uèitava sve GET podatke o tome koja se stranica traži i parametri za stranicu, akcije i slièno.
	private function getRouterInfo()
	{
	
		if(isset($_GET['rt']))
		{
			$d = explode('/', $_GET['rt']);
			$this->route = $_GET['rt'];
			
			# Definiramo konstantu za current URL.
			define('CURRENT_PAGE', SITE_URL . $this->route);
			
			$this->page = isset($d[0]) ? $d[0] : 'err404';
			$this->action = isset($d[1]) ? $d[1] : 'index';
			$this->criteria = isset($d[2]) ? $d[2] : false;
			$this->criteria2 = isset($d[3]) ? $d[3] : false;
			$this->criteria3 = isset($d[4]) ? $d[4] : false;
			$this->criteria4 = isset($d[5]) ? $d[5] : false;
			$this->criteria5 = isset($d[6]) ? $d[6] : false;
			
			# Criteria znaèi recimo site.net/user/4   broj 4 bi bio ID user-a. TO je criteria, criteria 2 bi bilo site.net/user/4/favorites
		}
		else
		{
			$this->page = 'index';
			$this->action = 'index';
			$this->criteria = false;
			$this->criteria2 = false;
			$this->criteria3 = false;
			$this->criteria4 = false;
			$this->criteria5 = false;
		}
	}
	
	# Setting path for the page directory (i.e. views/pages/index)
	/**
	* @param string $p path base.
	*/
	public function setDirPath($p)
	{
		$dir_path = $p . '/' . $this->page;
		
		if(MODE !== 'MAINTENANCE')
		{
			if(is_dir($dir_path))
			{
				$this->dir_path = $dir_path;
			}
			else
			{
				$this->dir_path = $p . '/err404';
				$this->page = 'err404';
			}
		}
		else
		{
			$this->dir_path = $p . '/maintenance';
			$this->page = 'maintenance';
		}
	}
	
	# Loads up the controller.
	public function loader()
	{
		include $this->dir_path . '/Controller.php';
		
		$controller = new Controller($this->registry);

		$controller->criteria  = $this->criteria;
		$controller->criteria2 = $this->criteria2;
		$controller->criteria3 = $this->criteria3;
		$controller->criteria4 = $this->criteria4;
		$controller->criteria5 = $this->criteria5;
	
		if(is_callable(array($controller, $this->action)))
		{
			$action = $this->action;
			$controller->$action();
		}
		else
		{
			$controller->index($this->action);
		}
	}
}