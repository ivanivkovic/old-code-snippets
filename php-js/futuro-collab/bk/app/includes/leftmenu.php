<?

/*
*
* DOCS :
*
* Član lijevog menija mo?e imati podmeni - u array-u odredimo "submenu".
* Član lijevog menija mo?e biti link - odredimo mu href u array-u.
* Član lijevog menija mo?e biti tab toggle za tabirani sadržaj - u arrayu odredimo 'tab' => 'ime_taba' i možemo staviti 'active' : true ako je taj tab početni tab.
* Title array odre?uje naslov ?lana menija.
*
*/


if ( isset($leftMenu) ):

?>

<ul class="left-menu nav nav-tabs nav-stacked">

	<? foreach( $leftMenu as $MenuItem): ?>
	
		<? if( is_array($MenuItem) ): ?>
		
		<? $title = isset($MenuItem['title']) 		&& $MenuItem['title'] !== '' ? 		$MenuItem['title'] : '-Missing Title-'; ?>
		<? $href = isset($MenuItem['href']) 		&& $MenuItem['href'] !== '' ? 		$MenuItem['href'] : '#'; ?>
		<? $tabToggle = isset($MenuItem['tab']) 					 ?			  		' tab-content-toggle ' : '' ?>
		<? $tabId = isset($MenuItem['tab']) 						 ? 					$MenuItem['tab'] : '' ?>
		<? $class = isset($MenuItem['class']) 						 ? 					$MenuItem['class'] : '' ?>
		
		<li class="<?= ( isset($MenuItem['active']) && $MenuItem['active'] === true ? 'active' : '' ) . $tabToggle . ' ' . $class ?>" data-id="<?= $tabId ?>">
			<a href="<?= $href . $tabId ?>"><?= $title ?></a>	
		</li>
		
		<? else: ?>
		
			<li><?= $MenuItem ?></li>
		
		<? endif; ?>
		
	<? endforeach; ?>

</ul>

<? endif; ?>