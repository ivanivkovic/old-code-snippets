function postComment(pic_id, content){

	var request = $.ajax({
		url: '<?php echo Conf::$page['ajax'] ?>post_comment/',
		type: 'POST',
		data: { pic_id: pic_id, content: content },
		dataType: 'json'
	});

	request.fail(function(jqXHR, textStatus){
		alert('Request fail: ' + textStatus);
	});

	request.done(function(data){
		if(!data.error){
			$('.overview').prepend('<div class="comment" style="display: none;" id="com_' + data.comm_id + '"><a class="fl user_pic" href="<?php echo Conf::$page['profile_view'] ?>' + data.user_id + '"><img alt="" alt="" src="' + $('#my_pic').attr('src') + '"/></a><div class="fl content"><a class="user_link" href="<?php echo Conf::$page['profile_view'] ?>' + data.user_id + '">' + data.fullname + '</a> <span>' + $('#comment').val() + '</span></div><div class="fr"><img alt="" class="com_close" data-id="' + data.comm_id + '" src="<?php echo Conf::$src['images'] ?>close2.png"/></div><div class="cleaner"></div></div>');
			$('#com_' + data.comm_id).slideDown(200);
			$('#comment').val('');
			$('#scrollbar1').tinyscrollbar();
		}else{
				<?php
				if(MODE === 'DEVELOPMENT'){
					echo 'alert(data.error);';
				} ?>
		}
	});
}

function deleteComment(id){
	var request = $.ajax({
		url: '<?php echo Conf::$page['ajax'] ?>del_comment/',
		type: 'POST',
		data: { comm_id: id },
		dataType: 'json'
	});
	
	request.fail(function(jqXHR, textStatus){
		alert('Request fail: ' + textStatus);
	});
				
	request.done(function(data){
		if(data.success){
			$('#com_' + id).slideUp(200, function(){ $('#com_' + id).remove() });
		}
		$('#scrollbar1').tinyscrollbar();
	});
}

$(document).ready(function(){
			
	
	$('#comment').live('keyup', function(e){
		if(e.keyCode == 13 && $('#comment').val() != ''){
			postComment($('#image').attr('data-pic_id'), $('#comment').val());
		}
	});
	
	$('.com_close').live('click', function(){

		var answer = confirm('<?php echo $template -> loadString('are_sure_delete_comment') ?>');
		if(answer){
			id = $(this).attr('data-id');
			deleteComment(id);
		}
		
	});
	
	$('#left').live('mouseenter', 
		function(){
			$('#bottom').fadeIn(100);
		}
	);
	
	$('#left').live('mouseleave', 
		function(){
			$('#bottom').fadeOut(100);
		}
	);
	
	$('#bottom ul li').live('mouseover',
		function(){
			$(this).addClass('option_hover');
		}
	);
	
	$('#bottom ul li').live('mouseout',
		function(){
			$(this).removeClass('option_hover');
		}
	);
	
	$('.popup2 *').live('click', function(e){
		e.stopPropagation();
	});
	
	$('#favoritesToggle').live('click', function(){
		$this = $(this);
		$counter = $('#counter');
		number = parseInt($counter.text());
		$.get(
			'<?php echo Conf::$page['ajax'] ?>togglePhotoFavorites/' + $this.attr('data-pic_id') + '/' + $this.attr('data-boolean'),
			function(data){
				switch(data){
					default:
						alert(data);
					break;
					
					case 'success_added':
						if(isNaN(number)){
							$counter.text(1);
							$('#item_' + $this.attr('data-pic_id') + ' .item_stats_right span').text(1);
						}else{
							num = number + 1;
							$('#item_' + $this.attr('data-pic_id') + ' .item_stats_right span').text(num);
							$counter.text(num);
						}
						$this.removeClass('not_favorite');
						$this.addClass('favorite');
						$this.attr('title', '<?php echo $template -> loadString('remove_from_fav') ?>');
						$this.attr('data-boolean', 'true');
					break;
					
					case 'success_removed':
						num = number - 1;
						$('#item_' + $this.attr('data-pic_id') + ' .item_stats_right span').text(num);
						if(num == 0){ num = ''; }
						$counter.text(num);
						$this.removeClass('favorite');
						$this.addClass('not_favorite');
						$this.attr('title', '<?php echo $template -> loadString('add_to_fav') ?>');
						$this.attr('data-boolean', 'false');
					break;
				}
			},
			'html'
		);
	});
	
});

<?php /* OPTIONS */ ?>
$('#options li a').live('click', function(){
	action = $(this).attr('data-action');
	switch(action){
		case 'delete':
			var ans = confirm('<?php echo $template -> loadString('are_sure_delete_photo') ?>');
			if(ans === true){
				$.get(
				'<?php echo Conf::$page['ajax'] ?>photo_options/delete/' + $('#image').attr('data-pic_id'),
				function(data){
					switch(data){
						default:
							alert(data);
						break;
						case 'success':
						
							if($('#container').length != 0){
								$('#item_' + $('#image').attr('data-pic_id')).remove();
								var wall = new Masonry( document.getElementById('container'), {isFitWidth: true , isAnimated: false});
								wall.reload();
							}
							closePhotoViewerDynamic();
							
						break;
					}
				},
				'html'
				);
			}
		break;
		
		case 'edit':
			loadAjaxPopup(500, 250, 'photo_options', 'edit', $('#image').attr('data-pic_id'));
		break;
		
	}
});


<?php
/*
JAVASCRIPT: (one day)
if (typeof document.cancelFullScreen != 'undefined' && document.fullScreenEnabled === true) {
 //do fullscreen stuff
}
*/
?>