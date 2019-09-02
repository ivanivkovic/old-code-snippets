<form id="form-new-user" method="POST" class="form hidden">
	
	<input type="hidden" name="action" value="insert"/>
	
	<label for="name">Ime <span class="asterisk">*</span></label>
	<input type="text" name="name" value="" required/>
	
	<label for="lastname">Prezime <span class="asterisk">*</span></label>
	<input type="text" name="lastname" value="" required />
	
	<label for="username">Korisničko Ime <span class="asterisk">*</span></label>
	<input type="text" name="username" value="" required/>
	
	<label for="phone">Telefon</label>
	<input type="text" name="phone" value="" />
	
	<label for="role">Uloga <span class="asterisk">*</span></label>
	<input type="text" name="role" value="" required/>
	
	<label for="einfo">Internetski kontakti</label>
	<textarea name="einfo"></textarea>
	
	<label for="level">Razina korisnika <span class="asterisk">*</span></label>
	
	<select name="level" required>
	
		<? foreach( modelUserData::$userTypes as $type ): ?>
			
			<option value="<?= $type ?>"><?= self::txt('usertype-' . $type ) ?></option>
			
		<? endforeach; ?>
		
	</select>
	
	<input type="submit" class="submit"/>

</form>