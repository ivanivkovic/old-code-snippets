<? if ( isset($topTabs) ):  ?>

<ul class="nav-tabs nav">

	<? foreach( $topTabs as $MenuItem): ?>
	
		<? if( is_array($MenuItem) ): ?>
		
			<? $title = isset($MenuItem['title']) 		&& $MenuItem['title'] !== '' ? 		$MenuItem['title'] : '-Missing Title-'; ?>
			<? $tabToggle = isset($MenuItem['tab']) 					 ?			  		' tab-content-toggle ' : '' ?>
			<? $active = isset($MenuItem['active']) 	&& $MenuItem['active'] === true ? 	' active ' : '' ?>
			<? $class = isset($MenuItem['class']) 	?	' ' .$MenuItem['class'] . ' ' : '' ?>
			<? $tabId = isset($MenuItem['tab']) 						 ? 					$MenuItem['tab'] : '' ?>
			<? $href = isset($MenuItem['href']) 						 ? 					$MenuItem['href'] : '' ?>
			
			<li class="<?= $active . ' ' .  $class . ' ' . $tabToggle ?>" data-id="<?= $tabId ?>">
				<a href="<?= $href . ( $tabId !== '' ? '#' : '' ) .  $tabId ?>"><?= $title ?></a>
			</li>
			
		<? else: ?>
			
			<li><?= $MenuItem ?></li>
			
		<? endif; ?>
		
	<? endforeach; ?>
</ul>

<? endif; ?>

<div class="clear"></div>
