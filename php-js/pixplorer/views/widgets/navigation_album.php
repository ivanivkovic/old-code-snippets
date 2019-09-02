<?php include(Conf::$dir['widget_data'] . 'navigation2.php'); ?>
	<div id="navigation2" class="navigation">
	<div class="items">
		<ul>
			<?php foreach($items as $item){ ?>
				<li>
					<a id="menu_<?php echo $item['id'] ?>" title="<?php echo $item['title'] ?>" <?php echo $item['href']?> >
						<img alt="" class="nav_img hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>nav/<?php echo $item['pic_hover'] ?>" src="<?php echo Conf::$src['images'] ?>nav/<?php echo $item['pic']?>"/>
					</a>
				</li> 
			<?php } ?>
		</ul>
	</div>
	
	<?php
	if($this -> registry -> user -> logged_in)
	{ 
		include(Conf::$dir['widgets'] . 'profile_menu.php');
	}
	?>
	
	<div id="nav_right">
		<ul>
			<?php include(Conf::$dir['widgets'] . 'search.php'); ?>
			<?php $link = isset($city_name) ? 'href="' . Conf::$url['wikiquery'] . $city_name . '"' : ''; ?>
			
			<?php if($link !== ''){ ?>
			<li>
				<a title="<?php echo $this -> loadString('photo_search_booking')?>" href="http://www.booking.com/<?php echo $city_name ?>" target="_blank">
					<img alt="" class="right_image hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>hover_booking2.png" src="<?php echo Conf::$src['images'] ?>booking2.png" />
				</a>
			</li>
			<li>
				<a title="<?php echo $this -> loadString('nav_wiki')?>" <?php echo $link ?> target="_blank">
					<img alt="" class="right_image hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>nav/hover_wiki.png" src="<?php echo Conf::$src['images'] ?>nav/wiki.png" />
				</a>
			</li>
			<?php } ?>
			
		</ul>
	</div>
	<?php
		if(isset($nav_headline)){
	?>
	<div id="navigation_headline">
		<a>
			<?php echo $nav_headline ?>
		</a>
	</div>
	<?php } ?>
</div>
