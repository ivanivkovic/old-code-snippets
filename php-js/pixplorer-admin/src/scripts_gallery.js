function loadGalleryDescription(pic_url, thumb_url, content){
	
	var view = $('input[name=photo]:checked', '#photo_radios').val();
	$('#gallery_description_picture a').attr('href', pic_url);
	
	if(view === 'photo'){
	
		$('#gallery_description_picture a').append('<img id="prespic" onload="vAlignMe(\'#prespic\')" src="' + pic_url + '"/>');
		
	}else{
	
		$('#gallery_description_picture a').append('<img id="prespic" onload="vAlignMe(\'#prespic\')" src="' + thumb_url + '"/>');
		
	}
	
	$('#gallery_description_picture').addClass('bg_black');
	
	$('#gallery_description_description').append(content);
	
}

function removeGalleryDescription(){
	$('#gallery_description_picture a').attr('href', '');
	$('#gallery_description_picture').removeClass('bg_black');
	$('#gallery_description_picture img').remove();
	$('#gallery_description_description').html('');
}

function createContent(object){
	var content = '';
		if(typeof object.attr('data-rightpic') != 'undefined'){
			content += '<div class="gallery_right_info"><img src="' + object.attr('data-rightpic') + '"/>';
		}
		if(typeof object.attr('data-righturl') != 'undefined'){
			content += '<a target="_blank" href="' + object.attr('data-righturl') + '">';
		}
		if(typeof object.attr('data-righttext') != 'undefined'){
			content += object.attr('data-righttext') + '</a></div><div class="cleaner"></div></br>';
		}
		if(typeof object.attr('data-rtext1') != 'undefined'){
			content += '<p>' + object.attr('data-rtext1') + '</p>';
		}
		if(typeof object.attr('data-rtext2') != 'undefined'){
			content += '<p>' + object.attr('data-rtext2') + '</p>';
		}
		if(typeof object.attr('data-rtext3') != 'undefined'){
			content += '<p>' + object.attr('data-rtext3') + '</p>';
		}
		if(typeof object.attr('data-rtext4') != 'undefined'){
			content += '<p>' + object.attr('data-rtext4') + '</p>';
		}
		if(typeof object.attr('data-rtext5') != 'undefined'){
			content += '<p>' + object.attr('data-rtext5') + '</p>';
		}
	return content;
}

$(document).ready(function(){
	
	
	$('.gallery_container .item').hover(
		function(){
			var view = $('input[name=view]:checked', '#view_radios').val();
			if(view === 'dynamic'){
				var $this = $(this);
				$this.addClass('hover');
				var content = createContent($this);
				removeGalleryDescription();
				loadGalleryDescription($this.attr('data-photo'), $this.attr('data-thumb'), content);
			}
		},
		function(){
			var $this = $(this);
			var view = $('input[name=view]:checked', '#view_radios').val();
			if(view === 'dynamic'){
				$this.removeClass('hover');
			}
		}
	);
	
	$('.gallery_container .item').click(function(e){
	
		var view = $('input[name=view]:checked', '#view_radios').val();
		
		e.stopPropagation();
		var $this = $(this);
			
		if(!e.ctrlKey){
			if(!$this.hasClass('selected')){
				$('.selected').removeClass('selected');
			}
		}
		
		$last_clicked = $('#last_clicked')
		
		$('#last_clicked').removeAttr('id');
		$($this).attr('id', 'last_clicked');
		
		if($this.hasClass('selected')){
			if(view == 'static'){
				removeGalleryDescription();
			}
			$this.removeClass('selected');
		}else{
			$this.addClass('selected');
			if(view === 'static'){
				var content = createContent($this);
				removeGalleryDescription();
				loadGalleryDescription($this.attr('data-photo'), $this.attr('data-thumb'), content);
			}
		}
		
		
		if($last_clicked.length && e.shiftKey){
		
			$thisIndex = $this.index();
			$prevIndex = $last_clicked.index();
			
			if($thisIndex > $prevIndex){
			
				$('.item').eq($prevIndex).addClass('selected');
				
				for(var i = $thisIndex; i > $prevIndex; i--){
					$('.item').eq(i).addClass('selected');
				}
			
			}else{
				
				for(var i = $prevIndex; i > $thisIndex; i--){
					$('.item').eq(i).addClass('selected');
				}
				
			}
			
		}
		
	});	
	
	$('.gallery_container').click(function(e){
		$('.selected').removeClass('selected');
		$('#last_clicked').removeAttr('id');
		removeGalleryDescription();
	});
	
});

function vAlignMe(dom){
	$dom = $(dom);
	var top_move = parseInt($dom.css('height')) / 2;
	$dom.css({position : 'relative', top: '50%', marginTop: '-' + top_move + 'px'});
}