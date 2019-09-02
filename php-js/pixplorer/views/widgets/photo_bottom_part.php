<ul id="options" class="bottom_menu bottom_menu_left">

<?php

$this -> registry -> template -> data = $data;
$this -> registry -> template -> loadWidget('options_default'); 

if($this -> registry -> user -> logged_in === true){ 

	$file = $this -> registry -> user -> id === $data['user_id'] ? 'home' : 'view';
	$this -> registry -> template -> loadWidget('options_' . $file); 

}
?>

</ul>

<ul id="fav" class="bottom_menu bottom_menu_right">
	<?php /* if($this -> registry -> user -> logged_in === false){ ?>
	<li>
		<a href="<?php echo DEFAULT_LOGIN_URL?>"><?php echo $this -> loadString('login_to_fav') ?></a>
	</li>
<?php } */?>

<?php 

if($this -> registry -> user -> logged_in === true)
{
	$user = $this -> registry -> user;
	
	$count = PhotoFavorites::getSubjectFavoritesCount($data['pic_id']);
	$count = $count != 0 ? $count : '';
	
	if(PhotoFavorites::isFavorite($user -> id, $data['pic_id']))
	{
		$class = 'favorite';
		$boolean = 'true';
		$title = $this -> loadString('remove_from_fav');
	}
	else
	{
		$class = 'not_favorite';
		$boolean = 'false';
		$title = $this -> loadString('add_to_fav');
	}
	
	?>
	<li>
		<?php if($this -> registry -> user -> id !== $data['user_id']){ ?>
		
			<a id="favoritesToggle" data-pic_id="<?php echo $data['pic_id'] ?>" data-boolean="<?php echo $boolean ?>" title="<?php echo $title?>" class="<?php echo $class ?>"></a>
			<a id="counter" class="no_decoration"><?php echo $count ?></a>
			
		<?php }else{ 
				 if($count !== ''){ ?>
				 
			<a id="counter" class="no_decoration"><?php printf($this -> loadString('photo_favorited_times'), $count); ?></a>
			
		<?php } } ?>
	</li>
<?php } ?>
</ul>