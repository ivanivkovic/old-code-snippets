<? foreach( $clientList as $key => $item ): ?>

	<tr>
		<td>
			<a href="/client/<?= $item['clientid'] ?>"><?= $item['name'] ?></a>
		</td>
		<td class="hidden-phone"><a href="mailto:<?= $item['email']?>"><?= $item['email']?></a></td>
		<td class="hidden-phone"><p><?= libString::UrlToA( $item['info'], true ) ?></p></td>
		<td class="hidden-phone"><?= $item['contract'] === '1' ? 'Da' : 'Ne' ?></td>
		<td class="hidden-phone"><a href="/task#clientid=<?= $item['clientid'] ?>;status=1;"><?= $item['active-tasks'] ?></a></td>
		
		<? if( Core::$user->level !== 2 ): ?>
		
		<td>
			<a class="btn margin-right-5 edit" data-id="<?= $item['clientid'] ?>" href="#">
				<i class="icon-edit"></i> Uredi</button>
			</a>
			
			<a class="btn delete" data-hasprojects="<?= var_export($item['hasprojects']) ?>" data-item="client" data-id="<?= $item['clientid'] ?>" href="#">
				<i class="icon-remove"></i> Obriši
			</a>
		</td>
		
		<? endif; ?>
	</tr>

<? endforeach; ?>