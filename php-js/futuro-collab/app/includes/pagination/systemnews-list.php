<? foreach($data as $newsData): ?>

	<div class="news-item">
		
		<?
			libSystemNews::printNewsItem($newsData);
		?>
		
		<div class="clear"></div>
	
	</div>
	
<? endforeach; ?>