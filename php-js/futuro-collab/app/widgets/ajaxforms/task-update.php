<form id="task-update" method="POST" class="form" enctype="multipart/form-data">

	<input type="hidden" name="action" value="update"/>
	<input type="hidden" name="taskid" value="<?= $taskData['taskid'] ?>"/>

	<label for="assigned">Zadužen</label>
	<? 
		libTemplate::widget( 'autocomplete', array(
												'limit' => 0,
												'name' => 'assigned',
												'curvalue' => $autocompleteCurrentValue,
												'value' => $autocompleteValues,
												'autocompleteList' => $autocompleteList
												)
	); ?>

	<label for="description">Opis posla <span class="asterisk">*</a></label>
	<textarea name="description"><?= $taskData['description'] ?></textarea>

	<label for="ordertype">Zaprimljeno <span class="asterisk">*</a></label>
	<select name="ordertype">

	<? foreach( modelTask::$orderType as $key ): ?>
		
		<option value="<?= $key ?>" <? if( $taskData['ordertype'] == $key ): ?> selected="selected" <? endif; ?>>
			<?= $txt['task-order'][$key] ?>
		</option>
	
	<? endforeach; ?>
	
	</select>
	
	<label for="recieverid">Zaprimio <span class="asterisk">*</a></label>
	<select name="recieverid">
		<? foreach( modelUserData::getUserList() as $user): ?>
		
			<option value="<?= $user['userid'] ?>" <? if( $user['userid'] == $taskData['recieverid'] ): ?> selected="selected" <? endif;?>>
				<?= $user['name'] . ' ' . $user['lastname'] ?>
			</option>
			
		<? endforeach; ?>
	</select>
	
	<label for="projectid">Projekt <span class="asterisk">*</a></label>
	<select name="projectid">
		<? foreach( modelProject::getActiveProjects() as $project ): ?>
			<option value="<?= $project['projectid'] ?>" <? if( $project['projectid'] == $taskData['projectid'] ){ echo 'selected=\'selected\'';} ?>><?= $project['title'] ?></option>
		<? endforeach; ?>
	</select>
	
	<label for="deadline">Rok <span class="asterisk">*</a></label>
	<input class="datep" type="text" name="deadline" value="<?= libTemplate::getFullDate( $taskData['deadline'] ) ?>"/>
	
	<label for="priority">Prioritet</label>
	<select name="priority">
	
	<? foreach( modelTask::$priority as $key): ?>
	
		<option value="<?= $key ?>" <? if($key == $taskData['priority']){ echo 'selected'; } ?>><?= $txt['task-priority'][$key] ?></option>
	
	<? endforeach; ?>
	
	</select>
	
	<? libTemplate::widget( 'input-file', array('limit' => 0, 'name' => 'files', 'string' => 'Vezane datoteke' ) ); ?>
	
	<? if( ! empty($taskData['files']) ): ?>
	
		<br/>
		<label for="filesdelete">Brisanje podataka</label>
	
	<? endif; ?>
	
	<? libFile::printFilesListForDelete( $taskData['files'] ); ?>
</form>