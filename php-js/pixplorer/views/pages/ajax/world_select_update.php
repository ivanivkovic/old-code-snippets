<select class="world" name="<?php echo $type ?>" id="<?php echo $type ?>">
	<option selected><?php echo $this -> loadString('popup_choose_' . $type) ?></option>
<?php 	
while($dt = $data -> fetch(PDO::FETCH_ASSOC)){		
	echo '<option value="' . $dt[$type . 'ID']. '">' . $dt[$type . 'Name'] . '</option>';
}
?>
</select>
<script type="text/javascript">

$('.world').change(function(){	
<?php if($type != 'city'){ ?>
	loadResult($(this).attr('id'), $(this).attr('value'));
<?php } ?>
});


</script>