<? include(Conf::DIR_INCLUDES . 'htmlheader.php'); ?>

	</head>
	<body>
		
		<? include(Conf::DIR_INCLUDES . 'topheader.php'); ?>
		
		<div class="row-fluid">
			<div class="span3"><? include(Conf::DIR_INCLUDES . 'leftmenu.php'); ?></div>
		
			<div class="span9">
            	<div class="well">
            		<div class="row-fluid">
		                <div class="span3">
                			<h4>Projekti</h4>
		                </div>
						<div class="span5 pull-right">
							<? if(Core::$user->level !== 2): ?>
							
							<a
								class="btn font-normal pull-right form-toggle"
								data-id="form-new-project"
								data-text="Dodaj novi projekt"
								data-active="Zatvori formu"
								href="#"
							>
								<i class="icon-plus-sign"></i> Dodaj novi projekt
							</a>
							
							<? endif; ?>
							
							<a class="btn pull-right font-normal margin-right-10" href="<?= Core::$router->back ?>">
								<i class="icon-arrow-left"></i> <?= $txt['back'] ?>
							</a>
						</div>
					</div>
					
				<br />
				
				<? include('app/widgets/forms/project-new.php'); ?>
				
				<input class="fl" type="text" id="pagination-search" placeholder="Pretraži"/>
				
				<br />
				
				<table class="table">
					<thead>
						<tr>
							<th>Projekt</th>
							<th class="hidden-phone"><a href="/task">Zadaci</a></th>
							<th class="hidden-phone">Započeo</th>
							<th class="hidden-phone">Klijent</th>
						</tr>
					</thead>
					
					<tbody id="pagination-content"></tbody>
					
				</table>
				
				<div class="unselectable pagination">
					<ul id="pagination"></ul>
				</div>
			</div>
		</div>

<?php

$footer = '
<script>

function Run(filter)
{
	$("#pagination").pagination(
	{
		dataType : "project",
		search : true,
		filters : { filter : filter }
	});
}

$(document).ready(function()
{
	Run( $(".filter.active").attr("data-id") );

	$( ".filter.active" ).parent().find(".filter").click(function()
	{
		Run( $(".filter.active").attr("data-id") );
		return false;
	});
	
	loadAutocomplete()
});

</script>
';

include(Conf::DIR_INCLUDES . 'htmlfooter.php');

?>