<? foreach( $clientList as $key => $item ): ?>

	<tr>
		<td><a href="/client/<?= $item['clientid'] ?>"><?= $item['name'] ?></a></td>
		<td class="hidden-phone"><a href="mailto:<?= $item['email']?>"><?= $item['email']?></a></td>
		<td class="hidden-phone"><p><?= libString::UrlToA( $item['info'], true ) ?></p></td>
	
		<? if( Core::$user->level !== 2 ): ?>
		
		<td>
			<a class="btn btn-mini margin-right-5 edit" data-id="<?= $item['clientid'] ?>" href="#"><i class="icon-pencil"></i> Uredi</button></a>
			<a class="btn btn-mini delete" data-hasprojects="<?= var_export($item['hasprojects']) ?>" data-item="client" data-id="<?= $item['clientid'] ?>" href="#"><i class="icon-trash"></i> Obriši</a>
		</td>
		
		<? endif; ?>
	</tr>

<? endforeach; ?>