<?php include(Conf::$dir['widget_data'] . 'navigation.php');  $navigation_id = isset($navigation_id) ? $navigation_id : 'navigation'; ?>

<div id="<?php echo $navigation_id ?>" class="navigation">
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
	<a id="logo" title="<?php echo $this -> loadString('nav_logo_home') ?>" href="/">
		<img alt="" src="<?php echo Conf::$src['images'] ?>logo.png"/>
	</a>
	
	<?php
	if($this -> registry -> user -> logged_in)
	{ 
		include(Conf::$dir['widgets'] . 'profile_menu.php');
	}
	?>
	
	<div id="nav_right">
		<ul>
		<?php include(Conf::$dir['widgets'] . 'search.php'); ?>
			
		<?php if(!$this -> registry -> user -> logged_in) { ?>
				<li>
					<a title="<?php echo $this -> loadString('nav_fb_button') ?>" href="<?php echo Conf::$page['fb_login'] ?>">
						<img alt="" class="right_image hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>facebook_hover.png" src="<?php echo Conf::$src['images'] ?>f.png"/>
					</a>
				</li>
		<?php } ?>
		</ul>
	</div>
	<div class="cleaner"></div>
	
	<?php if(!$this -> registry -> user -> logged_in){ ?>
	
		<div id="header_txt"><?php printf($this -> loadString('headline_txt'), '<a href="' .  Conf::$page['default_login']  . '">', '</a>', '<a href="">'); ?></div>
		
	<?php } ?>
</div>