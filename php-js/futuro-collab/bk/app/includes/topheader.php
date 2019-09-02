<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
		<div class="container">
			<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="brand" href="/">Poskok</a>
			<div class="nav-collapse collapse">
				<ul class="nav">
					<li <? if(self::$routeInfo['page'] === 'index' ){ echo 'class="active"'; } ?>><a href="/">Početna</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Zadaci <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/task">Svi zadaci</a></li>
							<li><a href="/task#userid=<?= Core::$user->id ?>">Moji zadaci</a></li>
                        </ul>
					</li>
					
                    <li class="dropdown <? if(self::$routeInfo['page'] === 'project' || self::$routeInfo['page'] === 'client'){ echo 'active'; } ?>">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $txt['projects'] ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="/client"><?= $txt['clients'] ?></a></li>
                            <li class="divider"></li>
                           	<li><a href="/project">Svi Projekti</a></li>
                            <li class="dropdown-submenu">
								<a tabindex="-1" href="#"><?= $txt[0] ?></a>
								<ul class="dropdown-menu">
									
									<? foreach($activeProjects as $info): ?>
							
										<li class="active-project"><a href="/project/<?= $info['projectid'] ?>"><?= $info['title'] ?></a></li>
										
									<? endforeach; ?>
									
								</ul>
							</li>
							<?
								/* if( Core::$user->level !== 2):
							?>
							
								<li><a href="#" class="popup-trigger" data-id="getAccessForm/project/"><?= $txt[1] ?></a></li>
							
							<? endif; */
						
							?>
						
                        </ul>
                    </li>
					
					
					<? if( Core::$user->level === 0 ): ?>
					
						<li class="dropdown <? if( self::$routeInfo['page'] === 'system' ){ echo 'active'; } ?>">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Sustav <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="/system">Info</a></li>
								<li><a href="/system/users">Korisnici</a></li>
							</ul>
						</li>
					
					<? endif; ?>
					
					<li class="dropdown <? if( self::$routeInfo['page'] === 'user' && self::$routeInfo['action'] === 'home'){ echo 'active'; } ?>">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?= $user['fullname'] ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/user/home">Moj Profil</a></li>
							<!--
							<li class="dropdown-submenu">
								<a tabindex="-1" href="#">Obavijesti (3)</a>
								<ul class="dropdown-menu">
									<li><a href="#">Futuro Admin vam je zadao zadatak u projektu Futuro.</a></li>
									<li><a href="#">Futuro Admin je komentirao na vaš projekt Futuro.</a></li>
									<li><a href="#">Juraj Hilje je izbrisao vaš zadatak "Rješiti thumbove".</a></li>
									<li class="divider"></li>
									<li><a href="#">Ukloni obavijesti</a></li>
								</ul>
							</li>
							-->
							<li><a href="#" class="show" data-id="control-panel"><?= $txt['cpanel'] ?></a></li>
							<li>
								<a href="#" class="popup-trigger" data-onclose="refresh();" data-id="getSettingsForm/libUser/">
									<?= $txt[3] ?>
								</a>
							</li>
							<li><a href="#" class="popup-trigger" data-id="help/general/"><?= $txt['help'] ?></a></li>
							<li class="divider"></li>
							<li><a href="/logout"><?= $txt[2] ?></a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
    </div>
</div>
<!-- /navbar -->
<div class="container main-container">
	<div class="row-fluid">
		<div class="span12">
			<form class="pull-right">
				<div class="input-prepend">
					<span class="add-on"><i class="icon-search"></i></span>
					<input type="text" name="keyword" id="global-search" placeholder="Klijent, zadatak..." data-lastvalue="" />
				</div>
			</form>
		</div>
	</div><!-- /row -->
	<div class="row-fluid search-results" style="display:none;">
		<div class="span12">
			<div class="well">
				<p class="lead"></p>
				
				<div id="global-search-pagination-content"></div>
				
				<div class="pagination margin-bottom-5">
					<ul id="global-search-pagination"></ul>
				</div>
			</div>
		</div>
	</div>
<!-- /row -->

<? if( isset( $error ) ) : ?>

<div class="alert alert-error">
	
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	
	<? if(isset($error[1])): ?>
	<ul>
		<? foreach($error as $item): ?>
		
			<li>
				<?= is_numeric ($item) ? $txt['error'][$item] : $item ?>
			</li>
		
		<? endforeach; ?>
	</ul>
	<? else: ?>
		
		<p class="margin-0"><?= is_numeric ($error[0]) ? $txt['error'][$error[0]] : $error[0] ?></p>
		
	<? endif; ?>
</div>

<? endif; ?>



<? if( isset( $success ) ) : ?>

<div class="alert alert-success" id="success-list">

	<button type="button" class="close" data-dismiss="alert">&times;</button>
	
	<? if(isset($success[1])): ?>
	<ul>
		
		<? foreach($success as $item ): ?><li> <?= is_numeric ($item) ? $txt['success'][$item] : $item ?> </li><? endforeach; ?>
		
	</ul>
	<? else: ?>
		
		<p class="margin-0"><?= is_numeric ($success[0]) ? $txt['success'][$success[0]] : $success[0] ?></p>
		
	<? endif; ?>
</div>

<? endif; ?>

<div class="row-fluid display-toggle" <? if( ! isset( $cPanel ) || $cPanel !== true ){ echo 'style="display:none;"';} ?> data-id="control-panel">
	<div class="span12">
    	<div class="well">
      		<ul class="nav nav-list">
        		<li class="nav-header">Kontrol panel</li>
	            <li><a href="/task#userid=<?= Core::$user->id ?>">Moji zadaci</a></li></li>
	            <?php /* <li><a class="popup-trigger" data-id="getAccessForm/project/" href="#">Pristupni Podaci</a></li> */ ?>
	            <li><a href="/user/home">Moj Profil</a></li>
	            <li><a href="#" class="popup-trigger" data-onclose="refresh();" data-id="getSettingsForm/libUser/"><?= $txt[3] ?></a></li>
			</ul>
    	</div>
	</div>
</div>

<? if( isset($timedBack) ):

$footer = "
<script>
$(document).ready(function()
{
	timedBack( $timedBack );
});
</script>
";

endif; ?>
