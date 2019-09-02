<?php

// Klasa za rad s bitkama, pokreće bitke i evente iz klase appCombatEvent
class appCombat
{
	// Pokreni bitku
	public static function initBattle($a1, $a2)
	{
		// Pripremi niz log za vraćanje rezultata bitke.
		$log = array( 'events' => array() );
		
		// Učitaj podatke o igraču za log.
		$log['army1Username'] = $a1->p->name;
		$log['army2Username'] = $a2->p->name;
		
		// Početni broj vojnika dok je bitka započeta.
		$log['army1Units'] = $a1->n;
		$log['army2Units'] = $a2->n;
		
		while( $a1->n > 0 && $a2->n > 0 )
		{
			// Nasumično generiranje čete iz vojske za slijedeću bitku.
			// Ako vojska ima manje od 10 vojnika, smjesti ih sve u istu trupu.
			
			$n1 = $a1->n > 10 ? rand(10, $a1->n) : $a1->n;
			$n2 = $a2->n > 10 ? rand(10, $a2->n) : $a2->n;
			
			// Umanji broj vojske za broj vojnika u četi.
			$a1->n -= $n1;
			$a2->n -= $n2;
			
			// Istanciranje objekta čete.
			$t1 = new objTroop( $n1 );
			$t2 = new objTroop( $n2 );
			
			// Učitaj event/borbu.
			$result = appCombatEvent::initEvent($t1, $t2);
			
			// Dodaj preostale vojnike iz pobjedniče čete (0 za četu koja gubi) broju vojnika u pripadajućoj vojsci.
			$a1->n += $result['eventresult']['t1']->n;
			$a2->n += $result['eventresult']['t2']->n;
			
			// Učitaj broj vojnika nakon završetka eventa u polje eventresult u $result polju.
			$result['eventresult']['army1Units'] = $a1->n;
			$result['eventresult']['army2Units'] = $a2->n;
			
			// Učitaj rezultat u log za evente.
			array_push($log['events'], $result);
		}
		
		// Odredi pobjednika bazirano na kranjem broju vojnika.
		$log['winner'] = $result['eventresult']['t1']->n > 0 ? 0 : 1;
		
		// Vrati informaciju o bitci.
		return $log;
	}
}