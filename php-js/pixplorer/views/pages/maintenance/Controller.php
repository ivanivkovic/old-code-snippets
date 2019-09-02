<?php

class Controller extends BaseController{
	
	public function index()
	{
		$this -> registry -> template -> loadTemplate(__FUNCTION__);
	}
	
}