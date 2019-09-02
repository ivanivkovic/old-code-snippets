<?

/*
*
* DOCS :
*
* 
*
*/

?>

<? if ( isset($leftForm) ):  ?>

<? foreach( $leftForm as $form): ?>

	<form action="" method="<?= $form['method'] ?>">
		
		<? if( isset($form['select']) ): ?>
		
			<? foreach( $form['select'] as $select): ?>
				
				<select name="<?= $select['name'] ?>" <? if(isset( $select['onchange'] )): ?> onchange='<?= $select['onchange']?>' <? endif; ?> >
					
					<? foreach( $select['option'] as $array ): ?>
					
						<option <? if( isset( $array['selected'] ) ){ echo 'selected'; } ?> value="<? if( isset( $array['value'] ) ){ echo $array['value']; } ?>"><?= $array['title'] ?></option>
						
					<? endforeach; ?>
					
				</select>
				
			<? endforeach; ?>
		
		<? endif; ?>
		
	</form>

<? endforeach; ?>

<? else: ?>

	<!-- form info missing -->
	
<? endif; ?>