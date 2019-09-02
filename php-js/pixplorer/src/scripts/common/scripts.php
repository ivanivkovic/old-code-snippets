// IE console log patch
if ( ! window.console ) {

    (function() {
      var names = ["log", "debug", "info", "warn", "error",
          "assert", "dir", "dirxml", "group", "groupEnd", "time",
          "timeEnd", "count", "trace", "profile", "profileEnd"],
          i, l = names.length;

      window.console = {};

      for ( i = 0; i < l; i++ ) {
        window.console[ names[i] ] = function() {};
      }
    }());

}

function loadAjaxPopup(width, height, widget, action, criteria){
	mg_lft = - width / 2;
	mg_top = - height / 2;
	
	$('#background').animate({opacity: '0.45'}, 0).fadeIn(200);
	$('body:not(#background)').append('<div class="popup"></div>');
	var done = true;

	if(done == true){
		$popup = $('.popup');
		$popup.animate({ width: width + 'px', marginLeft : mg_lft + 'px', height: height + 'px', marginTop : mg_top + 'px' }, 0).fadeIn(300);
		done3 = true;
		if(done3 == true){
			$.get(
				'<?php echo Conf::$page['ajax'] ?>' + widget + '/' + action + '/' + criteria,
				function(data){
					$popup.append(data);
					$('.data').fadeIn(200);
				},
				'html'
			);
		}
	}
}

function closePopups(){
	$('#background').fadeOut(200);
	$popup = $('.popup');
	$popup.fadeOut(200);
	$popup.remove();
}

function limitString(string, number){
	return string.substring(0, number);
}

// Facebook publish
function postToFeed(link, picture, name, caption, description) {

	var obj = {
		method: 'feed',
		link: link,
		picture: picture,
		name: name,
		caption: caption,
		description: description
	};

	function callback(response) {
		document.getElementById('msg').innerHTML = "Post ID: " + response['post_id'];
	}

	FB.ui(obj, callback);
}

// Facebook share
function facebookShare(u, t) {
	window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');
	return false;
}

function refreshLikes(){
	try{
		FB.XFBML.parse(); 
		twttr.widgets.load();
		IN.parse(document);
	}catch(ex){
		//console.log(ex);
	}
}

$(document).ready(function()
{
	$("img").error(function () { 
		// or $(this).hide();
		$(this).css({visibility:"hidden"}); 
	});
});
