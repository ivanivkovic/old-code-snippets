<?php $this->loadWidget('html_head'); ?>
		<?php $this -> loadSrc('masonry.css') ?>
		<?php $this -> loadSrc('upload.css') ?>
	</head>
	<body id="<?php echo $this -> registry -> router -> page ?>">	
	
		<div id="background"></div>
		
		<?php 
		
			$nav_headline = $this -> loadString('upload_result_description');
			include(Conf::$dir['widgets'] . 'navigation_search.php');
		
			if(isset($errormsg)){ ?>
				<div id="warning"><?php printf($errormsg, ' <a class="pointer underline" onclick="$(\'#menu_Upload\').click();">', '</a>') ?> </div>	
			<?php }else{	?>	
				<form id="container" style="position: relative; top: 85px;" action="<?php echo Conf::$page['upload_add_info'] ?>" method="POST">
		
		<?php foreach($uploads as $result){ ?>	
			<?php $mode = 'upload'; include(Conf::$dir['widgets'] . 'masonry_box.php'); ?>
		<?php } ?>
		<?php } ?>
		</form>
		<input style="display: none;" type="hidden" id="ids_list" name="ids_list" 
			value="<?php
				foreach($array as $key => $value)
				{
					echo $key . ',';
				}
			?>"/>
		
		<?php $this -> loadSrc('masonry.js') ?>
		<?php $this -> loadSrc('masonry_load2.js') ?>
<?php
	$this->loadWidget('html_footer');
?>