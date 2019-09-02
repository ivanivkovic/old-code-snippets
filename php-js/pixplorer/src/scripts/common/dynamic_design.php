function vAlignMe(dom){
	$dom = $(dom);
	var top_move = parseInt($dom.css('height')) / 2;
	$dom.css({position : 'relative', top: '50%', marginTop: '-' + top_move + 'px'});
}

$.fn.placeholder = function() {
	return this.focus(function() {
		if( this.value == this.defaultValue ) {
			this.value = "";
		}
	}).blur(function() {
		if( !this.value.length ) {
			this.value = this.defaultValue;
		}
	});
};

$(document).ready(function(){
	
	$('.placeholder').placeholder();
	
	var file = "<link rel=\"stylesheet\" type=\"text/css\" href=\"<?php echo Conf::$src['generate'] ?>dimensions.css&w=" + screen.width + "&h=" + screen.height + "\"/>";
	
	$('head').append(file);

	var $footer = $('#footer');
	$footer.css('opacity', 0);
	$footer.hover(
		function(){
			$(this).animate({opacity: 0.82}, 100);
		},
		function(){
			$(this).animate({opacity: 0}, 300);
		}
	);
	
	// All dynamic css controllers.
	$('.hover_underline').live('mouseout',
		function(){
			$(this).css('text-decoration', 'none');
		}
	);
	$('.hover_underline').live('mouseover',
		function(){
			$(this).css('text-decoration', 'underline');
		}
	);
	
	// All image-changing hovers.
	
	$('.hover_img').live('mouseenter',
		function(){
			$this = $(this);
			prev_img = $this.attr('src');
			$this.attr('src', $this.attr('data-hover-img'));
		}		
	);
	
	$('.hover_img').live('mouseleave',
		function(){
			$this.attr('src', prev_img);
		}	
	);
	<?php 
	/*
	$('.item').live({
        mouseover:
           function(){
				var $this = $(this);

				var $darkbox = $this.find('.item_dark_box');
				$darkbox.animate({opacity: 0.2}, 0, function(){
					$darkbox.css('display', 'block');
				});
				
				var $statsbox = $this.find('.item_stats');
				$statsbox.animate({opacity: 1}, 0, function(){
					$statsbox.css('display', 'block');
					$statsbox.find('.stats_text').css('opacity', '0.7');
				});
           },
        mouseout:
           function(){
				var $this = $(this);
				$this.find('.item_stats').css('opacity', '0');
           }
       }
    );
	*/
	?>
	
	
});