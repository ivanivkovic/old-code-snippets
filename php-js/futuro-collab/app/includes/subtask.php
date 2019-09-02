<label class="sub-task checkbox">
	
	<input type="checkbox" name="publish" value="<?= $subTask['taskid'] ?>" <? if( $subTask['status'] == '0' ): echo 'checked'; endif; ?>/>
	
	<?= $subTask['description'] ?>
	
	<i class="icon-minus delete-subtask pull-right"  data-id="<?= $subTask['taskid'] ?>"></i>
	<i class="icon-edit margin-right-5 pull-right" data-id="<?= $subTask['taskid'] ?>"></i>
</label>