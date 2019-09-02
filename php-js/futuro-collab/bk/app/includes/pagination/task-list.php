<?

// Op op https://www.youtube.com/watch?v=AfBKIaZOyK0

function determineRowClass( $item )
{
	if( $item['status'] === '1' && libDateTime::Time() > $item['deadline'] )
	{
		return ' error';
	}
	else if( $item['status'] === '0' )
	{
		return ' success';
	}
	
	return '';
}


foreach( $items as $item ): ?>

	<tr class="task<?= determineRowClass( $item ) ?>">
		<td>
			<a href="/task/<?= $item['taskid'] ?>"><?= $item['order_code'] ?></a>
		</td>
	
		<td>
			<a href="/client/<?= $item['client']['clientid'] ?>" target="_blank">
				<?= $item['client']['name'] ?>
			</a>
		</td>
	
		<td><?= $item['description'] ?></td>
	
		<td  class="hidden-phone hidden-tablet"><?= date('d. m. Y.', $item['publishtime']) ?></td>
		<td><?= libTemplate::txt(  'task-order',   $item['ordertype']   ) ?></td>
		<td>
			<a href="/user/<?= $item['reciever']['userid'] ?>" target="_blank">
				<?= $item['reciever']['name'] ?> <?= $item['reciever']['lastname'] ?>
			</a>
		</td>
	
		<td>
			<? foreach( $item['assigned'] as $assigned): ?>
				<a target="_blank" href="/user/<?= $assigned['userid'] ?>"><?= $assigned['name'] . ' ' . $assigned['lastname'] ?></a>
			<? endforeach; ?>
		</td>
	
		<td><? if( $item['deadline'] != 0 ){ echo date( 'd. m. Y.', $item['deadline'] ); } ?></td>
		<td class="hidden-phone hidden-tablet"><? if( $item['timefinished'] != 0 ){ echo date( 'd. m. Y.', $item['timefinished'] ); }else{ echo 'U tijeku'; } ?></td>
		<td class="hidden-phone hidden-tablet"><? if( $item['client']['contract'] == '1' ){ echo 'Da'; }else{ echo 'Ne'; } ?></td>
		
		<td class="hidden-phone hidden-tablet"></td>
		<td class="hidden-phone hidden-tablet"></td>
		
		<td class="priority priority-<?= $item['priority'] ?>">
			<a href="#" class="priorty" data-value="<?= $item['priority'] ?>">
				<?= libTemplate::txt( 'task-priority', $item['priority'] ) ?>
			</a>
		</td>
		
		<td>
			<a data-id="<?= $item['taskid'] ?>" onclick="deleteTask(this); return false;" href="#" class="btn pull-right font-normal delete">
				<i class="icon-remove"></i> Izbriši
			</a>
			<a data-id="<?= $item['taskid'] ?>" onclick="editTask(this); return false;" href="#" class="btn pull-right font-normal edit margin-right-5">
				<i class="icon-edit"></i> Uredi
			</a>
		</td>
	</tr>

<? endforeach; ?>