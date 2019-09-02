<form id="form-new-client" class="form hidden" method="POST">

	<input type="hidden" name="action" value="insert"/>
	
	<label for="name">Ime <span class="asterisk">*</span></label>
	<input type="text" name="name" required/>
	
	<label for="phone">Broj Telefona</label>
	<input type="text" name="phone" />
	
	<label for="email">email</label>
	<input type="text" name="email" />
	
	<label for="address">Adresa</label>
	<input type="text" name="address" />
	
	<label for="info">Informacije <span class="asterisk">*</span></label>
	<textarea name="info" required></textarea>
	
	<label for="contract">Ugovor o održavanju</label>
	<select name="contract" class="pull-left margin-right-10">
		<option value="1">Da</option>
		<option value="0">Ne</option>
	</select>
	
	<input type="submit" class="btn font-normal pull-left" value="Unos"/>

<br />
<br />
</form>