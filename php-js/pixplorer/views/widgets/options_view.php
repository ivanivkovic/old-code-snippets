<?php /*
<li>
	<a data-action="report"><?php echo $this -> loadString('report_photo') ?></a>
</li>
*/ ?>
<li>
	<a data-action="share" rel="nofollow" href="http://www.facebook.com/share.php?u=<?php echo urlencode($data['link']); ?>&t=<?php echo urlencode($data['title']); ?>" 
	onclick="return facebookShare('<?php echo $data['link']; ?>', '<?php echo $data['title']; ?>')" target="_blank"><?php echo $this -> loadString('share') ?></a>
</li>
