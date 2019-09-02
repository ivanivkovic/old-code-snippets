<form id="form-new-task" action="/task" method="POST" class="form" enctype="multipart/form-data">

	<input type="hidden" name="action" value="insert"/>
	
	<label for="assigned">Zadužen</label>
	<? libTemplate::widget( 'autocomplete', array('limit' => 0, 'name' => 'assigned', 'autocompleteList' => $autocompleteList ) ); ?>
	
	<label for="description">Opis zadatka <span class="asterisk">*</a></label>
	<textarea name="description"></textarea>
	
	<label for="ordertype">Zaprimljeno <span class="asterisk">*</a></label>
	<select name="ordertype">
	
	<? foreach( modelTask::$orderType as $key ): ?>
	
		<option value="<?= $key ?>">
		
			<?= $txt['task-order'][$key] ?>
		
		</option>
	
	<? endforeach; ?>
	
	</select>
	
	<label for="recieverid">Zaprimio <span class="asterisk">*</a></label>
	<select name="recieverid">
	
		<? foreach( modelUserData::getUserList() as $user): ?>
		
			<option value="<?= $user['userid'] ?>" <? if( $user['userid'] == Core::$user->id ): echo 'selected'; endif; ?>>
			
				<?= $user['name'] . ' ' . $user['lastname'] ?>
				
			</option>
			
		<? endforeach; ?>
		
	</select>
	
	<label for="projectid">Projekt <span class="asterisk">*</a></label>
	<select name="projectid">
	
	<? foreach( modelProject::getActiveProjects() as $project ): ?>
	
		<option value="<?= $project['projectid'] ?>"><?= $project['title'] ?></option>
		
	<? endforeach; ?>
	
	</select>
	
	<label for="deadline">Rok <span class="asterisk">*</a></label>
	<input class="datep" type="text" name="deadline" value="Danas"/>
	
	<label for="priority">Prioritet</label>
	<select name="priority">
	
	<? foreach( modelTask::$priority as $key): ?>
	
		<option value="<?= $key ?>" <? if($key === 1){ echo 'selected'; } ?>><?= $txt['task-priority'][$key] ?></option>
	
	<? endforeach; ?>
	
	</select>
	
	<? libTemplate::widget( 'input-file', array('limit' => 0, 'name' => 'files', 'string' => 'Vezane datoteke', 'autocompleteList' => $autocompleteList ) ); ?>

</form>