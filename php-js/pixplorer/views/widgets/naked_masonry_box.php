<?php

if(!isset($mode))
{
	$mode = 'standard';
}

switch($mode){ 
	
	case 'standard': default: ?>

	<?php // include(Conf::$dir['widgets'] . 'item_dark_box.php'); ?>
	
	<a class="item_link" href="<?php echo Conf::$page['photo_view'], $fetch['pic_id'] ?>">
		<img alt="" class="img" src="<?php echo Conf::$src['thumbs'], $fetch['user_id'] . $fetch['src'] ?>"/>
	</a>
	<div class="bottom_part">
		<a class="user_pic fl" title="<?php echo $fetch['fullname'] ?>" href="<?php echo Conf::$page['profile_view'], $fetch['user_id'] ?>">
			<img alt="" src="<?php  echo $fetch['nav_user_pic']  ?>"/>
		</a>
		<h6 class="fr">
		<?php if($fetch['description'] !== ''){ ?>
			"<?php echo limitString($fetch['description'], BOX_DESCRIPTION_LIMIT, '...'); ?>"
		<?php } ?>
		</h6>
		<h5 class="fr"><?php echo WorldDatabase::getFullLocation($fetch['city_id'], ', ', true) ?></h5>
		<div class="cleaner"></div>
	</div>

<?php
break;

case 'search_user': ?>

	<a class="item_link" href="<?php echo Conf::$page['profile_view'] . $fetch['id'] ?>">
		<img alt="" class="profile_picture" src="<?php echo $fetch['src'] ?>"/>
	</a>
	
	<div class="bottom_part">
		<div class="spacer10"></div>
		<div class="spacer10"></div>
		<div class="spacer10">
			<a href="<?php echo Conf::$page['profile_view'] . $fetch['id'] ?>"><?php echo $fetch['name'] ?></a>
		</div>
		<div class="spacer10"></div>
		<div class="spacer10"></div>
	</div>
	
<?php break; ?>

<?php 

	case 'search_img':
	case 'search_city':
	case 'search_region':
	case 'search_country':
	
?>


	<?php // include(Conf::$dir['widgets'] . 'item_dark_box.php'); ?>
	<a class="item_link" href="<?php echo Conf::$page['photo_view'], $fetch['id'] ?>">
		<img alt="" class="img" src="<?php echo Conf::$src['thumbs'], $fetch['user_id'] . $fetch['src'] ?>"/>
	</a>
	<div class="bottom_part">
		<a class="user_pic fl" title="<?php echo $fetch['fullname'] ?>" href="<?php echo Conf::$page['profile_view'], $fetch['user_id'] ?>">
			<img alt="" src="<?php echo $fetch['nav_user_pic']  ?>"/>
		</a>
		<h6 class="fr">
		<?php if($fetch['name'] !== ''){ ?>
			"<?php echo limitString($fetch['name'], BOX_DESCRIPTION_LIMIT, '...'); ?>"
		<?php } ?>
		</h6>
		<h5 class="fr"><?php echo WorldDatabase::getFullLocation($fetch['city_id'], ', ', true) ?></h5>
		<div class="cleaner"></div>
	</div>
	

<?php 
	
break; 


} ?>
