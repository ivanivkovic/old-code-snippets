<?php

// Klasa za igrača.
// Za svrhe testiranja, ne radi se o pravim igračima nego o simuliranima.
// Zato imamo $randomNames atribut koji nam pomaže pri generiranju istih.

class objPlayer
{
	private static $randomNames = array('break2k','DrunkAus','krisandrioni','i618','Radar_666','billy_dlc','akash_100294','Allandeassis_M','Phizzo89','shaggycool117','cjtsoy','sinknot','craazyfist','TusharV8','PeluFunk','JackSladeDrums','Sadissst','xxx_birdman_xxx','zaidcrowe','Nythyn','davman5000','sliprekiem','Kangaxx_','a1phanumeric','Tessarion','Vyazovyh','metal7core','devanmc436','Achlys_Nils','OVER_D','binari0','bdehaas','sturgemeister','seanp0well','carterly','rizalomaniac','-GaNgr33n-','nerbys_sretlow','MaxfmUK','shortman_alan','deadwoo','russellsims','DaveyCadaver','Dom_Cardell46','JohnBalci','nutzisme','carlnorrbom','neofish22','metrvg','jimih_');
	
	// Podaci o igraču
	public $id;
	public $name;
	
	// Konstruktor, poziva privatnu metodu za generiranje igrača.
	public function __construct()
	{
		$playerData = $this->getRandomPlayer();
		
		$this->id = $playerData[0];
		$this->name = $playerData[1];
	}
	
	// Generiraj igrača na random iz atributa $randomNames.
	private function getRandomPlayer()
	{
		$rand = rand( 0, count( self::$randomNames ) -1 );
		
		return array( $rand, self::$randomNames[ $rand ]);
	}
}