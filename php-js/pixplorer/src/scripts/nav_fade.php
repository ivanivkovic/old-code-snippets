$(document).ready(function() {
	$(window).scroll(function(){
		var scrollTop = $(window).scrollTop();
		if(scrollTop != 0)
			$('.navigation').stop().animate({'opacity':'0.2'},400);
		else
			$('.navigation').stop().animate({'opacity':'1'},400);
	});
 
	$('.navigation').hover(
		function (e) {
			var scrollTop = $(window).scrollTop();
			if(scrollTop != 0){
				$('.navigation').stop().animate({'opacity':'1'},400);
			}
		},
		function (e) {
			var scrollTop = $(window).scrollTop();
			if(scrollTop != 0){
				$('.navigation').stop().animate({'opacity':'0.2'},400);
			}
		}
	);

});
