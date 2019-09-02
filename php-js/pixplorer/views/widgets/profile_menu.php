<?php

$ncount = Notifications::getUnreadNotificationsCount($this -> registry -> user -> id);
$ncount = $ncount != '0' && $ncount !== false ? $ncount : '';

?>

<div id="profile_icon" class="profile_stack profile_no_hover">
	<a href="<?php echo Conf::$page['profile_view'] . $this -> registry -> user -> id ?>">
		<img alt="" class="user_pic" id="social_image" src="<?php echo $this -> registry -> user -> data['nav_user_pic'] ?>"/>
	</a>
	
</div>
<div id="nFlag" <?php if($ncount !== 0 && $ncount !== ''){ echo 'class="display_block"'; }else{ echo 'class="display_none"'; }?>><?php echo $ncount; ?></div>
<div id="profile_menu" class="profile_stack display_none">
	
	<ul>
	
		<li onclick="window.location=''">
			<a href="<?php echo Conf::$page['notifications'] ?>"><?php echo $this -> loadString('profile_notifications')?>
				<span id="nCount"><?php if($ncount !== 0 && $ncount !== ''){echo '(' . $ncount . ')'; } ?></span>
			</a>
			<?php unset($ncount); ?>
		</li>
		
		<li onclick="window.location='<?php echo Conf::$page['profile_home'] ?>'">
			<a href="<?php echo Conf::$page['profile_home'] ?>"><?php echo $this -> loadString('profile_my_profile')?></a>
		</li>
		
		<li onclick="window.location='<?php echo Conf::$page['logout'] ?>'">
			<a href="<?php echo Conf::$page['logout'] ?>"><?php echo $this -> loadString('profile_logout')?></a>
		</li>
		
	</ul>
	
</div>