<? include(Conf::DIR_INCLUDES . 'htmlheader.php'); ?>
	</head>
	<body>
		<? include(Conf::DIR_INCLUDES . 'topheader.php'); ?>
		<? print_r($taskData); ?>
		<div class="row-fluid">
            	<div class="span3">
					<? include(Conf::DIR_INCLUDES . 'leftmenu.php'); ?>
				</div>
				<div class="span9">
            		<div class="well">
            			<div class="row-fluid">
            				<div class="span6 pull-right">
            				
            					<? if(Core::$user->level !== 2): ?>
								
									<a class="btn pull-right font-normal delete" data-id="<?= $taskdata['taskid'] ?>" href="#"><i class="icon-remove"></i> Izbriši zadatak</a>
									
									<? if($taskData['status'] === '1'): ?>
									
										<a class="btn pull-right margin-right-10 font-normal edit" data-id="<?= $taskData['taskid'] ?>" href="#">
											<i class="icon-edit"></i> Uredi zadatak
										</a>
										
									<? endif; ?>
									
									<a class="btn pull-right margin-right-10 font-normal mark-done" data-id="<?= $taskData['taskid'] ?>" href="#">
										<i class="icon-ok"></i> Dovrši zadatak
									</a>
									
								<? endif; ?>
								
								<a class="btn pull-right font-normal margin-right-10" href="<?= Core::$router->back ?>"><i class="icon-arrow-left"></i> <?= $txt['back'] ?></a>
							</div>
                		</div>
                		
                		<h4>Zadatak <?= $taskData['order_code'] ?></h4>
                		
                		<table class="table">
						<tr>
							<th>Opis zadatka</th>
							<td><?= $taskData['description'] ?></td>
						</tr>
						
						<tr>
							<th>Status Zadatka</th>
							<td><?= $txt['task-status'][$taskData['status']] ?></td>
						</tr>
						
						<? $creatorData = modelUserData::getData($taskData['creatorid'], array('name', 'lastname')) ?>
						
						<tr>
							<th>Stvorio</th>
							<td>
								<a href="/user/<?= $taskData['creatorid'] ?>">
									<?= $creatorData['name'] . ' ' . $creatorData['lastname']; ?>
								</a>
							</td>
						</tr>
					
						<tr>
							<th>Zadužen/i</th>
							
							<? if( ! empty ($taskData['assigned']) ): ?>
							
							<td>
								
								<? foreach($taskData['assigned'] as $key => $assigned): ?><? if($key !== 0){ echo ', '; } ?>
								
									<a href="/user/<?= $assigned['username'] ?>"><?= $assigned['name'] . ' ' . $assigned['lastname']; ?></a>
								
								<? endforeach; ?>
								
							</td>
							
							<? else: ?>
							
							<td>
								Nema zaduženih korisnika za ovaj zadatak.
							</td>
							
							<? endif; ?>
						</tr>
						
						<tr>
							<th>Opis Projekta</th>
							<td><?= libString::UrlToA( $taskData['description'], true ) ?></td>
						</tr>
						
						<tr>
							<th>Klijent</th>
							<td><a href="/client/<?= $taskData['client']['clientid'] ?>"><?= $taskData['client']['name'] ?></td>
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
		
	<? $footer = '<script src="/src/js/page/task.js"></script>'; ?>
<? include(Conf::DIR_INCLUDES . 'htmlfooter.php'); ?>