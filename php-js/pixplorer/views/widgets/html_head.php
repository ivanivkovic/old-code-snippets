<!DOCTYPE html>

<html dir="ltr" lang="en-US" xmlns:fb="https://www.facebook.com/2008/fbml" itemscope itemtype="http://schema.org/photo">
	<head>
		<title>
			<?php echo SITE_NAME ?><?php if(isset($title) && $title !== ''){ echo ' - ' . $title;} ?>
		</title>
		
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		
		<meta http-equiv="cache-control" content="cache"/>
		
		<meta name="keywords" 		content="pixplorer <?php if(isset($meta_keywords)){echo $meta_keywords;} ?>"/>
		<meta name="description" 	content="<?php if(isset($meta_description)){ echo $meta_description; } ?>"/>
		
		
		
		<meta itemprop="name" 			content="<?php echo SITE_NAME ?> - <?php echo $title?>"/>
		<meta itemprop="description" 	content="<?php if(isset($meta_description)){ echo $meta_description; } ?>"/>
		
		<?php if(isset($fb_meta_image)){ ?> 	<meta property="og:image" content="<?php echo $fb_meta_image; ?>"/> 	<?php } ?>
		<?php if(isset($fb_meta_url)){ ?> 		<meta property="og:url" content="<?php echo $fb_meta_url; ?>"/> 		<?php } ?>
		
		<meta property="fb:app_id"			content="177259275703895"/> 
		<meta property="og:type"			content="website"/>
		<meta property="og:title"			content="<?php echo SITE_NAME ?> - <?php echo $title; ?>"/> 
		<meta property="og:description"		content="<?php if(isset($meta_description)){ echo $meta_description; }?>"/>
		
		
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
		
		<script type="text/javascript">
			window.siteurl = '<?php echo SITE_URL ?>';
			window.curpage = '<?php echo $this->registry->router->page ?>';
			window.curaction = '<?php echo $this->registry->router->action ?>';
			window.criteria = '<?php echo $this->registry->router->criteria ?>';
			window.criteria2 = '<?php echo $this->registry->router->criteria2 ?>';
		</script>
		
		<?php if($this->registry->router->page != ''){ $footer = true;}else{$footer = false;} #Page exception that does not have footer. ?>
		
		<link rel="shortcut icon" href="<?php echo Conf::$src['images'] ?>favicon.ico"/>
	<?php
		if($this->registry->router->page === 'index' || $this->registry->router->page === 'categories')
		{
			if($this->registry->user->logged_in)
			{
				$this->loadSrc('nav_fade.js');
			}
			else
			{
				$this->loadSrc('nav_fade2.js');
			}
		}else{
			$this->loadSrc('nav_fade.js');
		}
	?>
	
	<?php $this->loadSrc('all.js') ?>
		
	<?php $this->loadSrc('all.css') ?>