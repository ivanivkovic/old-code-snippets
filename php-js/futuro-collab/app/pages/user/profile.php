<? include(Conf::DIR_INCLUDES . 'htmlheader.php'); ?>
	</head>
	<body>
		<? include(Conf::DIR_INCLUDES . 'topheader.php'); ?>
		
		<div class="row-fluid">
			
			<div class="span3">
				
				<div class="well">
				
					<h3><?= $userData['name'] ?> <?= $userData['lastname']?></h3>
					<h4><?= $userData['level-title'] ?></h4>
					<p><?= $userData['role'] ?></p>
					<p>Posljednji login : <em><?= $userData['lastlogin'] !== '0' ? self::getFullDateTime( $userData['lastlogin']) : 'Nikad'  ?></em></p>
					<p><a href="/task#userid=<?= $userData['userid'] ?>;status=0;">Obavljenih zadataka : <?= $userData['done-tasks'] ?></a></p>
				
				</div>
				
			</div>
			
			<div class="span9">
				
				<? include(Conf::DIR_INCLUDES . 'toptabs.php'); ?>
				
				<div class="well">
					<div class="tab-content" data-id="profile">
						Korisnički profil.
					</div>
					
					<div class="tab-content" data-id="activity">
					
						<div id="news-container">
							<div id="pagination-content-activity"></div>
							<div class="border-bottom"></div>
							<div class="pagination">
								<ul id="pagination-activity">
								</ul>
							</div>
						</div>
					
					</div>
					
					<div class="tab-content" data-id="task">
						Task Content.
					</div>
				</div>
			</div>
		</div>

<?

$footer = '
<script>

$(document).ready(function()
{
	$("#pagination-activity").pagination(
	{
		updateDiv : "#pagination-content-activity",
		paginationDiv : "#pagination-activity",
		dataType : "libSystemNews",
		filters : [ { name : "userid", value : ' . $userData['userid'] . ' } ],
		perPage : 15
	});
});

</script>
';

include(Conf::DIR_INCLUDES . 'htmlfooter.php');

?>