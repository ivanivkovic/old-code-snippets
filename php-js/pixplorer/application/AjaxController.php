<?php

/**
* Apstraktna klasa za ajax controller.
*/

abstract class AjaxController{		

	protected $registry; 
	
	public function __construct($registry)
	{
		$this->registry = $registry;
	}
	
	/** 
	* Svaki kontroler mora imati index metodu.
	*/
	
	abstract function index();

}