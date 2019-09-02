<? include(Conf::DIR_INCLUDES . 'htmlheader.php'); ?>
	</head>
	<body>
	
		<? include(Conf::DIR_INCLUDES . 'topheader.php'); ?>
		
		 <div class="row-fluid">
        	<div class="span12">
            	<div class="well">
                   	<div class="row">
		                <div class="span5 pull-right">
						
							<? if(Core::$user->level !== 2): ?>
							
								<a class="btn pull-right font-normal delete" data-id="<?= $clientData['clientid'] ?>" href="#"><i class="icon-remove"></i> Izbriši klijenta</a>
								<a class="btn pull-right margin-right-10 font-normal edit" data-id="<?= $clientData['clientid'] ?>" href="#"><i class="icon-edit"></i> Uredi klijenta</a>
							
							<? endif; ?>
							
							<a class="btn pull-right font-normal margin-right-10" href="<?= Core::$router->back ?>"><i class="icon-arrow-left"></i> <?= $txt['back'] ?></a>

						</div>
						<div class="span5 pull-left">
							<h4>Klijent <?= $clientData['name'] ?></h4>
						</div>
					</div>
					
					<br/>
					
		            <div class="row-fluid">
		                <div class="span12">
		                	<table class="table">
								<tr>
									<th width="20%">Ime i prezime</th>
									<td><?= $clientData['name'] ?></td>
								</tr>
								
								<tr>
									<th width="20%">Informacije</th>
									<td><?= libString::UrlToA( $clientData['info'], true ) ?></td>
								</tr>
								
								<tr width="20%">
									<th>Projekti</th>
									<td>
										<ul>
										
											<? 
												$c = 0;
												
												foreach( $clientData['projects'] as $project ):
													
													if( ! ( $c % 6 ) && $c !== 0  )
													{
														?>
														
														</ul>
														<ul>
														
														<?php
													}
											?>
												<li><a target="_blank" href="/project/<?= $project['projectid'] ?>"><?= $project['title'] ?></a></li>
												
											<? ++$c; endforeach; ?>
								
										</ul>
									</td>
								</tr>
								
								<tr>
									<th width="20%">Broj telefona</th>
									<td><?= $clientData['phone'] ?></td>
								</tr>
								
								<tr>
									<th width="20%">Adresa</th>
									<td><?= $clientData['address'] ?></td>
								</tr>
								
								<tr>
									<th width="20%">email</th>
									<td><a href="<?= $clientData['email'] ?>"><?= $clientData['email'] ?></a></td>
								</tr>
								
								<tr>
									<th width="20%">Ugovor o održavanju</th>
									<td><?= $clientData['contract'] === '1' ? 'Da' : 'Ne' ?></td>
								</tr>
								
								<tr>
									<th width="20%">Nedovršeni zadaci</th>
									<td><a href="/task#clientid=<?= $clientData['clientid'] ?>;status=1"><?= $clientData['active-tasks'] ?></a></td>
								</tr>
								
							</table>
		                </div>
					</div>
				</div>
		 	</div>
		 </div>

<? $footer = '<script src="/src/js/page/client.js"></script>'; ?>
<? include(Conf::DIR_INCLUDES . 'htmlfooter.php'); ?>
