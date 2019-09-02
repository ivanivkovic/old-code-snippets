<form action="" class="form" id="client-update" method="POST">
	
	<input type="hidden" name="action" value="update"/>
	<input type="hidden" name="clientid" value="<?= $data['clientid'] ?>"/>
	
	<label for="name">Ime <span class="asterisk">*</span></label>
	<input type="text" name="name" value="<?= $data['name'] ?>"/>
	
	<label for="phone">Broj Telefona</label>
	<input type="text" name="phone" value="<?= $data['phone'] ?>"/>
	
	<label for="email">email</label>
	<input type="text" name="email" value="<?= $data['email'] ?>"/>
	
	<label for="address">Adresa</label>
	<input type="text" name="address" value="<?= $data['address'] ?>" />
	
	<label for="info">Info <span class="asterisk">*</span></label>
	<textarea name="info" class="long"><?= $data['info'] ?></textarea>
	
	<label for="contract">Ugovor o održavanju</label>
	<select name="contract">
		<option value="1" <? if( $data['contract'] == 1 ){ echo 'selected'; } ?>>Da</option>
		<option value="0" <? if( $data['contract'] == 0 ){ echo 'selected'; } ?>>Ne</option>
	</select>
	
	<input type="submit" class="hidden"/>
</form>