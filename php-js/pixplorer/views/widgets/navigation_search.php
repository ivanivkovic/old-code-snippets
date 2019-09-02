<?php include(Conf::$dir['widget_data'] . 'navigation2.php'); ?>
	<div id="navigation2" class="navigation">
	<div class="items">
		<ul>
			<?php foreach($items as $item){ ?>
				<li>
					<a id="menu_<?php echo $item['id'] ?>" title="<?php echo $item['title'] ?>" <?php echo $item['href']?> >
						<img alt="" class="nav_img hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>nav/<?php echo $item['pic_hover']?>" src="<?php echo Conf::$src['images'] ?>nav/<?php echo $item['pic']?>"/>
					</a>
				</li>
			<?php } ?>
		</ul>
	</div>
	
	<?php
	if(isset($errormsg)){
	?>
		<a id="logo" title="<?php echo $this -> loadString('nav_logo_home') ?>" href="<?php echo SITE_URL ?>">
			<img alt="" src="<?php echo Conf::$src['images'], 'logo.png' ?>"/>
		</a>
	<?php
	}
	
		# Getting true criteria.
		if(isset($city_name)){
			$wikicriteria = $city_name;
		}
		
	?>
	
	<?php
	if($this -> registry -> user -> logged_in)
	{ 
		include(Conf::$dir['widgets'] . 'profile_menu.php');
	}
	?>
	
	<div id="nav_right">
		<ul>
			<?php include(Conf::$dir['widgets'] . 'search.php'); ?>
			<?php if($this -> registry -> router -> page == 'upload' && $this -> registry -> router -> action == 'result'){ ?>
			<li>
				<a id="nextSubmit" title="<?php echo $this -> loadString('submit') ?>" target="_blank">
					<img alt="" class="right_image hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>nav/hover_submit.png" src="<?php echo Conf::$src['images'] ?>nav/submit.png"/>
				</a>
			</li>
<script>
$('#nextSubmit').click(function(){
	$('#ids_list').appendTo('#container');
	$('#container').submit();
});
</script>
			
			<?php } ?>
			<?php if(isset($city_name)){ ?>
			<li>
				<a title="<?php echo $this -> loadString('photo_search_booking')?>" href="http://www.booking.com" target="_blank">
					<img alt="" class="right_image hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>hover_booking2.png" src="<?php echo Conf::$src['images'] ?>booking2.png" />
				</a>
			</li>
			<?php } ?>
			<?php if(isset($wikicriteria)){ ?>
			<li>
				<a title="<?php echo $this -> loadString('nav_wiki')?>" href=<?php echo Conf::$url['wikiquery'] . $wikicriteria ?>" target="_blank">
					<img alt="" class="right_image hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>nav/hover_wiki.png" src="<?php echo Conf::$src['images'] ?>nav/wiki.png"/>
				</a>
			</li>
			<?php } ?>
		</ul>
	</div>
	<div id="navigation_headline">
		
			<?php 
			if(isset($nav_headline) && !isset($errormsg))
			{
				echo '<a>', $nav_headline;
			}
			else
			{
				echo '<a href="javascript:window.location = location.href;">';
				if(isset($cat_name))
				{
					echo $cat_name;
				}
				if(isset($city_name) && isset($parent_data))
				{
					if(isset($cat_name))
					{
						echo ' ' , $this -> loadString('conj_in') , ' ';
					}
					echo $city_name , ', ';
					echo $parent_data['name'];
				}
				
				if(isset($parent_name))
				{
					echo ' ' , $this -> loadString('conj_in') , ' ' , $parent_name;
				}
			}
			?>
		</a>
	</div>
	
</div>
