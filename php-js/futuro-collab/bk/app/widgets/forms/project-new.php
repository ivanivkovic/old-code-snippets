<form class="form hidden" id="form-new-project" method="POST" enctype="multipart/form-data">

	<label for="title">Naslov Projekta <span class="asterisk">*</span></label>
	<input type="text" name="title" required/>

	<label for="description">Opis Projekta <span class="asterisk">*</span></label>
	<textarea name="description" required></textarea>
	
	<label for="title">Domena</label>
	<input type="text" name="domain" />

	<label for="title">Tip Projekta</label>
	<select name="type">
	
	<? foreach(modelProject::$types as $key => $type): ?>
		
		<option value="<?= $key ?>"><?= $txt['project-type'][$key] ?></option>
		
	<? endforeach; ?>
	
	</select>
	
	<label for="client">Klijent <span class="asterisk">*</span></label>
	<select name="client" required>
		
	<? foreach( $clientList as $client ): ?>
	
		<option value="<?= $client['clientid'] ?>"><?= $client['name'] ?></option>
		
	<? endforeach; ?>
	
	</select>
	
	<label for="users">Dodaj Voditelje</label>
	
	<? self::widget('autocomplete', array('limit' => 0, 'name' => 'usernames', 'autocompleteList' => $autocompleteList ) ); ?>
	<? self::widget('input-file', array('limit' => 0, 'name' => 'files', 'string' => 'Vezane Datoteke')); ?>
	
	<br/>
	
	<input type="submit" class="btn pull-left" value="Unos"/>
	<input type="hidden" name="action" value="insert" />
	
	<div class="clear"></div>
	<hr/>
	
</form>