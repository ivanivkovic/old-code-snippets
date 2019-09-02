<?

if( $taskData['status'] === '1'):
	$dateValue = libDateTime::$days[libTemplate::$lang]['today'];
else:
	$dateValue = libTemplate::getFullDate($taskData['timefinished']);
endif;

?>

<form id="task-status-update" class="form" method="POST">

	<input type="hidden" name="taskid" value="<?= $taskData['taskid'] ?>"/>
	<input type="hidden" name="action" value="update-status"/>
	<input type="hidden" name="status" value="0"/>
		<label for="timefinished">Datum izvršenja <span class="asterisk">*</a></label>
	<input class="datep" type="text" name="timefinished" value="<?= $dateValue ?>"/>

	<? if( $taskData['status'] === '0'): ?>

	<hr/>

	<label for="timefinished">Status</label>
	
	<select name="status">
		<option value="0" selected>Neaktivan</option>
		<option value="1">Aktivan</option>
	</select>
	
	<? endif; ?>
</form>