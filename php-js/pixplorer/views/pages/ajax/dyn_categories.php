<select name="subcategory" id="subcategory">
	<option selected><?php echo $this -> loadString('popup_subcategory') ?></option>
<?php
	while($fetch = $categories -> fetch(PDO::FETCH_ASSOC))
	{
		echo '<option value="' . $fetch['cat_id'] . '">' . $fetch['title'] . '</option>';
	}
?>
</select>

