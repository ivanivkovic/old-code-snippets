<?php

/**
* @author Ivan Ivković
* Testiranje za selekciju kandidata za radno mjesto Software Developer Intern @ Degordian
*
* Vojske su podijeljene na dvije vrste : napadajuća i defenzivna.
* Napadač dobiva prednost (poveća mu se "luck") pred napadnutom vojskom, zavisno o tipu napada. Tipovi su navedeni u klasama i određeni na random ili zahtjev programera u kodu. (parametri u metodi)
* 
* Bitke su podijeljene na evente, budući da neće cijela vojska ići direktno na suparničku vojsku. Dalje u kodu.
*/

/**
1xx: Informational - Request received, continuing process 
2xx: Success - The action was successfully received, understood, and accepted 
3xx: Redirection - Further action must be taken in order to complete the request 
4xx: Client Error - The request contains bad syntax or cannot be fulfilled 
5xx: Server Error - The server failed to fulfil an apparently valid request
*/


error_reporting(-1);

$params = array( 'army1', 'army2' );

include('config.php');

// Pokreni bazu podataka, istanciraj klasu u objekt koji će raditi s logiranjem bitki.
$DB = new libDB(Conf::$_DB);
$log = new appLog($DB);

// Funkcija za brisanje povijesti o svim igrama.
if( isset( $_GET['action'] ) )
{
	switch( $_GET['action'] )
	{
		case 'clear':
			$log->clearAllGamesHistory();
		break;
	}
}

// Ako nema inputa, JSON vraća no input response. 
if( ! empty( $_GET ) && isset( $_GET[ $params[0] ] ) && isset( $_GET[ $params[1] ] ) )
{
	// Određujemo n1 i n2 po $_GET varijabli.
	$n1 = $_GET[ $params[0] ];
	$n2 = $_GET[ $params[1] ];
	
	// Provjera numeričnosti, ako nisu numerični izbacujemo error.
	if( is_numeric( $n1 ) && is_numeric( $n2 ) )
	{
		// Vojska mora imati više od 0 vojnika.
		if( $n1 > 0 && $n2 > 0 )
		{
			// Istanciranje player klase.
			$player1 = new objPlayer();
			$player2 = new objPlayer();
			
			// U konstruktoru klase objPlayer unaprijed je postavljeno nasumično generiranje igrača po imenu i ID-u.
			// Ako/dok je nasumično generirani igrač 2 jednak igraču 1, ponovo ga pozovi.
			while( $player1->id === $player2->id )
			{
				$player2 = new objPlayer();
			}
			
			// Istanciranje vojski.
			$army1 = new objArmy( $n1, $player1 ); // Attacking Army
			$army2 = new objArmy( $n2, $player2 ); // Defending Army
			
			// Pokreni bitku, ostalo pogledati u combat.app.class.php
			$combatResult = appCombat::initBattle( $army1, $army2 );
			
			// Ako nam ne uspije unos loga bitke u bazu podataka, izbaci error. U protivnom izbaci uspjeh.
			if( ! $log->createLog( $combatResult ) )
			{
				libJSON::loadCache( array( 'return' => 500, 'text' => 'Database entry insertion failed.') );
			}
			else
			{
				libJSON::loadCache( array('return' => 200, 'text' => 'Database entry success', 'data' => $combatResult) );
			}
		}
		else
		{
			libJSON::loadCache( array( 'return' => 401, 'text' => 'Both armies must have at least 1 soldier.') );
		}
	}
	else
	{
		libJSON::loadCache( array( 'return' => 400, 'text' => 'Invalid Input') );
	}
}
else
{
	libJSON::loadCache( array('return' => 3, 'text' => 'No Input') );
}

// Dobavi popis bitki i sve njihove informacije.
$logsList = $log->getLogs();

// Učitaj ispis bitki.
include(Conf::$_DIR['views'] . 'home.php');

?>