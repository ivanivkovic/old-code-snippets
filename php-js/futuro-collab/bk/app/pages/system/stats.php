<? include(Conf::DIR_INCLUDES . 'htmlheader.php'); ?>
	</head>
	<body>
		<? include(Conf::DIR_INCLUDES . 'topheader.php'); ?>
		
		<div class="row-fluid">
            <div class="span12">
				<? include(Conf::DIR_INCLUDES . 'pagenav.php'); ?>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span12">
				
				<div class="well">
					
					<p>Korisnici : <?= $stats['userCount'] ?></p>
					<p>Administratori :  <?= $stats['adminCount'] ?></p>
					<p>Glavni Administratori :  <?= $stats['superAdminCount'] ?></p>
					<p>Projekti :  <?= $stats['projectCount'] ?></p>
					<p>Nedovršeni Zadaci :  <?= $stats['taskCount'] ?></p>
					
				</div>
				
			</div>
		</div>

<? include(Conf::DIR_INCLUDES . 'htmlfooter.php'); ?>