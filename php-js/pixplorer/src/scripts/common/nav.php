$(document).ready(function(){
	
	$('#menu_Upload').click(function(){
		loadAjaxPopup(600, 300, 'upload_form', '', '');
	});
	
	$('#background').click(function(){
		closePopups();
	});
	
	$('#profile_icon').mouseenter(
	
		function(e){
			var $this = $(this);
			var $menu = $('#profile_menu');
			
			var $flag = $('#nFlag');
			
			if($flag.hasClass('display_block')){
				$flag.css('display', 'none');
			}
			
			$this.removeClass('profile_no_hover');
			$this.addClass('profile_hover');
			
			$menu.removeClass('display_none');
			$menu.addClass('display_block');
		}

	);
	
	$('.profile_stack').mouseleave(
	
		function(e){
		
			if(!$(e.relatedTarget).hasClass('profile_stack')){
				var $icon = $('#profile_icon');
				var $menu = $('#profile_menu');
				
				var $flag = $('#nFlag');
			
				if($flag.hasClass('display_block')){
					$flag.css('display', 'block');
				}
				
				$icon.removeClass('profile_hover');
				$icon.addClass('profile_no_hover');
				
				$menu.removeClass('display_block');
				$menu.addClass('display_none');
			}
		}
		
	);
	
});

function begForEnter(){
	if(window.event.keyCode == 13){
		window.location = '<?php echo Conf::$page['search_keyword'] ?>' + encodeURIComponent($('#search_box').val());
	}
}