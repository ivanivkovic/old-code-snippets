<?php

Class objArmy
{
	public $n; // Broj vojnika u vojsci.
	public $p; // Informacija o igra�u, player �e biti la�an i ne�e ga biti u bazi, izmi�ljeno ime iz random player imena.
	
	public function __construct($n, $p)
	{
		$this->n = $n;
		$this->p = $p;
	}
}