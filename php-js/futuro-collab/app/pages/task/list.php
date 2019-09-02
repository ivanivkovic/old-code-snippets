<? include(Conf::DIR_INCLUDES . 'htmlheader.php'); ?>

	</head>
	<body>
		<? include(Conf::DIR_INCLUDES . 'topheader.php'); ?>
		
		<div class="well">
			<div class="row-fluid">
				<div class="pull-right span5">
				
					<a 
						class="btn pull-right font-normal popup-trigger"
						data-id="getCreateForm/task/"
						data-onok="document.getElementById('form-new-task').submit()"
						data-onload="loadAutocomplete(); loadDatepicker('.datep');"
					>
						<i class="icon-plus-sign"></i> Dodaj novi zadatak
					</a>
					
					<a class="btn pull-right font-normal margin-right-10" href="<?= Core::$router->back ?>"><i class="icon-arrow-left"></i> <?= $txt['back'] ?></a>	
				</div>
	
				<div class="span5">
					<h4>Zadaci</h4>
				</div>
			</div>
			
			<hr/>
			
			<div class="row-fluid">
				<div class="span9">
					<form class="form-inline" style="margin-bottom: 0;">
						<select id="filter-user" onchange="setHash('userid', this.value)">
						
							<option value="" selected>Svi korisnici</option>
							
							<? foreach( $userList as $user ): ?>
							
								<option value="<?= $user['userid'] ?>"><?= $user['name'] . ' ' . $user['lastname']?></option>
								
							<? endforeach; ?>
						
						</select>
						
						<select id="filter-project" onchange="setHash('projectid', this.value)">
						
							<option value="" selected>Svi projekti</option>
						
							<? foreach( $projectList as $project ): ?> <option value="<?= $project['projectid'] ?>"><?= $project['title'] ?></option> <? endforeach; ?>
						
						</select>
						
						<select id="filter-priority" onchange="setHash('priority', this.value)">
						
							<option value="" selected>Svi prioriteti</option>
							
							<? foreach( modelTask::$priority as $p ): ?> <option value="<?= $p ?>"><?= $txt['task-priority'][ $p ] ?></option> <? endforeach; ?>
							
						</select>
						
						<select id="filter-client" onchange="setHash('clientid', this.value)">

							<option value="" selected>Svi klijenti</option>
							
							<? foreach( $clientList as $client ): ?> <option value="<?= $client['clientid'] ?>"><?= $client['name'] ?></option> <? endforeach; ?>
							
						</select>
						
						<label class="checkbox unselectable">
     						 <input type="checkbox" id="filter-expired"/> Van Roka
    					</label>
    					
						<label class="checkbox unselectable">
							<input type="checkbox" id="filter-status"/> Prikaži samo aktivne
						</label>
					</form>
					
				</div>
				
				<div class="span3">
					<a class="btn pull-right btn-primary" href="/task">Osvježi Filtere</a>
				</div>
			</div>
			
			<hr/>
			
			<div class="row-fluid">
				<div class="span12">
					<table class="table">
						<thead>
							<tr>
								<th>Radni nalog / ID</th>
								<th class="hidden-phone">Klijent</th>
								<th>Projekt</th>
								<th>Opis posla</th>
								<th class="hidden-phone hidden-tablet">Datum</th>
								<th class="hidden-phone hidden-tablet">Zaprimljeno</th>
								<th class="hidden-phone">Zaprimio</th>
								<th>Zadužen/i</th>
								<th>Rok</th>
								<th class="hidden-phone">Izvršeno</th>
								<th class="hidden-phone hidden-tablet">Ugovor</th>
								<th class="hidden-phone hidden-tablet">Utrošeno h</th>
								<th class="hidden-phone hidden-tablet">Račun</th>
								<th class="hidden-phone">Prioritet</th>
								<th>Akcije</th>
							</tr>
						</thead>
						<tbody id="pagination-content"></tbody>
					</table>
				</div>
			</div>

			<div class="row-fluid">
				<div class="span9 pull-left">
					<div class="pagination">
						<ul id="pagination"></ul>
					</div>
				</div>
				<div class="span3 pull-right legend text-right">
					<div>
						<div style="background-color: #dff0d8;"></div> <span>Obavljeni zadaci</span>
					</div>
					
					<div class="clear"></div>
					
					<div>
						<div style="background-color: #f2dede;"></div> <span>Zadaci van roka</span>
					</div>
				</div>
			</div>
		</div>
<?

$footer = '<script src="/src/js/page/task.js"></script>';

include(Conf::DIR_INCLUDES . 'htmlfooter.php');

?>