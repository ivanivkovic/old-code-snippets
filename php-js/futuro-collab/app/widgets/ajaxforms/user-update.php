<form id="user-update" method="POST" class="form">

	<input type="hidden" name="action" value="update"/>
	<input type="hidden" name="userid" value="<?= $data['userid'] ?>"/>
	
	<label for="name">Ime <span class="asterisk">*</span></label>
	<input type="text" name="name" value="<?= $data['name'] ?>" required/>
	
	<label for="lastname">Prezime <span class="asterisk">*</span></label>
	<input type="text" name="lastname" value="<?= $data['lastname'] ?>" required />
	
	<label for="username">Korisničko Ime <span class="asterisk">*</span></label>
	<input type="text" name="username" value="<?= $data['username'] ?>" required/>
	
	<label for="phone">Telefon</label>
	<input type="text" name="phone" value="<?= $data['phone'] ?>" />
	
	<label for="role">Uloga <span class="asterisk">*</span></label>
	<input type="text" name="role" value="<?= $data['role'] ?>" required/>
	
	<label for="einfo">Internetski kontakti</label>
	<textarea name="einfo"><?= $data['einfo'] ?></textarea>
	
	<label for="level">Razina korisnika <span class="asterisk">*</span></label>
	
	<select name="level" required>
		
		<? foreach( self::$userTypes as $type ): ?>
			
			<option value="<?= $type ?>" <? if( $type == $data['level'] ){ echo 'selected';} ?>><?= libTemplate::txt('usertype-' . $type ) ?></option>
			
		<? endforeach; ?>
		
	</select>
	
	<label for="level">Aktivnost računa</label>
	
	<select name="status">
		<option value="0" <? if( $data['status'] == 0){ echo 'selected';} ?>>Neaktivan</option>
		<option value="1" <? if( $data['status'] == 1){ echo 'selected';} ?>>Aktivan</option>
	</select>
	
	<input type="submit" class="hide submit"/>

</form>