<form class="form" id="project-update" method="POST" enctype="multipart/form-data">
	
	<input type="hidden" name="projectid" value="<?= $data['projectid'] ?>"/>
	
	<label for="title">Naslov Projekta <span class="asterisk">*</span></label>
	<input type="text" name="title" value="<?= $data['title'] ?>" required/>
	
	<label for="description">Opis Projekta <span class="asterisk">*</span></label>
	<textarea name="description" required><?= $data['info'] ?></textarea>
	
	<label for="users">Dodaj Voditelje</label>
	
	<? libTemplate::widget( 'autocomplete', array('limit' => 0, 'name' => 'usernames', 'autocompleteList' => $autocompleteList, 'value' => $autocompleteValues, 'curvalue' => $autocompleteCurrentValue ) ); ?>
	
	<label for="title">Domena</label>
	<input type="text" name="domain" value="<?= $data['domain'] ?>" />
	
	<label for="title">Tip Projekta</label>
	<select name="type">
	
	<? foreach(modelProject::$types as $key => $type): ?>
	
		<option value="<?= $key ?>" <? if($key == $data['type']){ echo 'selected'; } ?> ><?= libTemplate::txt('project-type', $key) ?></option>
	
	<? endforeach; ?>
	
	</select>
	
	<label for="client">Klijent <span class="asterisk">*</span></label>
	<select name="client" required>
	
	<? foreach( $clientList as $client ): ?>
		
		<option value="<?= $client['clientid'] ?>" <? if( $client['clientid'] == $data['clientid'] ): ?> selected <? endif; ?>><?= $client['name'] ?></option>
		
	<? endforeach; ?>
	
	</select>
	
	<? libTemplate::widget( 'input-file', array('limit' => 0, 'name' => 'files', 'string' => 'Vezane Datoteke' ) ); ?>
	
	<? if( !empty($files) ): ?>
		
		<br/>
		<label for="filesdelete[]">Brisanje podataka</label>
		
	<? endif; ?>
	
	<? libFile::printFilesListForDelete( $files ); ?>
	
	<input type="hidden" name="action" value="update" />
</form>
