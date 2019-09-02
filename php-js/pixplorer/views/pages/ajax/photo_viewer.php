<script>

$(document).ready(function(){
	$('#scrollbar1').tinyscrollbar();
	$('#comment_mask').fadeTo('fast', 0.85);
	$('.arrow').fadeTo(0, 0.1);
	$('#bottom').css('opacity', 0.8);
	
	<?php
		if($this -> registry -> user -> logged_in){
			?>
			$('#comment').focus();
			<?php
		}
	?>
	
	if($('#container').attr('data-photo_navigation') === 'true'){
		var $box = $('#item_' + $('#image').attr('data-pic_id'));
		createNavigation($box.prev().attr('data-id'), $box.next().attr('data-id'));
	}
});

</script>

<?php echo $this -> loadSrc('gallery_options.js') ?>

<div id="viewer_wrapper">
	<?php 
		if(isset($data))
		{
			$location = WorldDatabase::getCityAndParent($data['city_id'], ', ');
			// Picture::updateViewCount($data['pic_id']);
			
	?>
	<div id="left" class="l_elem">
		<?php if(!isset($event)){ $event = 'closePopupViewer()';  } ?>
		<div id="image_container">
			<img alt="" id="image" data-pic_id="<?php echo $data['pic_id'] ?>" onload="vAlignMe('#image')" src="<?php echo Conf::$src['pics'], $data['user_id'] , $data['src']?>"/>	
		</div>
		<?php
			$data = Picture::addSocialData($data, $location);
		?>
		<div id="bottom">
		<?php include(Conf::$dir['widgets'] . 'photo_bottom_part.php'); ?>
		</div>
		<?php include(Conf::$dir['widgets']. 'photo_arrows.php') ?>
		
	</div>
		
	<div id="right" class="l_elem">	
		
		<a title="<?php echo $this -> loadString('close') ?>" id="close"></a>
		<div id="move">
		
			<div class="user">
				<a href="<?php echo Conf::$page['profile_view'] , $data['user_id']?>" class="fl">
					<img alt="" class="user_pic" src="<?php echo $data['nav_user_pic']?>"/>
				</a>
				
				<a class="link" href="<?php echo Conf::$page['profile_view'] , $data['user_id']?>" class="fl"><?php echo $data['fullname'] ?></a>
				<div class="cleaner"></div>
			</div>
			
			<div class="border"></div>
			
			<div class="wiki">
			
				<a target="_blank" href="<?php echo Conf::$url['wikiquery'] . clearAsterisk($data['cityName']) ?>" title="<?php echo $this -> loadString('photo_search_wikipedia') ?>">
					<img alt="" class="hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>nav/hover_wiki.png" src="<?php echo Conf::$src['images'] ?>nav/wiki.png"/>
				</a>
				<span>
				<a id="location_link" class="hover_underline" title="<?php echo $this -> loadString('explore') ?>" href="<?php echo Conf::$page['search_categories'] , $data['city_id'] ?>">
					<?php echo $location ?>
				</a>
				</span>
				<a id="booking_link" target="_blank" title="<?php echo $this -> loadString('photo_search_booking')?>" class="fr" href="<?php echo Conf::$url['booking'] ?>">
					<img alt="" class="hover_img" data-hover-img="<?php echo Conf::$src['images'] ?>hover_booking.png" src="<?php echo Conf::$src['images'] ?>booking.png"/>
				</a>
				<div class="cleaner"></div>
			</div>
			
			<div class="border"></div>
			
			<div class="fl description">
			
				<div class="cleaner" style="height: 25px">
					<?php 
						$description = $data['description'] !== '' ? '"' . $data['description'] . '"' : '';
					?>
					<span><?php echo $description ?></span>
				</div>
				
				<div class="spacer10"></div>
				<div class="spacer10" style="text-align: right;">
					<?php
						$href = $data['cat_id'];
					?>
					<a id="category_link" class="hover_underline" title="<?php echo $this -> loadString('explore') ?>" href="<?php echo Conf::$page['search_categories'] ?>0/<?php echo $href; ?>">
						<?php
							echo Categories::getCategoryAndParent($data['cat_id'], ' - ');
						?>
					</a>
				</div>
				
			</div>
			<div class="cleaner"></div>
			<div class="border border_after_desc"></div>

			
			
<div class="likes fl"><?php include(Conf::$dir['widgets'] . 'photo_likes_ads.php'); ?></div>
<script>refreshLikes();</script>
			
			<div class="ads fl">
				<a href=""><img alt="" src="<?php echo Conf::$src['images'] ?>ad.png"/></a>
			</div>
			<div class="cleaner"></div>
			<div id="comments_container">
				<?php include(Conf::$dir['widgets'] . 'photo_comments.php') ?>
			</div>
		</div>
	</div>
			<?php
		}else{
			?>
			
			<div id="warning2">
			
			<?php printf($this -> loadString('pictures_does_not_exist_redirect'), '<a href="/">', '</a>'); ?>
			</br>
			<?php printf($this -> loadString('pictures_does_not_exist_redirect2'), '<span id="counter">', '</span>'); ?>
			
			</div>
<script> 
	setInterval(function(){ window.location = "/"; }, 5000);
	setInterval(function(){ var num = parseInt($('#counter').text());  $('#counter').text(num - 1)}, 1000);
</script>
	
			<?php
		}
	?>
	
</div>
