<? include(Conf::DIR_INCLUDES . 'htmlheader.php'); ?>
	</head>
	<body>
	
		<? include(Conf::DIR_INCLUDES . 'topheader.php'); ?>
		
		
		 <div class="row-fluid">
        	<div class="span12">
            	<div class="well">
                   	<div class="row">
		                <div class="span5 pull-right">
							
							<? if(Core::$user->level !== 2): ?>
							
								<a class="btn pull-right font-normal delete" data-id="<?= $clientData['clientid'] ?>" href="#"><i class="icon-remove"></i> Izbriši klijenta</a>
								<a class="btn pull-right margin-right-10 font-normal edit" data-id="<?= $clientData['clientid'] ?>" href="#"><i class="icon-edit"></i> Uredi klijenta</a>
							
							<? endif; ?>
							
							<a class="btn pull-right font-normal margin-right-10" href="<?= Core::$router->back ?>"><i class="icon-arrow-left"></i> <?= $txt['back'] ?></a>

						</div>
						<div class="span5 pull-left">
							<h4>Klijent <?= $clientData['name'] ?></h4>
						</div>
					</div>
					
					<br/>
					
		            <div class="row-fluid">
		                <div class="span12">
							<p><?= libString::UrlToA( $clientData['info'], true ) ?></p>
							<h5>Projekti</h5>
							
							<? foreach( $clientData['projects'] as $project ): ?>
								<p><a href="/project/<?= $project['projectid'] ?>"><?= $project['title'] ?></a></p>
							<? endforeach; ?>
		                </div>
					</div>
				</div>
		 	</div>
		 </div>
		
	<?php $footer = '<script src="/src/js/page/client.js"></script>'; ?>
<? include(Conf::DIR_INCLUDES . 'htmlfooter.php'); ?>