<?php

# This file is made to be included as a widget for each masonry items output.
# If the info you're loading can also be loaded with AJAX, put that data in the naked_masonry_box, and leave the parent div in this file, then include the naked_masonry_box file.

if(!isset($mode)){
	$mode = 'standard';
}

switch($mode){ 
	
	case 'standard': default: ?>

<div class="element item" data-id="<?php echo $fetch['pic_id'] ?>" id="item_<?php echo $fetch['pic_id'] ?>">
	<?php include(Conf::$dir['widgets'] . 'naked_masonry_box.php'); ?>
</div>

<?php break; ?>




<?php case 'upload': ?>

<div class="item element"> 	
	<div class="item_link">
		<img alt="" src="<?php echo Conf::$src['thumbs'] . $user->id . '/' . $result['src'] ?>"/>
	</div>
	<div class="bottom_part" style="border-bottom: 0 !important;">
		<div class="spacer10"></div>
		<div class="spacer10"><?php echo Categories::getCategoryAndParent($result['cat_id'], ', ') ?></div>
		<div class="spacer10"></div>
		<div class="spacer10"><?php echo WorldDatabase::getFullLocation($result['city_id'], ', ', true); ?></div>
		<div class="spacer10"></div>
		<textarea maxlength="<?php echo PHOTO_DESCRIPTION_LIMIT ?>" name="desc_<?php echo $result['pic_id'] ?>"></textarea>
		<?php $array[$result['pic_id']] = 0; ?>
	</div>
</div>

<?php break; ?>



<?php case 'search_user': ?>

<div class="item element">
	
	<?php include(Conf::$dir['widgets'] . 'naked_masonry_box.php'); ?>
	
</div>

<?php break; ?>

<?php 

	case 'search_img':
	case 'search_city':
	case 'search_region':
	case 'search_country':
	
	if($fetch['id'] !== NULL){
	
?>

<div class="element item" data-id="<?php echo $fetch['id'] ?>" id="item_<?php echo $fetch['id'] ?>">
	
	<?php include(Conf::$dir['widgets'] . 'naked_masonry_box.php'); ?>
	
</div>

<?php 
	}
break; 

case 'user_categories_profile':

?>
<div class="item element">
	<a class="item_link" href="<?php echo Conf::$page['album_generated'] . $user_data['user_id'] . '/' . $album['cat_id'] ?>/0">
		<img alt="" class="img" src="<?php echo Conf::$src['thumbs'] , $album['user_id'] , $album['pic'] ?>">
	</a>
	<div class="bottom_part">
		<div class="spacer20">&nbsp;</div>
		<div class="spacer10"><?php echo Categories::getCategoryAndParent($album['cat_id'], ' - ', true) ?> (<?php echo $album['num_photos'] . ' ' . $this->loadString('photos_lower') ?>)</div>
		<div class="spacer20">&nbsp;</div>
	</div>
</div>
<?php break;

case 'user_cities_profile':
?>
<div class="item element">
	<a class="item_link" href="<?php echo Conf::$page['album_generated'] . $user_data['user_id'] . '/0/' . $album['city_id'] ?>/0">
		<img alt="" class="img" src="<?php echo Conf::$src['thumbs'] , $user_data['user_id'] , $album['pic'] ?>">
	</a>
	<div class="bottom_part">
		<div class="spacer20">&nbsp;</div>
		<div class="spacer10"><?php echo WorldDatabase::getFullLocation($album['city_id'], ', ', true) ?> (<?php echo $album['num_photos'] . ' ' . $this->loadString('photos_lower') ?>)</div>
		<div class="spacer20">&nbsp;</div>
	</div>
</div>
<?php
break;

case 'user_categories_home':
?>
<div class="item element">
	<a class="item_link" href="<?php echo Conf::$page['album_generated'] . $user->id . '/' . $album['cat_id'] ?>/0">
		<img alt="" class="img" src="<?php echo Conf::$src['thumbs'] , $user->id , $album['pic'] ?>">
	</a>
	<div class="bottom_part">
		<div class="spacer20">&nbsp;</div>
		<div class="spacer10"><?php echo Categories::getCategoryAndParent($album['cat_id'], ' - ', true) ?> (<?php echo $album['num_photos'] . ' ' . $this->loadString('photos_lower') ?>)</div>
		<div class="spacer20">&nbsp;</div>
	</div>
</div>
<?php
break;

case 'user_cities_home';
?>
<div class="item element">
	<a class="item_link" href="<?php echo Conf::$page['album_generated'] . $user->id . '/0/' . $album['city_id'] ?>/0">
		<img alt="" class="img" src="<?php echo Conf::$src['thumbs'] , $user->id , $album['pic'] ?>">
	</a>
	<div class="bottom_part">
		<div class="spacer20">&nbsp;</div>
		<div class="spacer10"><?php echo WorldDatabase::getFullLocation($album['city_id'], ', ', true) ?> (<?php echo $album['num_photos'] . ' ' . $this->loadString('photos_lower') ?>)</div>
		<div class="spacer20">&nbsp;</div>
	</div>
</div>
<?php
break;


case 'explore_results_by_cat_0':
?>
<div class="item element">

	<a class="item_link" href="<?php echo Conf::$page['album_generated'] , $fetch['user_id'] , '/' , $cat_id , '/', $city; ?>">
	<?php
	
	$result = DB::sql('SELECT src FROM sc_pics WHERE user_id="' . $fetch['user_id'] . '" AND cat_id="' . $cat_id . '" AND city_id="' . $city . '" LIMIT 1');
	$fetch2 = $result->fetch(PDO::FETCH_ASSOC);
	?>
		<img alt="" class="img" src="<?php echo Conf::$src['thumbs'], $fetch['user_id'] , $fetch2['src'] ?>"/>
	</a>
	
	<div class="bottom_part">
		<a class="user_pic fl" title="<?php echo $user_data['fullname']; ?>" href="<?php echo Conf::$page['profile_view'], $fetch['user_id'] ?>">
			<img alt="" src="<?php echo $user_data['nav_user_pic'] ?>">
		</a>
		<h6 class="fr"><?php echo Categories::getCategoryAndParent($cat_id, ', ', true); ?></h5>
		<h5 class="fr"> <?php echo $user_data['fullname']; echo WorldDatabase::getFullLocation($city, ', ', true); ?> </h5>
		<div class="cleaner"></div>
	</div>
	
</div>
<?php
break;

case 'explore_results_by_subcat':

?>

<div class="item element">
	<a class="item_link" href="<?php echo Conf::$page['album_generated'] , $fetch['user_id'] , '/' , $cat_id , '/', $city; ?>">
	<?php
	$result = DB::sql('SELECT src FROM sc_pics WHERE user_id="' . $fetch['user_id'] . '" AND cat_id="' . $cat_id . '" LIMIT 1');
	$fetch2 = $result->fetch(PDO::FETCH_ASSOC);
	?>
		<img alt="" class="img" src="<?php echo Conf::$src['thumbs'], $fetch['user_id'] , $fetch2['src'] ?>"/>
	</a>
	<div class="bottom_part">
		<a class="user_pic fl" title="<?php echo $user_data['fullname']; ?>" href="<?php echo Conf::$page['profile_view'], $fetch['user_id'] ?>">
			<img alt="" src="<?php echo $user_data['nav_user_pic'] ?>">
		</a>
		<h6 class="fr"><?php echo Categories::getCategoryAndParent($cat_id, ', ', true); ?></h5>
		<h5 class="fr"><?php echo WorldDatabase::getFullLocation($city, ', ', true); ?></h5>
		<div class="cleaner"></div>
	</div>
</div>

<?php

break;

case 'explore_results_by_cat_1':

?>

<div class="item element">
	
	<a class="item_link" href="<?php echo Conf::$page['album_generated'] , $fetch['user_id'] , '/' , $cat_id , '/', $city; ?>">
		<?php
		$result = DB::sql('SELECT src FROM sc_pics WHERE user_id="' . $fetch['user_id'] . '" AND cat_id="' . $cat_id . '" AND city_id="' . $city . '" LIMIT 1');
		$fetch2 = $result->fetch(PDO::FETCH_ASSOC);
		?>
		<img alt="" class="img" src="<?php echo Conf::$src['thumbs'], $fetch['user_id'] , $fetch2['src'] ?>"/>
	</a>
			
	<div class="bottom_part">
		<a class="user_pic fl" title="<?php echo $user_data['fullname']; ?>" href="<?php echo Conf::$page['profile_view'], $fetch['user_id'] ?>">
			<img alt="" src="<?php echo $user_data['nav_user_pic'] ?>">
		</a>
		<h6 class="fr"><?php echo Categories::getCategoryAndParent($cat_id, ', ', true); ?></h5>
		<h5 class="fr"><?php echo WorldDatabase::getFullLocation($city, ', ', true); ?></h5>
		<div class="cleaner"></div>
	</div>
	
</div>
<?php
break;

case 'explore_results_parent_bycat':

?>

<div class="item element">
	<a class="item_link" href="<?php echo Conf::$page['album_generated'], $fetch['user_id'] , '/' , $cat_id , '/', $city; ?>">
	<?php
		$result = DB::sql('SELECT src FROM sc_pics WHERE user_id="' . $fetch['user_id'] . '" AND cat_id="' . $fetch['cat_id'] . '" LIMIT 1');
		$fetch2 = $result->fetch(PDO::FETCH_ASSOC);
			echo '<img alt="" class="img" src="', Conf::$src['pics'] , $fetch['user_id'] , $fetch2['src'] , '"/>';
	?>
	</a>
	<div class="bottom_part">
		<a class="user_pic fl" title="<?php echo $user_data['fullname']; ?>" href="<?php echo Conf::$page['profile_view'], $fetch['user_id'] ?>">
			<img alt="" src="<?php echo $user_data['nav_user_pic'] ?>">
		</a>
		<h6 class="fr"><?php $cat_id = isset($fetch['cat_id']) ? $fetch['cat_id'] : $cat_id; echo Categories::getCategoryAndParent($cat_id, ', ', true); ?></h5>
		<h5 class="fr"><?php echo WorldDatabase::getFullLocation($city, ', ', true); ?></h5>
		<div class="cleaner"></div>
	</div>
</div>

<?php

break;

case 'explore_results_parent_bysubcat':

?>

<div class="item element">
	<a href="<?php echo Conf::$page['album_generated'], $fetch['user_id'], '/' , $cat_id , '/', $city; ?>">
	<?php
	$result = DB::sql('SELECT src FROM sc_pics WHERE user_id="' . $fetch['user_id'] . '" AND cat_id="' . $fetch['cat_id'] . '" AND city_id="' . $key . '" LIMIT 1');
	$fetch2 = $result->fetch(PDO::FETCH_ASSOC);
	?>
		<img alt="" class="img" src="<?php echo Conf::$src['thumbs'], $fetch['user_id'], $fetch2['src']; ?>"/>
	</a>
	<div class="bottom_part">
		<a class="user_pic fl" title="<?php echo $user_data['fullname']; ?>" href="<?php echo Conf::$page['profile_view'], $fetch['user_id'] ?>">
			<img alt="" src="<?php echo $user_data['nav_user_pic'] ?>">
		</a>
		<h6 class="fr"><?php $cat_id = isset($fetch['cat_id']) ? $fetch['cat_id'] : $cat_id; echo Categories::getCategoryAndParent($cat_id, ', ', true); ?></h5>
		<h5 class="fr"><?php echo WorldDatabase::getFullLocation($city, ', ', true); ?></h5>
		<div class="cleaner"></div>
	</div>
</div>

<?php

break;


case 'explore_results_by_city':
?>

<div class="item element">
	<a class="nav_link" href="<?php echo Conf::$page['album_generated'] . $value['id'] . '/0/' . $value['city_id'] ?>">
		<img alt="" class="img" src="<?php echo Conf::$src['thumbs'] , $value['id'] , $value['pic'] ?>"/>
	</a>
<div class="bottom_part">
	<a class="user_pic fl" title="<?php echo $value['name']; ?>" href="<?php echo Conf::$page['profile_view'] . $value['id'] ?>">
		<img alt="" src="<?php echo $value['nav_user_pic'] ?>"/>
	</a>
	<h5 class="fr"><?php echo WorldDatabase::getFullLocation($value['city_id'], ', ', true); ?></h5>
		<div class="cleaner"></div>
	</div>
</div>

<?php
break;


} ?>
