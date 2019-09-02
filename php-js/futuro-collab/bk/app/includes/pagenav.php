<div class="navbar">
	<div class="navbar-inner">
		<ul class="nav">
		
		<? foreach( $pageNav as $MenuItem): ?>
		
			<? if( is_array($MenuItem) ): ?>
			
				<? $active = isset($MenuItem['active']) && $MenuItem['active'] === true ? 	' active ' : '' ?>
				<? $title = isset($MenuItem['title']) && $MenuItem['title'] !== '' ? $MenuItem['title'] : '-Missing Title-'; ?>
				
				<li class="<?= $active ?>">
					<a href="<?= $MenuItem['href'] ?>"><?= $title ?></a>	
				</li>
			
			<? endif; ?>
		
		<? endforeach; ?>
		
		</ul>
	</div>
</div>

