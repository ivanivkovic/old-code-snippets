<?php

/**
* Objekt Registry klase sadri objekte u $vars arrayu.
*/

class Registry{

	public $vars = array();

	public function __set($k, $v)
	{
		$this -> vars[$k] = $v;
		
		$r = isset($this -> vars[$k]) ? true : false;
		return $r;
	}
	
	public function __get($k)
	{
		$r = isset($this -> vars[$k]) ? $this -> vars[$k] : false;
		return $r;
	}
	
}