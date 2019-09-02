<?php


// Bitka se sastoji od niza eventova različitih scenarija, tome služi klasa appCombatEvent.
// Razne opcije se još mogu nadodati i lako isprogramirati.

Class appCombatEvent
{
	// Popis događaja i njihove funkcije.
	
	public static $events = array(
						array(
							'eventCode' => 0,
							'eventDescription' => 'Troops went face to face to one another',
							'eventName' => 'Direct battle',
							'attacker_luck_gain' => 0,
							'duration' => 20 // u sekundama, po jedinici/vojniku, poslije se zbraja kroz bitku podijeljeno sa 1.3
						),
						array(
							'eventCode' => 1,
							'eventName' => 'Sneak attack',
							'eventDescription' => 'Attacking troop caught the enemy off guard at it\'s territory',
							'attacker_luck_gain' => 3,
							'duration' => 15
						),
						array(
							'eventCode' => 2,
							'eventName' => 'Caught off guard',
							'eventDescription' => 'Attacker attacked a smaller pack from behind',
							'attacker_luck_gain' => 5,
							'attacker_damage_gain' => 10,
							'duration' => 10
						),
						array(
							'eventCode' => 3,
							'eventName' => 'Ambushed',
							'eventDescription' => 'The defending troops have organized an ambush for the attackering brute',
							'defender_luck_gain' => 4,
							'duration' => 10
						),
						array(
							'eventCode' => 4,
							'eventName' => 'Bad attack',
							'eventDescription' => 'The defending troops have equipped themselves very well for a possible attack',
							'defender_hp_gain' => 20,
							'defender_damage_gain' => 10,
							'duration' => 10
						)
	);
	
	// Interfeerence su smetnje tokom borbe i imaju utjecaj na ishod bitke.
	public static $interfeerences = array(
						array(
							'interfeerenceCode' => 0,
							'interfeerenceName' => 'Gass tank explosion',
							'interfeerenceDescription' => 'A gass tank has exploded near the troops resulting in some casulties.',
							'troop_death' => 25 // Postotak čete poginulih
						),
						array(
							'interfeerenceCode' => 1,
							'interfeerenceName' => 'Landmines',
							'interfeerenceDescription' => 'A small number of troops stepped on a landmine territory on the battlefield',
							'troop_death' => 10
						),
						array(
							'interfeerenceCode' => 2,
							'interfeerenceName' => 'Terrain collapse',
							'interfeerenceDescription' => 'As the troops walked through a congested terrain, some of the units got caught up in the disaster',
							'troop_death' => 20
						)
	);
    
	// Učitaj pojedini event za trenutne dvije čete.
	public static function initEvent( $t1, $t2, $eventtype = false, $interfeerence = false, $interfeerenceVictim = false)
	{
		$log = array();
		$log['interfeerence'] = array();
		
		// Ako smetnja/interfeerence nije određen, bazirano na konfiguraciji igre, aplikacija će jednom u x eventa generirati interfeerence.
		if( $interfeerence === false )
		{
			// Ako random number generator pogodi šansu za generiranje interfeerencea, generiraj ga.
			if( 
				rand(1, Conf::$_GAME_SETTINGS['interfeerence_chance'] ) === Conf::$_GAME_SETTINGS['interfeerence_chance'] &&
				Conf::$_GAME_SETTINGS['interfeerence_enabled']
			)
			{
				$interfeerence = self::getRandomInterfeerence(); // Učitaj random ID interfeerencea.
				$interfeerence = self::$interfeerences[$interfeerence]; // Dobavi sve podatke o interfeerenceu.
			}
		}
		
		// Ako interfeerence nije false (određen je), pokreni ga.
		if( $interfeerence !== false && Conf::$_GAME_SETTINGS['interfeerence_enabled'] === true )
		{
			// Ako žrtva smetnje nije određena, odredi ju nasumično : napadač/branitelj
			if( $interfeerenceVictim === false )
			{
				$interfeerenceVictim = rand(0, 1); 
			}
			
			// Pokreni event smentnje, zabilježi return u result varijablu.
			$result = self::simulateInterfeerence($t1->n, $t2->n, $interfeerence, $interfeerenceVictim);
			
			// Dodatni podaci za interfeerence koje ćemo trebati spremiti u bazu podataka.
			$log['interfeerence'] = $result;
			$log['interfeerence']['interfeerenceVictim'] = $interfeerenceVictim;
			$log['interfeerence']['interfeerenceCode'] = $interfeerence['interfeerenceCode'];
			
			// Ažuriraj broj vojnika u četama.
			$t1->n = $result['t1n'];
			$t2->n = $result['t2n'];
		}
		
		// Ako event type nije određen u parametru metode, odredi ga na random
		if( $eventtype === false )
		{
			$eventtype = self::getRandomEventType();
		}
		
		// Dobavi informaciju o eventu čiji imamo samo ID.
		$event = self::$events[$eventtype]; 
		
		// Simuliraj bitku između trupa, spremi rezultat u $eventresult.
		$eventresult = self::simulateEvent($t1, $t2, $event);
		
		// Zabilježi rezultate događaja u $log array.
		$log['eventresult'] = $eventresult;
		$log['eventtype'] = $eventtype;
		
		// Vrati array $log.
		return $log;
	}
	
	// Simuliranje eventa/bitke, parametri su dvije čete i informacija o eventu.
	private static function simulateEvent($t1, $t2, $event)
	{
		// Zbroj svih vojnika prijateljske i neprijateljske sile, za izračun trajanja eventa.
		$all_units = $t1->n + $t2->n;
		
		// Trajanje eventa.
		$duration = floor ( ( $all_units * $event['duration'] ) / 1.3 );
		
		// Ako event povećava LUCK vrijednost napadalačkoj sili, dodijeli ju.
		if( isset( $event['attacker_luck_gain'] ) ) 
		{
			$t1->luck += $event['attacker_luck_gain'];
		}
		
		// Ako event povećava LUCK vrijednost obrambenoj sili, dodijeli ju.
		if( isset( $event['defender_luck_gain'] ) ) 
		{
			$t2->luck += $event['defender_luck_gain'];
		}
		
		// Ako event povećava HP vrijednost napadalačkoj sili, dodijeli ju.
		if( isset( $event['attacker_hp_gain'] ) ) 
		{
			$t1->hp += $event['attacker_hp_gain'];
		}
		
		// Ako event povećava HP vrijednost obrambenoj sili, dodijeli ju.
		if( isset( $event['defender_hp_gain'] ) ) 
		{
			$t2->hp += $event['defender_hp_gain'];
		}
		
		// Ako event povećava DAMAGE vrijednost napadalačkoj sili, dodijeli ju.
		if( isset( $event['attacker_damage_gain'] ) ) 
		{
			$t1->damage += $event['attacker_damage_gain'];
		}
		
		// Ako event povećava HP vrijednost obrambenoj sili, dodijeli ju.
		if( isset( $event['defender_damage_gain'] ) ) 
		{
			$t2->damage += $event['defender_damage_gain'];
		}
		
		// Izračun bitke.
		// Izračunaj total vrijednost hit pointa i damagea u skladu s LUCK vrijednosti čete.
		$total1 = new stdClass();
		$total2 = new stdClass();
		
		// Luck ćemo pretvoriti u 1.03 ili sličnu brojku kako bismo smanjili preveliku razliku između snaga dvije sile.
		$luck1 = 1 + ( $t1->luck / 25);
		$luck2 = 1 + ( $t2->luck / 25);
		
		// Pomnožit ćemo HP i DAMAGE sa vrijednosti LUCK. npr. 100 * 1.15
		$t1->hp = $t1->hp * $luck1;
		$t2->hp = $t2->hp * $luck2;
		
		$t1->damage = $t1->damage * $luck1;
		$t2->damage = $t2->damage * $luck2;
		
		// Total vrijednosti se uračunavaju. Množimo broj vojnika s njihovim statsima. HP * n, DAMAGE * n
		// Luck je već unaprijed izračunat po jedinici.
		
		$total1->hp = floor( ( $t1->hp * $t1->n ) );
		$total1->damage = floor( ( $t1->damage * $t1->n ) );
		$total1->units = $t1->n;
		
		$total2->hp = floor( ( $t2->hp * $t2->n ) );
		$total2->damage = floor( ( $t2->damage * $t2->n ) );
		$total2->units = $t2->n;
		
		// Total end varijabla služi za procesiranje kroz loop, ostavljajući $total1 i $total2 varijablu nazad u slučaju zatrebaju li nam stari podaci.
		$totalEnd1 = $total1;
		$totalEnd2 = $total2;
		
		
		// Sve dok nije jedna četa pobijeđena, nastavi s bitkom.
		while( $totalEnd1->units > 0 && $totalEnd2->units > 0 )
		{
			// Oduzmi HP svakoj četi onoliko koliko mu suprotna četa može oduzeti.
			// DAMAGE DEALING
			
			$t2damage = $t2->damage * $totalEnd2->units;
			$t1damage = $t1->damage * $totalEnd1->units;
			
			$totalEnd1->hp -= $t2damage;
			$totalEnd2->hp -= $t1damage;
			
			
			// Pogledaj koliko je ljudi ostalo svakoj partiji.
			$totalEnd1->units = ceil( $totalEnd1->hp / $t1->hp );
			$totalEnd2->units = ceil( $totalEnd2->hp / $t2->hp );
		}
		
		
		
		// Osvježi krajnji broj vojnika i vrati ga return keywordom u arrayu.
		$t1->n = $totalEnd1->units > 0 ? $totalEnd1->units : 0;
		$t2->n = $totalEnd2->units > 0 ? $totalEnd2->units : 0;
		
		return array( 't1' => $t1, 't2' => $t2, 'duration' => $duration );
	}
	
	// Simuliraj smatnju/interfeerence.
	private static function simulateInterfeerence($t1n, $t2n, $interfeerence, $interfeerence_victim = 0)
	{
		// Odredi varijabilnu varijablu koju ćemo koristiti bazirano na tome tko je meta smetnji.
		$t = 't' . ( $interfeerence_victim + 1 ) . 'n';
		
		// Ako smetnja ubija vojsku, oduzmi broj vojnika žrtvenoj četi.
		if( isset( $interfeerence['troop_death'] ) )
		{
			// Broj žrtava, zaokruženi donji broj po postotku smrtnosti smetnje.
			$casualties = floor( ( $$t / 100 ) * $interfeerence['troop_death']);
			
			$$t = $$t - $casualties;
		}
		
		return array( 't1n' => $t1n, 't2n' => $t2n, 'casualties' => $casualties );
	}
	
	// Dobavi nasumični ID eventa.
	private static function getRandomEventType()
	{
		$rand = rand( 0, count( self::$events ) -1 );
		
		return self::$events[$rand]['eventCode'];
	}
	
	// Dobavi nasumični ID smetnje.
	private static function getRandomInterfeerence()
	{
		$rand = rand( 0, count( self::$interfeerences ) -1 );
		
		return self::$interfeerences[$rand]['interfeerenceCode'];
	}
}