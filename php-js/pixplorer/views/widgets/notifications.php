<div class="item">
	<div class="darkbox">
		<?php
		if(isset($notifications[$key]))
		{
			$count = count($notifications[$key]);
		}else{
			$count = 0;
		}
		?>
		<h1><?php echo $item  ?> | <?php echo $count ?></h1>
	</div>
		<?php
		if(isset($notifications[$key]))
		{
			foreach($notifications[$key] as $dat)
			{
				switch($key)
				{
					case 0:
		?>
					<div class="bottom_part <?php if($dat['viewed'] == 0){echo 'new'; } ?> notification pviewer-trigger" data-id="<?php echo $dat['subject_id']?>">
						<a class="user_pic fl" title="<?php echo $dat['fullname'] ?>" href="<?php echo Conf::$page['profile_view'], $dat['user_poster_id'] ?>">
							<img alt="" src="<?php echo $dat['nav_user_pic']  ?>"/>
						</a>
						<p>
							<?php
								$string = Picture::getDescription($dat['subject_id']);
								$string = $string !== '' ? ' "' . limitString($string, 30, '...') . '"' : '';
								
								$src = Picture::getSrc($dat['subject_id'], Conf::$src['thumbs']);
								$src = $src['src'];
								
								printf($this -> loadString('notif_user_commented_on_your_photo'), $dat['fullname'], DB::fetchOne('content', 'sc_pic_comments', array('comm_id' => $dat['alt_id']) ));
							?>
						</p>
						
						<img alt="" class="notification_thumb fr" src="<?php echo $src ?>"/>
						<div class="cleaner"></div>
					</div>
					
					<?php
					break;
					
					case 1:
					?>
					<div class="bottom_part notification pviewer-trigger" data-id="<?php echo $dat['subject_id']?>">
						<a class="user_pic fl" title="<?php echo $dat['fullname'] ?>" href="<?php echo Conf::$page['profile_view'], $dat['user_poster_id'] ?>">
							<img alt="" src="<?php echo $dat['nav_user_pic']  ?>"/>
						</a>
						<p>
							<?php
								$string = Picture::getDescription($dat['subject_id']);
								$string = $string !== '' ? ' "' . limitString($string, 30, '...') . '"' : '';
								
								$src = Picture::getSrc($dat['subject_id'], Conf::$src['thumbs']);
								$src = $src['src'];
								
								printf($this -> loadString('notif_user_favorited_your_photo'), $dat['fullname'], ''/*$string */)
							?>
						</p>
						<img alt="" class="notification_thumb fr" src="<?php echo $src  ?>"/>
						<div class="cleaner"></div>
					</div>
					<?php
					break;
				}
			}
		}
		?>
</div>