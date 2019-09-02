<?php

$comments_class = $this -> registry -> user -> logged_in === true ? 'comments_normal' : 'comments_big';

if($this -> registry -> user -> logged_in){

?>
<div class="my_comment">
	<a class="user_pic fl">	
		<img alt="" id="my_pic" src="<?php echo $this -> registry -> user -> data['nav_user_pic'] ?>">
	</a>
	<input type="text" id="comment" onfocus="this.placeholder=''" onblur="this.placeholder='<?php echo $this -> loadString('popup_write_comment') ?>'" class="fl" placeholder="<?php echo $this -> loadString('popup_write_comment') ?>"/>
	<div class="cleaner"></div>
</div>
<?php }	?>

<div class="comments" id="scrollbar1">
	<div class="scrollbar">
		<div class="track">
			<div class="thumb"></div>
		</div>
	</div>
	<div class="viewport <?php echo $comments_class?>">
		 <div class="overview">
<?php

$data = Picture::fetchPicComments($data['pic_id']);

if($data !== false)
{
	while($fetch = $data -> fetch(PDO::FETCH_ASSOC))
	{
?>
	<div class="comment" id="com_<?php echo $fetch['comm_id'] ?>">
		<a class="fl user_pic" href="<?php echo Conf::$page['profile_view'] . $fetch['user_id'] ?>">
			<img alt="" src="<?php  echo $fetch['nav_user_pic']  ?>"/>
		</a>
		<div class="fl content">
			<a class="user_link" href="<?php echo Conf::$page['profile_view'] . $fetch['user_id'] ?>">
				<?php echo $fetch['fullname'] ?>
			</a>
			<span> - <?php echo $fetch['content'] ?></span>
		</div>
		
			<?php
			if($this -> registry -> user -> logged_in && $this -> registry -> user -> id == $fetch['user_id']){
				?>
				<div class="fr">
					<img alt="" class="com_close" data-id="<?php echo $fetch['comm_id'] ?>" src="<?php echo Conf::$src['images'] ?>close2.png"/>
				</div>
				<?php
			}
			?>             

		<div class="cleaner"></div>
	</div>
		<?php
	}
}

?>

		</div>
	</div>
	<?php 
/* PAST FEATURE THAT BLURS ALL COMMENTS FOR A VISITOR USER
	if($this -> registry -> user -> logged_in === false){ ?>
	<div id="comment_mask"></div>
	<div class="login_to_comment">
		<a href="<?php echo DEFAULT_LOGIN_URL?>" class="pointer hover_underline"><?php echo $this -> loadString('login_to_com'); ?></a>
	</div>
	
	<?php } */ ?>
</div>