<?php

class TPL{

	private $vars;
	private $registry;
	private $tpl_strings = array();
	
	public function __construct($registry = '')
	{
		$this->registry = $registry;
	}
	
	public function __set($index, $value)
	{
		$this->vars[$index] = $value;
	}
	
	
	public function loadWidget($tpl)
	{
		if(!empty($this->vars))
		{
			foreach($this->vars as $key => $value)
			{
				$$key = $value;
			}
		}
		include(Conf::$dir['widgets'] . $tpl . '.php');
	}
	
	# Uèitava template po pathu u kojem je i kontroler.
	# I.E. ako u pages/index/Controller.php pozovemo $template->loadTemplate('template') kontroler æe uèitati pages/index/template.php
	public function loadTemplate($tpl)
	{
		if (ob_get_contents()){ ob_clean(); }
		$path = $this->registry->router->dir_path . '/' . $tpl . '.php';

		if(file_exists($path) == false)
		{
			throw new Exception('Template not found in '. $path);
			return false;
		}

		if(!empty($this->vars))
		{
			foreach($this->vars as $key => $value)
			{
				$$key = $value;
			}
		}
		// AKO ŽELIMO KOMPRESIRATI HTML, NEPOTREBNO
		#ob_start();
		
			include($path);
			
		// AKO ŽELIMO KOMPRESIRATI HTML, NEPOTREBNO
		#echo htmlCompress(ob_get_clean());
	}
	
	public function loadString($string_tag) # In future, maybe optimize the script to load specific string types that would be seperate by diff files.
	{	
		# Cache strings into array.
		if(empty($this->tpl_strings))
		{
			include(Conf::$dir['includes'] . 'template_strings_en.php');
			
			$this->tpl_strings = $str;
			
			# Return string from array.
			$return = $this->tpl_strings[$string_tag];
		}
		else
		{
			# Return string from array.
			$return = $this->tpl_strings[$string_tag];  
		}
		return $return;
	}
	
	# Loads scripts and styles HTML link to the website. Parameter : style.css or script.js
	public function loadSrc($file)
	{
		$link = Conf::$src['generate'] . $file;
		$parts = explode('.', $file);
		$ext = end($parts);
		
		switch(strtolower($ext))
		{
			case 'css':
				echo '<link rel="' . 'stylesheet' . '" type="' . 'text/css' . '" href="' . $link . '"/>';
			break;
			
			case 'js':
				echo '<script type="' . 'text/javascript' . '" src="' . $link . '"></script>';
			break;
		}
	}
}