function loadPopupViewer(img_id){
	$('#background2').animate({opacity: 1}, 0).fadeIn(0);
	$('body:not(#background2)').append('<div class="popup2"></div>');
	$('body').css('overflow', 'hidden');
	$viewer = $('#viewer_wrapper');
	$.get(
		'<?php echo Conf::$page['ajax'] ?>photo_viewer/' + img_id,
		function(data){
			$('.popup2').html(data);
		},
		'html'
	);
}

function closePopupViewer(){
	$('body').css('overflow', 'auto');
	$('#background2, .popup2').fadeOut(0);
	$('.popup2').remove();
}


function createNavigation(previous, next){

	$left_arrow = $('#left_arrow_container');
	$right_arrow = $('#right_arrow_container');

	if($('#container .item').length != 1){
		function Navigation(previous, next){
			if(typeof previous != 'undefined'){
				this.previous = previous;
			}else{
				this.previous = $('#container .item').last().attr('data-id');
			}
			if(typeof next != 'undefined'){
				this.next = next;
			}else{
				this.next = $('#container .item').first().attr('data-id');
			}
		}
		
		navigation = new Navigation(previous, next);
		
		if(typeof navigation.previous != 'undefined' || typeof navigation.next != 'undefined'){
			
			$left_arrow.attr('data-id', navigation.previous);
			$right_arrow.attr('data-id', navigation.next);
			
			$left_arrow.css('display', 'block');
			$right_arrow.css('display', 'block');
		}
	}
}

function updatePhoto(id){
	$.get(
		'<?php echo Conf::$page['ajax'] ?>update_photo/' + id,
		function(data){
			$('#image').attr('data-pic_id', data.id);
			$('#image').attr('src', data.src);
			vAlignMe('#image');
		},
		'json'
	);
	
}

function updateTopInfo(id){
	
	$.get(
		'<?php echo Conf::$page['ajax'] ?>update_info/' + id,
		function(data){
			$('.user a').attr('href', data.user_url);
			$('.user .user_pic').attr('src', data.nav_user_pic);
			$('.link').attr('href', data.user_url);
			$('.link').text(data.username);
			$('.wiki a').attr('href', data.wiki);
			$('#location_link').text(data.location);
			$('#location_link').attr('href', data.location_name);
			
			$('#category_link').text(data.category);
			$('#category_link').attr('href', data.category_link);
			$('.description .cleaner span').text(data.description);
		}, 
	'json'
	);
	
	
}

function updateLikes(id){
	$('.likes').animate({opacity: 0}, 200);
	$.get(
		'<?php echo Conf::$page['ajax'] ?>update_likes/' + id,
		function(data){ 
			$('.likes').html(data); 
			refreshLikes();
			setTimeout(function(){$('.likes').animate({opacity: 1}, 200);}, 400);
		}, 
	'html'
	);
}

function updateBottomPart(id){
	$.get(
		'<?php echo Conf::$page['ajax'] ?>update_bottom_part/' + id,
		function(data){
			$('#bottom').html(data);
		}, 
	'html'
	);
}

function updateComments(id){
	$.get(
		'<?php echo Conf::$page['ajax'] ?>update_comments/' + id,
		function(data){
			$('#comments_container').html(data);
			$('#comment_mask').fadeTo('fast', 0.85);
			$('#scrollbar1').tinyscrollbar();
		}, 
	'html'
	);
}

function replaceViewerData(id){
	createNavigation($('#item_' + id).prev().attr('data-id'), $('#item_' + id).next().attr('data-id'));
	updateTopInfo(id);
	refreshLikes(id);
	updateComments(id);
	updatePhoto(id);
	updateBottomPart(id);
	updateLikes(id);
}

$('.arrow_container .arrow').live('click',
	function(){
		id = $(this).parent().attr('data-id');
		if(typeof id != 'undefined'){
			replaceViewerData(id);
		}
	}
);

$('.arrow_container').live('click',
	function(){
		id = $(this).attr('data-id');
		if(typeof id != 'undefined'){
			replaceViewerData(id);
		}
	}
);

$(document).live('keydown',
	function(e){
		if(e.keyCode === 37){
			id = $('#left_arrow_container').attr('data-id');
			if(typeof id != 'undefined'){
				replaceViewerData(id);
			}
		}
		if(e.keyCode === 39){
			id = $('#right_arrow_container').attr('data-id');
			if(typeof id != 'undefined'){
				replaceViewerData(id);
			}
		}
	}
);

$(document).live('keydown',
	function(e){
		if(e.keyCode === 27){
			closePhotoViewerDynamic();
		}
	}
);

$(document).ready(function(){

	$('.arrow_container')
		.live('mouseenter',function(){ $(this).find('.arrow').css('opacity', '0.5'); })
		.live('mouseleave',function(){ $(this).find('.arrow').css('opacity', '0.1'); }); 
	
	$('.item_link').live('click', function(e) {
	
			var $parent = $(this).parent();
			if(typeof $parent.attr('data-id') != 'undefined'){
				loadPopupViewer($parent.attr('data-id'));
				var curText = parseInt($($parent).find('.item_stats_left span').text());
				$($parent).find('.item_stats_left span').text(curText + 1);
				return false;
			}
			
		return true;
	});
	
	$('.pviewer-trigger').click( function() {
	
		var $this = $(this);
		if(typeof $this.attr('data-id') != 'undefined'){
			loadPopupViewer($this.attr('data-id'));
			return false;
		}
		
		return true;
	});
	
});
	