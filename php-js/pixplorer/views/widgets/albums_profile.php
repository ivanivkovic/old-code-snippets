	<?php
		if(isset($albums)){
			foreach($albums as $album){
				?>
				<div class="item element">
					<?php include(DIR_WIDGETS . 'item_dark_box.php'); ?>
					<a class="item_link" href="<?php echo PAGE_ALBUM_GENERATED . $user_data['user_id']  . '/0/' . $album['id'] ?>">
						<img alt="" class="img" src="<?php echo SRC_THUMBS , $user_data['user_id'] , $album['pic'] ?>">
					</a>
						<div class="bottom_part">
							<div class="spacer20">&nbsp;</div>
								<div class="spacer10">
									<?php echo clearAsterisk($album['name']) , ', ' , $album['parent_name'] ?> (<?php echo $album['num_photos'] . ' ' . $this -> loadString('photos_lower') ?>)
								</div>
							<div class="spacer20">&nbsp;</div>
						</div>
				</div>
				<?php
			}
	?><div class="cleaner"></div><?php 
		}else{/*
			if(isset($errormsg)){
				echo $errormsg;
			}*/
		}
	?>
