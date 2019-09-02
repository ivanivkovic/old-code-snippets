<div id="<?php echo $this -> registry -> router -> action ?>" class="data"><?php # "data" div marks that this is ajax retrieved-data, so the jQuery fades it in. ?>
	<input type="hidden" id="cat" value="<?php echo $criteria ?>"/>
	<span id="main_selection"><?php echo $this -> loadString('popup_you_have_selected') ?> "<?php echo $category ?>":</span></br></br>
	
<?php 
	if(isset($categories)){
	
		echo '<select name="subcat" id="sel_category">';
		echo '<option selected>' . $this -> loadString('popup_choose_subcategory') . '</option>';
		
		while($fetch = $categories -> fetch(PDO::FETCH_ASSOC)){
			echo '<option value="' . $fetch['cat_id'].'">' . $fetch['title'] . '</option>';
		}
		
		echo '</select>';
		
	}

	include(Conf::$dir['widgets'] . 'world_select.php');
?>
	
	</br>
	</br>
	
	<div class="buttons">
		<a id="searchUrl" data-urlbase="<?php echo Conf::$page['search_categories'] ?>">
			<input type="button" class="button" value="<?php echo $this -> loadString('explore') ?>"/>
		</a>
	</div>
</div>
<script>createURL();</script>
