<?php

if(isset($data))
{
	while($fetch = $data->fetch(PDO::FETCH_ASSOC))
	{
		?>
		<div class="element item" data-id="<?php echo $fetch['fav_id'] ?>" id="item_<?php echo $fetch['fav_id'] ?>">
			<a class="item_link" href="<?php echo Conf::$page['photo_view'], $fetch['fav_id'] ?>">
				<img alt="" class="img" src="<?php echo Conf::$src['thumbs'], $fetch['user_id'] . $fetch['src'] ?>"/>
			</a>
			<div class="bottom_part">
				<a class="user_pic fl" title="<?php echo $fetch['fullname'] ?>" href="<?php echo Conf::$page['profile_view'], $fetch['user_id'] ?>">
					<img alt="" src="<?php echo $fetch['nav_user_pic']  ?>"/>
				</a>
				<h6 class="fr">
				<?php 
					echo WorldDatabase::getFullLocation($fetch['city_id'], ', ');
				?>
				</h6>
				<h5 class="fr">
				<?php 
					$parent_category = Categories::fetchParent($fetch['cat_id']);
					
					if($parent_category !== false)
					{
						echo $parent_category['title'] , ', ';
					}
					echo Categories::fetchCatName($fetch['cat_id']);
				?>
				</h5>
				<div class="cleaner"></div>
			</div>
		</div>
		<?php
	}
}
?>