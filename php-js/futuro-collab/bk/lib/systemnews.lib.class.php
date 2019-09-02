<?php

/**
* 
* @desc
* Fajlovi za akcije: (includes/newsmessages)
* 
* // Loginovi
* 0 => korisnik se prijavio
* 1 => korisnik se odjavio
* 
* // Klijenti
* 2 => korisnik je dodao klijenta
* 3 => korisnik je izbrisao klijenta
* 5 => korisnik je izmijenio klijenta
* 
* // Projekti
* 4 => korisnik je izbrisao projekt
* 6 => korisnik je stvorio projekt
* 7 => korisnik je promijenio status projekta
* 8 => korisnik je izmijenio projekt
* 9 => korisnik je dodao voditelja projekta
* 10 => uklonio voditelja
* 11 => objavio
* 12 => stvorio korisnika
* 
*/


# Kopirati za lakše pozivanje metode
# libSystemNews::addNews( 0, array( 'subjectid' => , 'actiontype' => 0, 'subjecttype' => '', 'additional' => '' ));

class libSystemNews
{
	public static $actionTypes = array(
									0 => 'Izmjena Podataka',
									1 => 'Prijave i Odjave',
									2 => 'Upravljanje Projektima',
									3 => 'Objave'
								);
	
	/**
	* @param int $newscode kod vijesti, s vrha fajla
	* @param array $newsdata nužne informacije i s opcionalnim
	*/
	public static function addNews($newscode, $data = array())
	{
		// Dodaj vijesti.
		if( !empty ( $data ) )
		{
			if(
				isset( $data['subjectid'] ) &&
				isset( $data['actiontype'] ) &&
				isset( $data['subjecttype'] )
			)
			{
				return Core::$db->insert(
									array(
										'actorid' => isset( $data['actorid'] ) ? $data['actorid'] : Core::$user->id, // actor id je ulogirani korisnik po defaultu.
										'subjectid' => $data['subjectid'],
										'subjecttype' => $data['subjecttype'],
										'newscode' => $newscode,
										'actiontype' => $data['actiontype'],
										'additional'  => isset( $data['additional'] ) ? $data['additional'] : '' ,
										'additional2' => isset( $data['additional2'] ) ? $data['additional2'] : '' ,
										'additional3' => isset( $data['additional3'] ) ? $data['additional3'] : '',
										'time' => libDateTime::Time()
									),
									'log'
				);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	// Paginator
	public static function getNumPages( $param )
	{
		$limit = $_POST['limit'];
		
		$actiontype = isset( $_POST['filters']['filter'] ) ? $_POST['filters']['filter'] : '';
		$dateString = isset( $_POST['filters']['date'] ) ? $_POST['filters']['date'] : '';
		
		$timeRange =  libTemplate::convertTimeStringRange( $dateString );
		
		$sql = 'SELECT CEIL( COUNT(newsid) / ' . $limit . ' ) AS numpages, COUNT(newsid) AS numresults FROM ?log ';
		
		if( ! empty( $_POST['filters'] ) && isset( $_POST['filters'][0]['name'] ) )
		{
			if( isset( $_POST['filters'][0]['name'] ) && $_POST['filters'][0]['name'] == 'userid' )
			{
				$sql .= 'WHERE actorid="' . $_POST['filters'][0]['value'] . '" AND time > ' . $timeRange['startDay'] . ' AND time < ' . $timeRange['endDay'];
			}
		}
		else
		{
			if($actiontype === '')
			{
				$sql .= 'WHERE time > ' . $timeRange['startDay'] . ' AND time < ' . $timeRange['endDay'];
			}
			else
			{
				$sql .= 'WHERE actiontype="' . $actiontype . '" AND time > ' . $timeRange['startDay'] . ' AND time < ' . $timeRange['endDay'];
			}
		}
		
		$data = Core::$db->fetchSQL($sql, true);
		
		return array( 'numresults' => $data['numresults'], 'numpages' => $data['numpages'] );
	}
	
	public static function getPage( $param )
	{
		$limit = $_POST['limit'];
		$page = $_POST['page'];
		
		$actiontype = isset( $_POST['filters']['filter'] ) ? $_POST['filters']['filter'] : '';
		$dateString = isset( $_POST['filters']['date'] ) ? $_POST['filters']['date'] : '';
		
		$timeRange = libTemplate::convertTimeStringRange( $dateString );
		
		$offset = ( $limit * $page );
		
		if( ! empty( $_POST['filters'] ) && isset( $_POST['filters'][0]['name'] ))
		{
			if( isset( $_POST['filters'][0]['name'] ) && $_POST['filters'][0]['name'] == 'userid' )
			{
				$query = 'SELECT * FROM ?log WHERE actorid="' . $_POST['filters'][0]['value'] . '" AND time < ' . $timeRange['endDay'] . ' AND time > ' . $timeRange['startDay'] . ' ORDER BY time DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
			}
		}
		else
		{
			if( $actiontype === '' )
			{
				$query = 'SELECT * FROM ?log WHERE time < ' . $timeRange['endDay'] . ' AND time > ' . $timeRange['startDay'] . ' ORDER BY time DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
			}
			else
			{
				$query = 'SELECT * FROM ?log WHERE time < ' . $timeRange['endDay'] . ' AND time > ' . $timeRange['startDay'] . ' AND actiontype="' . $actiontype . '" ORDER BY time DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
			}
		}
		
		$data = Core::$db->fetchSQL($query);
		
		if($data === false)
		{
			return array();
		}
		else
		{
			foreach( $data as &$item )
			{
				// Učitaj dodatne podatke.
				$item['time-string'] = libTemplate::formatTimeString( $item['time'] );
				$item['time-tag'] = libTemplate::formatTimeTag( $item['time'] );
				
				$item['actor'] = (object)modelUserData::getData( $item['actorid'], array('userid' => 'id', 'name', 'lastname', 'level', 'username') );
				$item['actor']->title = libTemplate::txt( 'usertype-' . $item['actor']->level );
				$item['actor']->fullname = $item['actor']->name . ' ' . $item['actor']->lastname;
				
				$class = 'model' . ucfirst($item['subjecttype']);
				
				if( ! class_exists( $class ) )
				{
					$class = 'lib' . ucfirst($item['subjecttype']);
				}
				
				if( $item['subjecttype'] != '' )
				{
					$item['subject-anchor'] = $class::exists( $item['subjectid'] ) === true ? 'href="/' . $item['subjecttype'] . '/' . $item['subjectid'] . '"' : 'href="#" title="Sadržaj više ne postoji"';
				}
			}
		}
		
		include('app/includes/pagination/systemnews-list.php');
	}
	
	// Ispiši vijest, za raličiti newscode različite vijesti.
	public static function printNewsItem($data)
	{
		echo '<p><span class="label label-info">' . $data['time-string'] . '</span>';
		
		switch($data['newscode'])
		{
			case 0;
			
			?>
			
				<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> se prijavio u sustav.
			</p>
			
			<?php
			
			break;
			
			
			
			case 1;
			?>
				<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> se odjavio iz sustava.
			</p>
			
			<?php
			break;
			
			
			
			case 2;
			?>
				<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> je stvorio novog klijenta <a <?= $data['subject-anchor'] ?>><?= $data['additional']?></a>.
			</p>
			
			<?php
			break;
			
			
			
			case 3;
			?>
				<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> je izbrisao klijenta <a <?= $data['subject-anchor'] ?>><?= $data['additional'] ?> (#<?= $data['subjectid'] ?>)</a>.
			</p>
			
			<?php
			break;
			
			
			
			case 4;
			?>
				<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> je izbrisao projekt <a <?= $data['subject-anchor'] ?>><?= $data['additional']?></a>.
			</p>
			
			<?php
			break;
			
			
			
			case 5;
			?>
				<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> je izmijenio klijenta <a <?= $data['subject-anchor'] ?>><?= $data['additional']?></a>.
			</p>
			
			<?php
			break;
			
			
			
			case 6;
			?>
				<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> je dodao projekt <a <?= $data['subject-anchor'] ?>><?= $data['additional']?></a>.
			</p>
			
			<?php
			break;
			
			
			
			case 7;
			?>
			
			<? if( $data['additional'] == '2' ): ?>
			
					<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> je premjestio projekt <a <?= $data['subject-anchor'] ?>><?= $data['additional2']?></a> u <a href="/project/#2">arhivu dovršenih projekata</a>.
				
				<? endif; if( $data['additional'] == '1' ): ?>
				
					<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> je premjestio projekt <a <?= $data['subject-anchor'] ?>><?= $data['additional2']?></a> u <a href="/project/#1">arhivu nedovršenih projekata</a>.
				
				<? endif; if( $data['additional'] == '0' ): ?>
				
					<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> je aktivirao arhivirani projekt <a <?= $data['subject-anchor'] ?>><?= $data['additional2']?></a>.
				
				<? endif; ?>
				
			</p>
		
			<?php
			break;
			
			
			
			case 8;
			?>
				<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> je izmijenio projekt <a <?= $data['subject-anchor'] ?>><?= $data['additional']?></a>.
			</p>
			
			<?php
			break;
			
			
			
			case 9;
			
			
			?>
				<a href="/user/<?= $data['actor']->username ?>"><?= $data['actor']->fullname ?></a> je dodao voditelja
				
					<? $userdata = modelUserData::getData($data['additional2'], array('name', 'lastname')) ?>
					
					<a href="/user/<?= $data['additional2'] ?>"><?= $userdata['name'] . ' ' . $userdata['lastname'] ?></a>
					
					 u projekt <a <?= $data['subject-anchor'] ?>><?= $data['additional']?></a>.
			</p>
			
			<?php
			break;
			
			
			
			
			case 10;
			?>
				<a href="/user/<?= $data['actor']->id ?>"><?= $data['actor']->fullname ?></a> je uklonio voditelja
				
					<? $userdata = modelUserData::getData($data['additional2'], array('name', 'lastname')) ?>
					
					<a href="/user/<?= $data['additional2'] ?>"><?= $userdata['name'] . ' ' . $userdata['lastname'] ?></a>
					
					 iz projekta <a <?= $data['subject-anchor'] ?>>	<?= $data['additional']?>
				</a>.
			</p>
			
			<?php
			
			
			break;
			
			case 11;
			?>
				<a href="/user/<?= $data['actor']->id ?>"><?= $data['actor']->fullname ?></a> je objavio:
				
					<? if( $data['additional2'] !== '' ): ?>
						
						<a target="_blank" href="<?= $data['additional2'] ?>">
						
					<? endif; ?>
				
					<span class="announcement">"<?= $data['additional'] ?>".</span>
					
					<? if( $data['additional2'] !== '' ): ?>
						
						</a>
						
					<? endif; ?>
			</p>
			
			<?php
			
			break;
			
			
			case 12;
			
				$userData = modelUserData::getData( $data['subjectid'], array('name', 'lastname') ); ?>
			
				<a href="/user/<?= $data['actor']->id ?>"><?= $data['actor']->fullname ?></a> je stvorio korisnika <a href="/user/<?= $data['subjectid']?>"><?= $userData['name'] . ' ' . $userData['lastname'] ?></a>.</p>
			
			<?php
			
			break;
			
			case 13;
			
				$userData = modelUserData::getData( $data['subjectid'], array('name', 'lastname') ); ?>
			
				<a href="/user/<?= $data['actor']->id ?>"><?= $data['actor']->fullname ?></a> 
					je izmijenio korisnika <a href="/user/<?= $data['subjectid']?>"><?= $userData['name'] . ' ' . $userData['lastname'] ?></a>.</p>
				
			<?php
			
			break;
			
			case 14;
			
				$taskData = modelTask::getData($data['subjectid'], array('description', 'projectid'));
				$projectData = modelProject::getData($taskData['projectid'], array('title'));
				
				if( empty( $projectData )):
				{
					?>
					
					<a href="/user/<?= $data['actor']->id ?>"><?= $data['actor']->fullname ?></a> je stvorio zadatak koji više ne postoji</a>.</p>
					
					<?php
				}
				else:
				
				?>
				
				<a href="/user/<?= $data['actor']->id ?>"><?= $data['actor']->fullname ?></a> je stvorio zadatak <a <?= $data['subject-anchor']?>>
					<?= $taskData['description']  ?> 
				</a>
				iz projekta <a href="/project/<?= $taskData['projectid']?>"><?= $projectData['title'] ?></a>.</p>
				
			<?php
			
				endif;
				
			break;
			
			case 15;
			
				$taskData = modelTask::getData($data['subjectid'], array('description'));
				
			?>
					<a href="/user/<?= $data['actor']->id ?>"><?= $data['actor']->fullname ?></a> je izmijenio zadatak 
						<a <?= $data['subject-anchor']?>"><?= $taskData['description'] ?></a>.
				</p>
			
			<?php
			
			break;
			
			case 16;
			
			?>
					<a href="/user/<?= $data['actor']->id ?>"><?= $data['actor']->fullname ?></a> je izbrisao zadatak 
						<a href="#" title="Zadatak više ne postoji"><?= $data['additional']  ?></a>
					.
				</p>
			
			<?php
			
			break;
		}
	}
}