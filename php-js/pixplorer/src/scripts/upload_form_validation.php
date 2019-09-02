function uploadFormValidation(){
	
	<?php # Checks are ordered by priority, and are displayed as such too. ?>
	
	counter = 0;
	var error_msg = [];
	var formats_allowed = ['jpg', 'jpeg', 'gif', 'png'];
	
	var $domobj = $('#upload_real');
	
	// IE has only single file uploader
	if(navigator.appName === 'Microsoft Internet Explorer'){
		var files = $domobj.val();
	}else{
		var files = $domobj[0].files;
	}
	
	if(typeof files === 'undefined' || files.length === 0){
		error_msg[counter] = '<?php echo $template -> loadString('uplform_no_files') ?>';
		++counter;
	}
	
	// One file IE validation.
	if(navigator.appName === 'Microsoft Internet Explorer'){
		
		parts = files.split('.');
		key = parts.length-1;
		ext = parts[key];
		if($.inArray(ext.toLowerCase(), formats_allowed) === -1){
			error_msg[counter] = '<?php echo $template -> loadString('uplform_file_format') ?>';
			++counter;
		}
		
	}else{
		// Multiple file/one file xbrowser validation.
		for (var i = 0; i < files.length; i++)
		{
			parts = files[i].name.split('.');
			ext = parts[parts.length-1];
			
			if($.inArray(ext.toLowerCase(), formats_allowed) === -1){
				error_msg[counter] = '<?php echo $template -> loadString('uplform_file_format') ?>';
				++counter;
			}
		}
	}
	
	// Information input validation.
	if(!$.isNumeric($('#category').val())){
		error_msg[counter] = '<?php echo $template -> loadString('uplform_select_category') ?>';
		++counter;
	}
	
	if(!$.isNumeric($('#subcategory').val())){
		error_msg[counter] = '<?php echo $template -> loadString('uplform_select_subcategory') ?>';
		++counter;
	}
	
	if(!$.isNumeric($('#city').val())){
		error_msg[counter] = '<?php echo $template -> loadString('uplform_select_location') ?>';
		++counter;
	}
	
	if(error_msg.length !== 0){
		$('#errors').text(error_msg[0]);
		return false;
	}
	
	return true;
}

function updateCounter(count){
	var $domobj = $('#counter');
	$domobj.text(count + ' <?php echo $template -> loadString('upload_images_chosen') ?>');
	$domobj.fadeIn(400).css('display', 'inline-block');
}

$(document).ready(function(){

	$('#upload_fake').click(function(){
		$('#upload_real').click();
	});
	
	$('#upload_real').change(function(){
		var $domobj = $('#upload_real');
		if(navigator.appName === 'Microsoft Internet Explorer'){
			var count = 1;
		}else{
			var count = $domobj[0].files.length;
		}

		updateCounter(count);
	});
});