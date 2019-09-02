<?php

$user = new User($db);

$users_total = $user -> getCriteriaCount('all');
$users_facebook = $user -> getCriteriaCount('facebook');
$users_active = $user -> getCriteriaCount('active');
$users_banned = $user -> getCriteriaCount('inactive');

$pic = new Picture($db);

$today = date('Y-m-d');

$photos_total = $pic -> getCriteriaCount('all');
$photos_today = $pic -> getCriteriaCount($today);

?>

<!-- DODATI JQUERY SHOW NA SVAKOM BOXU, PRVOTNO SU HIDDEN -->

<div id="container">
	<div class="layout">
		<div class="overview_box">

			<span class="trigger" data-trigger="users">USERS</span>
			
			<div class="display_block" id="content_users">
			
				<div class="border_spacer10"></div>
				
				<p><a href="index.php?page=user_administration&criteria=all">Total</a> : <span><?php echo $users_total ?></span></p>
				
				<div class="border_spacer10"></div>
				
				<p><a href="index.php?page=user_administration&criteria=facebook">Facebook</a> : <span><?php echo $users_facebook ?></span></p>
				<p><a href="index.php?page=user_administration&criteria=active">Active</a> : <span><?php echo $users_active ?></span></p>
				<p><a href="index.php?page=user_administration&criteria=inactive">Banned</a> : <span><?php echo $users_banned ?></span></p>
				
				<div class="cleaner"></div>
			</div>
		</div>
		
	</div>
	
	<div class="layout">
	
		<div class="overview_box">

			<span class="trigger" data-trigger="pictures">PICTURES</span>

			<div class="display_block" id="content_pictures">
				<div class="border_spacer10"></div>
				
				<p><a href="index.php?page=photo_administration&criteria=all">Total</a> : <span><?php echo $photos_total ?></span></p>
				
				<div class="border_spacer10"></div>
				
				<p><a href="index.php?page=photo_administration&criteria=<?php echo $today?>">Today : <span><?php echo $photos_today ?></span></p>
				
				<div class="cleaner"></div>
				
			</div>
			
				
		</div>
	</div>

	<div class="layout">
		
	</div>
	<div class="cleaner"></div>

</div>

<script>
$(document).ready(function(){
	
	$('.trigger').click(function(){
	
		var trigger = $(this).attr('data-trigger');
		
		$obj = $('#content_' + trigger);
		
		if($obj.css('display') == 'none'){
			$obj.slideDown(200);
		}else{
			$obj.slideUp(200);
		}
		
	});
	
});
</script>
