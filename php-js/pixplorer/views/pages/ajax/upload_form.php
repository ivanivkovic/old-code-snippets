<?php $this -> loadSrc('upload_form_validation.js') ?>
<?php $this -> loadSrc('dyn_select.js') ?>

<script>
$(document).ready(function(){

	$('#upload_real').fadeTo('fast', 0);
	
	$('#upload_real').change(function(){
		if(navigator.appName === 'Microsoft Internet Explorer'){
			var count = 1;
		}else{
			var count = $('#upload_real').files.length;
		}

		updateCounter(count);
	});
});
</script>

<div id="<?php echo $this -> registry -> router -> action ?>" class="data">
	<form action="<?php echo Conf::$page['upload_result'] ?>" onsubmit="return uploadFormValidation()" id="form_upload" enctype="multipart/form-data" method="POST">
	
		<div><?php echo $this -> loadString('popup_upload_headline') ?></div></br>
		<div><img alt="" title="<?php echo $this -> loadString('popup_select_photos') ?>" class="image_link" id="upload_fake" src="<?php echo Conf::$src['images'] ?>Photos.png"/></div>
		<div id="upload_real_container"><input type="file" name="pics[]" multiple="multiple" id="upload_real"/></div>
		<div id="counter"></div>
		</br>
		
		<select id="category" name="category">
			<option selected><?php echo $this -> loadString('popup_choose_category') ?></option> 
		<?php 
			while($fetch = $categories -> fetch(PDO::FETCH_ASSOC)){
				echo '<option ';
				if($fetch['cat_id'] == $criteria){
					echo 'selected ';
				}
				echo 'value="' . $fetch['cat_id']. '">' . $fetch['title'] . '</option>';
			} 
		?>
		</select>
		<?php include(Conf::$dir['widgets'] . 'world_select.php') ?>
		</br></br>
		
		<div class="buttons">
			<input type="submit" class="button" value="Upload"/>
		</div>
		<div id="errors"></div>
		
	</form>
	<?php # "data" div marks that this is ajax retrieved-data, so the jQuery fades it in. ?>

</div>