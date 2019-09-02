<?php

Class objArmy
{
	public $n; // Broj vojnika u vojsci.
	public $p; // Informacija o igraèu, player æe biti lažan i neæe ga biti u bazi, izmišljeno ime iz random player imena.
	
	public function __construct($n, $p)
	{
		$this->n = $n;
		$this->p = $p;
	}
}