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
            			<? if( $taskData['status'] === '0' ): ?>
            				<div class="alert alert-success">Ovaj zadatak je <strong><a href="/task#status=0" style="color:inherit;">završen</a></strong>.</div>
            			<? elseif ( $taskData['deadline'] < strtotime('now') ) :?>
            			
            				<div class="alert alert-error">Ovaj zadatak je 
            					<strong>
            						<a href="/task#status=1;expired=1;" style="color:inherit;">u izradi i van roka</a>
            					</strong>.
            				</div>
            				
            			<? else: ?>
            				
            				<div class="alert alert-warning">Ovaj zadatak je <strong><a href="/task#status=1" style="color:inherit;">u izradi</a></strong>.</div>
            				
            			<? endif;?>
            			
               		</div>
               		
            		<div class="row-fluid tab-content" data-id="info">
						
						<div class="span3 pull-left">
							<h4>Zadatak <?= $taskData['order_code'] ?></h4>
						</div>
						
            			<div class="span6 pull-right">
            			
            				<? if( modelTask::isOwner($taskData['taskid'], Core::$user->id) || Core::$user->level === 0): ?>
							
								<a class="btn pull-right font-normal" onclick="deleteTaskSingle(<?= $taskData['taskid']?>);return false;" href="#">
									<i class="icon-remove"></i> Izbriši zadatak
								</a>
								
								<a class="btn pull-right margin-right-10 font-normal" onclick="editTaskSingle(<?= $taskData['taskid']?>);return false;" href="#">
									<i class="icon-edit"></i> Uredi zadatak
								</a>
								
								<? if( $taskData['status'] === '1' ): ?>
								
									<a class="btn pull-right margin-right-10 font-normal update-status" data-id="<?= $taskData['taskid'] ?>" href="#">
										<i class="icon-ok"></i> Završi
									</a>
								
								<? else: ?>
								
									<a class="btn pull-right margin-right-10 font-normal update-status" data-id="<?= $taskData['taskid'] ?>" href="#">
										<i class="icon-ok"></i> Uredi status
									</a>
								
								<?
								
								endif;
							endif;
							
							?>
							
							<a class="btn pull-right font-normal margin-right-10" href="<?= Core::$router->back ?>">
								<i class="icon-arrow-left"></i> <?= $txt['back'] ?>
							</a>
							<br/><br/>
							<br/>
							
						</div>
               		
						<table class="table">
							<tr>
								<th>Opis zadatka</th>
								<td><?= $taskData['description'] ?></td>
							</tr>
							
							<tr>
								<th>Status</th>
								<td><?= $txt['task-status'][$taskData['status']] ?></td>
							</tr>
							
							<tr>
								<th>Rok</th>
								<td><?= self::getFullDate( $taskData['deadline'] ); ?></td>
							</tr>
							
							<? if( $taskData['status'] === '0'): ?>
							
								<tr>
									<th>Datum završetka</th>
									<td><?= self::getFullDate($taskData['timefinished']) ?></td>
								</tr>
								
								<?
									if( $taskData['deadline'] > $taskData['timefinished'] ):
										
										$deadlineValue = '<i class="icon-ok"></i>';
										
									else:
										
										$deadlineValue = '<i class="icon-remove"></i>';
										
									endif;
								?>
								
								<tr>
									<th>Završeno u roku</th>
									<td><?= $deadlineValue ?></td>
								</tr>
								
							<? endif; ?>
							
							<tr>
								<th>Prioritet</th>
								<td><?= $txt['task-priority'][$taskData['priority']] ?></td>
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
								
									<? foreach($taskData['assigned'] as $key => $assigned): ?>
										<? if($key !== 0){ echo ', '; } ?>
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
								<th>Opis Zadatka</th>
								<td><?= libString::UrlToA( $taskData['description'], true ) ?></td>
							</tr>
							
							<tr>
								<th>Klijent</th>
								<td><a href="/client/<?= $taskData['client']['clientid'] ?>"><?= $taskData['client']['name'] ?></td>
							</tr>
								
							<tr>
								<th>Vezani dokumenti</th>
								
								<? if( ! empty( $taskData['files'] ) ): ?>
							
									<td>
										<ul>
											<? foreach( $taskData['files'] as $file ): ?>
												
												<li><a href="/download/?file=<?= $file['fileid'] ?>"><?= $file['filename'] ?></a></li>
												
											<? endforeach; ?>
										</ul>
									</td>
								
								<? else: ?>
								
									<td>Nema vezanih dokumenata.</td>
								
								<? endif; ?>
							
							</tr>
						</table>
					</div>
					<div class="tab-content" data-id="subtasks">
						
						<div class="row-fluid">
							<div class="span3 pull-left">
								<h4>Zadatak <?= $taskData['order_code'] ?></h4>
							</div>
							
							<div class="span6 pull-right">
								<a class="btn pull-right margin-right-10 font-normal" onclick="createNewSubTask(<?= $taskData['taskid']?>);return false;" href="#">
									<i class="icon-edit"></i> Novi podzadatak
								</a>
							</div>
						</div>
						
						<hr/>
						
						<div class="row-fluid">
							<div class="span12 sub-tasks-container">
							
								<? 
								
								if( ! empty($taskData['subTasks']) ): 
								
									foreach($taskData['subTasks'] as $subTask):
										
										include('app/includes/subtask.php');
									
									endforeach;
								
								else: 
									
									echo '<div id="no-subtasks">' . $txt[8] . '</div>';
								
								endif;
								
								?>
							
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

<? $footer = '<script src="/src/js/page/task.js"></script>'; ?>
<? include(Conf::DIR_INCLUDES . 'htmlfooter.php'); ?>