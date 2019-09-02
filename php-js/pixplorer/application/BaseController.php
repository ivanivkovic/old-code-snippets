<?php

/**
* Apstraktna klasa za obièan HTML output controller.
*/

abstract class BaseController{
	
	protected $registry; 
	
	public function __construct($registry) # Every controller must override a registry.
	{
		$this -> registry = $registry;
	}
	
	/** 
	* Svaki kontroler mora imati index metodu.
	*/
	
	abstract function index(); 
}