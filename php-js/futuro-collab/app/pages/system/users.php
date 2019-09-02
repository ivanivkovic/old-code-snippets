<? include(Conf::DIR_INCLUDES . 'htmlheader.php'); ?>

<? function loadList( $dataList ) { ?>
	
		<table class="table">
	
			<thead>
				<tr>
					<th>Ime</th>
					<th>Posljednji login</th>
					<th>Telefon</th>
					<th>eKontakti</th>
					<th>Uloga</th>
					<th>Akcije</th>
				</tr>
			</thead>
			<tbody>
			
			<? if( ! empty($dataList) ): ?>
			
				<? foreach( $dataList as $item ): ?>
				
					<tr>
						<td><a target="_blank" href="/user/<?= $item['userid'] ?>"><?= $item['name'] . ' ' . $item['lastname'] ?></a></td>
						<td><?= libTemplate::formatTimeString( $item['lastlogin'] ) ?></td>
						<td><?= $item['phone'] ?></td>
						<td><?= nl2br( $item['einfo'] ) ?></td>
						<td><?= $item['role'] ?></td>
						<td><a href="#" class="btn margin-right-5 edit" data-id="<?= $item['userid'] ?>"><i class="icon-edit"></i> Uredi</a></td>
					</tr>
				
				<? endforeach; ?>
			
			<? else: ?>
			
			<tr><td colspan="3">Nema korisnika ove razine.</td></tr>
			
		<? endif; ?>
		
		</tbody>
	</table>

<?php } ?>

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
			
				<? include(Conf::DIR_INCLUDES . 'toptabs.php'); ?>
				
				<div class="well">
				
					<a class="btn pull-left font-normal margin-right-10" href="<?= Core::$router->back ?>"><i class="icon-arrow-left"></i> <?= $txt['back'] ?></a>	
					<a class="btn pull-left form-toggle" data-id="form-new-user" data-text="Dodaj novog korisnika" data-active="Zatvori formu">
						<i class="icon-plus-sign"></i> Dodaj novog korisnika
					</a>
					
					<div class="clear"></div>
					
					<hr/>
					
					<? include(Conf::DIR_WIDGETS . 'forms/user-new.php'); ?>
					
					<div class="tab-content" data-id="admins">
						<? loadList($adminList) ?>
					</div>
					
					<div class="tab-content" data-id="users">
						<? loadList($userList) ?>
					</div>
					
					<div class="tab-content" data-id="superadmins">
						<? loadList($superAdminList) ?>
					</div>
				</div>
			</div>
		</div>

<?php $footer = '<script src="/src/js/page/system-user.js"></script>'; ?>

<? include(Conf::DIR_INCLUDES . 'htmlfooter.php'); ?>