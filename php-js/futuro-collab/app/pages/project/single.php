<? include(Conf::DIR_INCLUDES . 'htmlheader.php'); ?>
		
	</head>
	<body>
		<? include(Conf::DIR_INCLUDES . 'topheader.php'); ?>
		
		
            <div class="row-fluid">
            	<div class="span3">
					<? include(Conf::DIR_INCLUDES . 'leftmenu.php'); ?>
				</div>
				<div class="span9">
            		<div class="well">
            			<div class="row-fluid">
            				<div class="span5 pull-right">
            				
            					<? if(Core::$user->level !== 2): ?>
								
									<a class="btn pull-right font-normal delete" data-id="<?= $projectData['projectid'] ?>" href="#"><i class="icon-remove"></i> Izbriši projekt</a>
									<a class="btn pull-right margin-right-10 font-normal edit" data-id="<?= $projectData['projectid'] ?>" href="#">
										<i class="icon-edit"></i> Uredi projekt
									</a>
								
								<? endif; ?>
								
								<a class="btn pull-right font-normal margin-right-10" href="<?= Core::$router->back ?>">
									<i class="icon-arrow-left"></i> <?= $txt['back'] ?>
								</a>
							</div>
                		</div>
                		
                		<h4>Projekt <?= $projectData['title'] ?></h4>
                		
                		<table class="table">
						<tr>
							<th>Naslov Projekta</th>
							<td><?= $projectData['title'] ?></td>
						</tr>
						
						<tr>
							<th>Status Projekta</th>
							<td><?= $projectData['active'] ?></td>
						</tr>
						
						<tr>
							<th>Datum</th>
							<td><time datetime="<?= $projectData['time-tag'] ?>"><?= $projectData['time-string'] ?></time></td>
						</tr>
						
						<tr>
							<th>Stvorio</th>
							<td><a href="/user/<?= $projectData['userid'] ?>"><?= $projectData['name'] . ' ' . $projectData['lastname']; ?></a></td>
						</tr>
						
						<tr>
							<th>Voditelji</th>
							
							<? if( ! empty ($projectData['managers']) ): ?>
							
							<td>
								<? foreach($projectData['managers'] as $key => $manager): ?><? if($key !== 0){ echo ', '; } ?>
									
								<a href="/user/<?= $manager['username'] ?>"><?= $manager['name'] . ' ' . $manager['lastname']; ?></a><? endforeach; ?>
							</td>
							
							<? else: ?>
							
							<td>
							
								Nema voditelja.
								
							</td>
							
							<? endif; ?>
						</tr>
						
						<tr>
							<th>Opis Projekta</th>
							<td><?= libString::UrlToA( $projectData['info'], true ) ?></td>
						</tr>
						
						<tr>
							<th>Klijent</th>
							<td><a href="/client/<?= $projectData['client']['clientid'] ?>"><?= $projectData['client']['name'] ?></td>
						</tr>
						
						<tr>
							<th>Tip Projekta</th>
							<td><?= $txt['project-type'][$projectData['type']] ?></td>
						</tr>
						
						<tr>
							<th>Domena</th>
							<td><?= libString::UrlToA($projectData['domain'], true); ?></td>
						</tr>
						
						<? if( ! empty( $projectData['files'] ) ): ?>
						
						<tr>
							<th>Vezani dokumenti</th>
							<td>
							
								<ul class="files">
								
									<? foreach( $projectData['files'] as $file ): ?>
										
										<li><a href="/download/?file=<?= $file['fileid'] ?>"><?= $file['filename'] ?></a></li>
										
									<? endforeach; ?>
								
								</ul>
							
							</td>
						</tr>
						
						<? else: ?>
						
							<tr>
								<th>Vezani dokumenti</th>
								<td>Nema vezanih dokumenata.</td>
							</tr>
						
						<? endif; ?>
					
					</table>
                		
				</div>
			</div>
		</div>
		
<?php $footer = '<script src="/src/js/page/project.js"></script>'; ?>
	
<? include(Conf::DIR_INCLUDES . 'htmlfooter.php'); ?>