<div class="facebook_like like">
	<div class="fb-like" data-href="<?php echo $data['link'] ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true" data-font="tahoma"></div>
</div>
<div class="tweet like">
	<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $data['fullname'] . ' - ' . $data['title'] . ' - ' . $data['link'] ?>" data-text="<?php echo $data['title'] ?>">Tweet</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>

<div class="tumblr like">
	<a href="http://www.tumblr.com/share/photo?source=<?php echo urlencode($data['image']) ?>&caption=<?php echo urlencode($data['title']) ?>&clickthru=<?php echo $data['link'] ?>"  title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:20px; height:20px; background:url('http://platform.tumblr.com/v1/share_4.png') top left no-repeat transparent;">Share on Tumblr</a>
</div>

<div class="gplus like">
	<!-- Place this tag where you want the +1 button to render. -->
	<div class="g-plusone" data-size="medium" data-annotation="bubble" data-href="<?php echo $data['link'] ?>"></div>
	<!-- Place this tag after the last +1 button tag. -->
	<script type="text/javascript">
  (function() {
	var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	po.src = 'https://apis.google.com/js/plusone.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
	</script>
</div>

<div class="pinit like">
	<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode($data['link'])?>&media=<?php echo urlencode($data['image']) ?>&description=<?php echo $data['description'] ?>" class="pin-it-button" count-layout="horizontal">
		<img alt="" border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" />
	</a>
</div>

<div class="stumble like">
	<su:badge layout="3" location="<?php echo $data['link'] ?>"></su:badge>
	<script type="text/javascript">
  (function() {
    var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
    li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
  })();
</script>
</div>

<div class="amazon like">
	<iframe src="http://rcm.amazon.com/e/cm?t=pixplorer-20&o=1&p=41&l=ur1&category=kindle&banner=181G1TS03ZAMH5Q4DFR2&f=ifr" width="88" height="31" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
</div>