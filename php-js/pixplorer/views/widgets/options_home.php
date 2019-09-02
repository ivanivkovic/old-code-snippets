<li>
	<?php
		$edit_text = $data['description'] == '' ? $this -> loadString('add_description') : $this -> loadString('edit');
	?>
	<a data-action="edit" id="edit"><?php echo $edit_text ?></a>
</li>
<li>
	<a data-action="delete"><?php echo $this -> loadString('delete')?></a>
</li>

<li>
	<a data-action="share" rel="nofollow" href="http://www.facebook.com/share.php?u=<?php echo urlencode($data['link']); ?>&t=<?php echo urlencode($data['title']); ?>" 
	onclick="return facebookShare('<?php echo $data['link']; ?>', '<?php echo $data['title']; ?>')" target="_blank"><?php echo $this -> loadString('share') ?></a>
</li>
