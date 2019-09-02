$(document).ready(function(){

	$(window).scroll(function(){
		var scrollTop = $(window).scrollTop();
		if(scrollTop != 0)
			$('.navigation').stop().animate({top:'-50px'}, 50);
		else
			$('.navigation').stop().animate({top:'0px'}, 300);
	});
	

	$('.navigation').hover(
		function (e) {
			var scrollTop = $(window).scrollTop();
			if(scrollTop != 0){
				$('.navigation').stop().animate({top:'0px'}, 150);
			}
		},
		function (e) {
			var scrollTop = $(window).scrollTop();
			if(scrollTop != 0){
				$('.navigation').stop().animate({top:'-50px'}, 250);
			}
		}
	);


});
