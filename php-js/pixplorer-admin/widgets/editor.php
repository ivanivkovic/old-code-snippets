<?php

if(isset($_POST['ids'])){

	if(isset($_POST['action']) ){
	
		$ids = explode(',', $_POST['ids']);
		
		foreach($ids as $id){
			if($id !== ''){
				$object -> $_POST['action']($id);
			}
		}
	
	}
}

if($criteria_setting['onoff'] === true){
	$criterias = $object -> listCriterias();
}

if($criteria_setting['onoff'] === true && isset($criterias) && $criterias !== false){ ?>
	
	<span class="fl" style="margin-top: 4px;">
		<?php echo $criteria_setting['name'] ?>
	</span>
	<select style="margin-left: 5px;" class="fl" onchange="window.location='<?php echo $cur_file ?>?page=<?php echo $cur_page?>&criteria='+this.value">

	<?php $counter = 0;
	foreach($criterias as $item){
		
		if(!isset($criteria) && $counter === 0){
			$criteria = $item['value'];
		}
		
		?>
		<option value="<?php echo $item['value'] ?>" <?php if(isset($criteria) && $item['value'] === $criteria){ echo 'selected'; } ?>>
			<?php echo $item['text'] ?> <?php if($item['count'] === true){ echo '(' . $object -> getCriteriaCount($item['value']) . ')' ; } ?>
		</option>
		<?php
		
		++$counter;
	} 
	?>

	</select>
		
<?php 
	}else{ 
		echo $not_found;
	}
?>
	<div class="fl" id="view_radios">
		&nbsp;View: <input type="radio" name="view" value="dynamic" checked/>Dynamic
		<input type="radio" name="view" value="static" />Static
	</div>
	
	<div class="border_side_spacer10 fl"></div>
	
	<div class="fl" id="photo_radios">
		&nbsp;Load: 
		<input type="radio" name="photo" value="thumb" checked />Thumbs
		<input type="radio" name="photo" value="photo"/>Photos
	</div>

	<button class="fr" onclick="window.location = location.href;">Refresh</button>
	<div class="border_side_spacer10 fr"></div>
	
	<div class="fr">
		<select id="action" style="width: 140px;">
			<option selected>Actions</option>
		<?php foreach($actions as $action): ?>
			<option value="<?php echo $action ?>"><?php echo $action ?></option> 
		<?php endforeach; ?>
		</select>
	</div>
	
	<div class="cleaner"></div>
	<div class="border_spacer10"></div>
	
	<div class="gallery_container">

	<?php

	$entities = $object -> listEntitiesByCriteria($criteria);

	if($entities !== false){
		
		foreach($entities as $fetch){
			?>
			<div class="item" 
				<?php foreach($fetch as $key => $value): ?>
					data-<?php echo $key ?>="<?php echo $value ?>"
				<?php endforeach; ?>
				>
				<a title="<?php echo $fetch['title'] ?>">
					<img src="<?php echo $fetch['thumb'] ?>"/>
				</a>
			</div>
			<?php
		}
		
	}else{
		echo $not_found_by_criteria;
	} ?>

</div>

<div class="border_spacer10"></div>
<div id="gallery_description">
	
	<div id="gallery_description_picture" class="fl">
		<a target="_blank">
			
		</a>
	</div>
	
	<div id="gallery_description_description" class="fl">
		
	</div>
	
</div>

<form style="display: none;" action="" id="items_admin_form" method="POST">
	<input type="hidden" name="ids"/>
	<input type="hidden" name="action"/>
</form>

<script>

$(document).ready(function(){

	$('#action').change(function(){
	
		var $this = $(this);
		
		var value = '';
		
		$('.selected').each(function(){
			value += $(this).attr('data-id') + ',';
		});
		
		var action = $this.val();
		
		if(value != '' && action != 'Actions'){
			
			var length = $('.selected').length;
			
			var question = 'Are you sure you want to ' + action.toLowerCase() + ' ' + length + '?';
			
			var answer = confirm(question);
			
			if(answer === true){
				$('#items_admin_form input[name=ids]').val(value);
				$('#items_admin_form input[name=action]').val(action);
				$('#items_admin_form').submit();
			}
		}
		
		$('#action option:eq(0)').attr('selected', 'selected');
		
	});
	
});
</script>