<?

$tags = '';

if(isset($settings['autocompleteList'])):
	
	$c = 0;
	
	foreach( $settings['autocompleteList'] as $item ):
		
		if($c !== 0 && $item !== ''):
			$tags .= ',';
		endif;
  	
  		$tags .= $item;
  		
  		$c++;
  		
	endforeach;
	
endif;

?>

<input 
	type="text"
	data-source="<?= $tags ?>"
	autocomplete="off"
	data-items="3"
	class="typehead pull-left margin-right-10"
/>

<ul class="typehead-container nav nav-pills">

<? if(isset( $settings['value'])): foreach( $settings['value'] as $item ): ?>

	<li onclick="removeTag(this)"><a href="#"><?= $item ?></a></li>

<? endforeach; endif; ?>

</ul>

<input class="typehead-value" type="hidden" name="<?= $settings['name'] ?>" value="<?= $settings['curvalue'] ?>"/>
<div class="clear"></div>