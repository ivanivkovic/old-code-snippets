function clearNotifications(){
	$.get(
		'<?php echo Conf::$page['ajax'] ?>clear_notif/',
		function(data){
			if(data.success == 'success'){
			
				updateNotifications(0);
			}
		},
		'json'
	);
}

function updateNotifications(count){

	if(count === 0){
		$('#nCount').html('');
		
	}else{
		$('#nCount').html('(' + count + ')');
	}
	
	var $flag = $('#nFlag');
	
	if(count === 0 && $flag.hasClass('display_block')){
	
		$flag.removeClass('display_block');
		$flag.addClass('display_none');
	
	}
	
	if(count != 0 && $flag.hasClass('display_none')){
	
		$flag.removeClass('display_none');
		$flag.addClass('display_block');
	
	}
	
	$('#nFlag').html(count);
	
}

function updateSite(){
	$.get(
		'<?php echo Conf::$page['ajax'] ?>global_update/',
		function(data){
			
			if(data.notifications != 0){
				updateNotifications(data.notifications);
			}
			
		},
		'json'
	);
}

$(document).ready(function(){
	global_interval = <?php echo PERIODICAL_UPDATE_INTERVAL ?>;
	setInterval(updateSite, global_interval);
});
