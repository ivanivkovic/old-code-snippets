<?php

// Bitka se dijeli na evente. U bitci nije cijela vojska na jednom mjestu, nego se podijeli na evente, po trupama.
// Programski se to događa na random.

Class objTroop
{
	// Broj vojnika u trupi. 
	public $n;
	
	// Statistike po pojedinom vojniku. Vrijednosti se poslije kalkuliraju.
	public $hp = 100;
	public $damage = 40;
	public $luck = 0; // Luck povećava HP i Damage s obzirom na to koliko ga ima (ovisno o eventu).
	
	// Unesi broj vojnika u objekt.
	public function __construct($n)
	{
		$this->n = $n;
	}
}