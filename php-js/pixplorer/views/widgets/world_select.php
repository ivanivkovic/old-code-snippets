<select id="country" name="country" class="world">
	<option selected><?php echo $this -> loadString('popup_choose_country') ?></option>
	<?php
	while($country = $countries -> fetch(PDO::FETCH_ASSOC))
	{
		echo '<option value="' . $country['countryID'] . '">' . $country['countryName'] . '</option>';
	}
?>
</select>