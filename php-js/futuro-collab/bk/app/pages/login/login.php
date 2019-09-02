<? include Conf::DIR_INCLUDES . 'htmlheader.php' ?>
	<head>
	<body>
		 <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="brand" href="/">Futuro Collab</a>
                </div>
            </div>
        </div><!-- /navbar -->
        <div class="container main-container">
            <div class="row-fluid">
                <br />    
                <div class="span4 offset4 well">
                    <legend>Prijava</legend>
                    
                    <? if( isset($error) ): ?>
					
						<div class="alert alert-error">
							<a class="close" data-dismiss="alert" href="#">×</a><?= $error ?>
						</div>
						
                    <? endif; ?>
                    
                    <form action="" method="post">
                        <input type="text" id="username" class="span12 text-center" name="username" placeholder="Korisničko ime" required />
                        <input type="password" id="password" class="span12 text-center" name="password" placeholder="Lozinka" required />
                        <?php /*
                        <label class="checkbox margin-bottom-15">
                            <input type="checkbox" name="remember" value="1" /> Zapamti me
                        </label>
						*/?>
                        <button type="submit" name="submit" class="btn btn-info btn-block">Prijava</button>
                    </form>
                </div>
            </div><!-- /row -->
		
<?php 
$footer = '
<script>

$(window).load(function()
{
	$("#login-box input:first").focus();
});

</script>
';

?>

<? include(Conf::DIR_INCLUDES . 'htmlfooter.php'); ?>
