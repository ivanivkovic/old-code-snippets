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
						<td><a href="#" class="btn btn-mini margin-right-5 edit" data-id="<?= $item['userid'] ?>"><i class="icon-pencil"></i> Uredi</a></td>
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
				
					<a class="button fr form-toggle" data-id="form-new-user" data-text="Dodaj novog korisnika" data-active="Zatvori formu">Dodaj novog korisnika</a>
					
					<? include('/app/widgets/forms/user-new.php'); ?>
					
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