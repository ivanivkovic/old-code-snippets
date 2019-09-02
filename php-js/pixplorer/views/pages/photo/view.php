<?php include(Conf::$dir['widgets'] . 'html_head.php'); ?>

	<?php include(Conf::$dir['widgets'] . 'close_page_viewer.php'); ?>
	</head>
	<body id="<?php echo $this -> registry -> router -> page ?>">
		
		<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
		<script type="text/javascript" src="http://platform.tumblr.com/v1/share.js"></script>
		<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=177259275703895";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	
<script>

//$('.popup2:not(#viewer_wrapper)').click(function(){
//	closePopupViewer();
//});

function correctMe(){
	var top_move = parseInt($('#image').css('height')) / 2;
	$('#image').css({position : 'relative', top: '50%', marginTop: '-' + top_move + 'px'});
}

</script>


<style>
body{
	overflow: hidden;
}
</style>
<div id="background"></div>

<div class="popup2" style="background-color: #222;">

<?php include(ROOT_SITE_PATH . '/views/pages/ajax/photo_viewer.php'); ?>

</div>
<?php
	$footer = false;
	include(Conf::$dir['widgets'] . 'html_footer.php');
?>