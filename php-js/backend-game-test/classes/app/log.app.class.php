<?php

class appLog
{
	private $db;
	
	// Učitaj bazu podataka u privatni atribut.
	public function __construct($db)
	{
		$this->db = $db;
	}
	
	// Dobavi popis svih logova iz baze podataka, uključujući sve informacije.
	public function getLogs()
	{
		$combats = $this->db->fetch('*', 'combats', false, '', array('combatID' => 'DESC') );
		
		foreach( $combats as &$combat )
		{
			$combat['events'] = $this->db->fetchSQL('SELECT 
												 ?combats_events.*, 
												 ?combats_events_interfeerences.*
											FROM 
												 ?combats_events
											LEFT JOIN 
												 ?combats_events_interfeerences
											ON
												 ?combats_events.eventID = ?combats_events_interfeerences.eventID
											WHERE combatID = "' . $combat['combatID'] . '"
			');
		}
		
		return $combats;
	}
	
	// Stvori log.
	public function createLog( $combatResult )
	{
		// Stvori bitku u tablici combats, zabilježi insert ID.
		$insertID = $this->db->insert(
						array(
							'army1Username' => $combatResult['army1Username'],
							'army2Username' => $combatResult['army2Username'],
							'winner' => $combatResult['winner'],
							'army1Units' => $combatResult['army1Units'],
							'army2Units' => $combatResult['army2Units']
						),
						'combats'
		); // Stvori entry u bazi
		
		// Ako je uspjelo, počni unositi evente s primarnim ključem combatID iz prošlog unosa.
		if( is_numeric( $insertID ) )
		{
			foreach($combatResult['events'] as $log)
			{
				$insertID2 = $this->db->insert(
							array(
								'combatID' => $insertID,
								'duration' => $log['eventresult']['duration'],
								'army1Units' => $log['eventresult']['army1Units'],
								'army2Units' => $log['eventresult']['army2Units'],
								'eventCode' => $log['eventtype']
							),
							'combats_events'
				);
				
				// Ako se dogodio interfeerence u toj bitki, dodaj ga u bazu podataka u tablicu combats_events_interfeerences.
				if( ! empty( $log['interfeerence'] ) )
				{
					$insertID3 = $this->db->insert(
							array(
								'eventID' => $insertID2,
								'interfeerenceCode' => $log['interfeerence']['interfeerenceCode'],
								'interfeerenceVictim' => $log['interfeerence']['interfeerenceVictim'],
								'interfeerenceCasualties' => $log['interfeerence']['casualties'],
							),
							'combats_events_interfeerences'
					);
				}
			}
			
			// Vrati insert id.
			return $insertID;
		}
		
		return false;
	}
	
	// Počisti svu povijest svih igara.
	public function clearAllGamesHistory()
	{
		$this->db->delete('combats');
		$this->db->delete('combats_events');
		$this->db->delete('combats_events_interfeerences');
	}
}