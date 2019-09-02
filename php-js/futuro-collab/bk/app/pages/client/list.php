<? include(Conf::DIR_INCLUDES . 'htmlheader.php'); ?>

	</head>
	<body>
	
		<? include(Conf::DIR_INCLUDES . 'topheader.php'); ?>
		
        <div class="row-fluid">
        	<div class="span12">
            	<div class="well">
                   	<div class="row-fluid">
		                <div class="span5 pull-left">
							<h4>Klijenti</h4>
						</div>
                		<div class="span5 pull-right">
                			<a class="btn pull-right font-normal" href="<?= Core::$router->back ?>"><i class="icon-arrow-left"></i> <?= $txt['back'] ?></a>
						
							<? if(Core::$user->level !== 2): ?>
							
								<a class="btn pull-right margin-right-10 font-normal form-toggle" data-id="form-new-client" data-text="Dodaj novog klijenta" data-active="Zatvori formu" href="#"><i class="icon-plus-sign"></i> Dodaj novog klijenta</a>
							
							<? endif; ?>
							
						</div>
					</div>
					
					<br />
					
					<? if(Core::$user->level !== 2): ?> <? include('app/widgets/forms/client-new.php'); ?> <? endif; ?>
					
					<br />
					
					<input type="text" id="pagination-search" placeholder="Pretraži"/>
				
					<table class="table">
						<thead>
							<tr>
						
							<? if( Core::$user->level !== 2 ): ?>
							
								<th>Ime</th>
								<th class="hidden-phone">email</th>
								<th class="hidden-phone">Informacije</th>
								<th>Akcije</th>
								
							<? else: ?>
							
								<th>Ime</th>
								<th class="hidden-phone">email</th>
								<th>Informacije</th>
								
							<? endif; ?>
						
							</tr>
						</thead>
					
						<tbody id="pagination-content"></tbody>
					
					</table>
				
					<div class="unselectable pagination">
						<ul id="pagination"></ul>
					</div>
					
				</div>
			</div>
		</div>

<?php

$footer = '
<script src="/src/js/page/client.js"></script>
<script>
$(document).ready(function()
{
	$("#pagination").pagination(
	{
		dataType : "client",
		search : true
	});
});
</script>';

include(Conf::DIR_INCLUDES . 'htmlfooter.php');

?>