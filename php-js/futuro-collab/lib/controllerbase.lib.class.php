<?php

class libControllerBase
{
	public function __construct()
	{
		$this->loadBoringData();
	}
	
	/* Za sve podatke koje bismo trebali upisivati 39876541421 puta. */
	private function loadBoringData()
	{
		libTemplate::set(
						array(
							'user' => Core::$user->data,
							'activeProjects' => modelProject::getActiveProjects(), // Za glavnu navijgaciju
							'lang' => libTemplate::$lang,
							'activeTasks' => modelTask::getUserHotTasks( Core::$user->id )
						)
		);
	}
}


interface intControllerBase
{
	public function index($param = false);
}