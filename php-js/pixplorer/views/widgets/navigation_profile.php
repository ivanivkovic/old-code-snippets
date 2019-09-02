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
			
		<?php if($this -> registry -> router -> page === 'user' && $this -> registry -> router -> action == 'home'):/* ?>
			<li>
				<a title="<?php echo $this -> loadString('edit') ?>" href="javascript:void()">
					<img alt="" class="nav_img hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>hover_edit.png" src="<?php echo Conf::$src['images'] ?>edit.png" class=""/>
				</a>
			</li>
			
		<?php */ endif; ?>
		</ul>
	</div>
	<?php if(!isset($errormsg)){ ?>
	
		<div id="navigation_headline">
		
		<?php if(is_array($nav_headline)): ?>
		
			<?php foreach($nav_headline as $nav): ?>
			
				<?php if(isset($posted)){ echo '|'; } $posted = true; ?>
				
				<a <?php if($criteria === $nav['criteria']){ echo 'class="selected"'; } ?> title="<?php echo $nav['title'] ?>" href="<?php echo $nav['link'] . $nav['criteria']?>">
					<?php echo $nav['title'] ?>
				</a>
			
			<?php endforeach;?>
		
		<?php else: ?>
		
			<a title="<?php echo $nav_headline ?>" href="<?php echo $nav_link ?>">
				<?php echo $nav_headline ?>
			</a>
		
		<?php endif; ?>
		
	</div>
	
	<?php } ?>
</div>