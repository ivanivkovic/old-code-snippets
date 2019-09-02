<div id="edit_form">

	<input type="hidden" id="pic_id" value="<?php echo $pic_id ?>" />
	<?php echo $this -> loadString('description') ?>:</br> </br>
	
	<textarea maxlength="<?php echo PHOTO_DESCRIPTION_LIMIT ?>" id="description"><?php echo strip_tags($description) ?></textarea>
	
	</br></br>

	<div class="buttons">
		<input type="button" class="button" id="submit" value="Submit"/>
	</div>
	
</div>
<script>

$('#submit').live('click', function(){

	var request = $.ajax({
		url: '/ajax/photo_options/edit_info/',
		type: 'POST',
		data: {id : $('#pic_id').val(), description : $('#description').val()},
		dataType: 'html'
	});
	
	request.done(function(msg) {
		if(msg != 'success'){
			alert(msg);
		}else{
		
			$('.description .cleaner span').text('"' + limitString($('#description').val(), <?php echo PHOTO_DESCRIPTION_LIMIT ?>) + '"');
			$('#item_' + $('#pic_id').val() + ' .item_description').text('"' + limitString($('#description').val(), <?php echo BOX_DESCRIPTION_LIMIT ?>) + '"');
			
			if($('#description').val() !== ''){
				$('#item_' + $('#pic_id').val() + ' h6').text('"' + limitString($('#description').val(), <?php echo BOX_DESCRIPTION_LIMIT ?>) + '"');
			}
			
			if($('#edit').text() == '<?php echo $this -> loadString('add_description') ?>'){
				$('#edit').text('<?php echo $this -> loadString('edit') ?>');
			}
			
			closePopups();
		}
	});

	request.fail(function(jqXHR, textStatus){
		alert( "Request failed: " + textStatus);
	});
});

</script>