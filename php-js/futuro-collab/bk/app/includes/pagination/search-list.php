<? if( ! empty ( $data ) ) : ?>

	<table id="right-content-list">
	
		<thead>
			<tr>
				<td width="90%">Ime</td>
				<td width="10%">Sadržaj</td>
			</tr>
		</thead>
		
		<tbody>
	
		<? foreach( $data as $item ): ?>
		
		<tr class="search-<?= $item['type'] ?>">
			
			<? 	switch( $item['type'] ):
			
				default: ?>
				
					<td>
						<a href="/<?= $item['type'] . '/' . $item['itemid']?>"><?= $item['field1'] . ' ' . $item['field2'] ?></a>
					</td>
					<td>
						<a href="/<?= $item['type']?>"><?= ucfirst(libTemplate::txt( $item['type'] )) ?></a>
					</td>
				
				<? break;
				
				case 'file': ?>
				
					<td class="search-<?= $item['type'] ?>">
						<a href="/download?file=<?= $item['itemid'] ?>"><?= $item['field1'] ?></a>
					</td>
					<td><?= ucfirst(libTemplate::txt( $item['type'] )) ?></td>
				
				<? break; ?>
				
			<? endswitch; ?>
			
		</tr>
	
		<? endforeach; ?>
	
		</tbody>
	
	<table>

	<div class="border-bottom"></div>

<? else: ?>

	<p>Nema rezultata koji odgovaraju vašoj pretrazi.</p>
	
<? endif; ?>