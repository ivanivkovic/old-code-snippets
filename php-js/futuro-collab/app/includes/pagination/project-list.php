<? foreach( $data as $item ): ?>
	<tr>
		<td><a href="/project/<?= $item['projectid'] ?>"><?= $item['title'] ?></a></td>
		<td class="hidden-phone"><a href="/task#projectid=<?= $item['projectid'] ?>"><?= $item['active_tasks'] ?></a></td>
		<td class="hidden-phone"><a href="/user/<?= $item['userid'] ?>"><?= $item['name'] . ' ' . $item['lastname'] ?></a></td>
		<td class="hidden-phone"><a href="/client/<?= $item['client']['clientid'] ?>"><?= $item['client']['name'] ?></a></td>
	</tr>
<? endforeach; ?>