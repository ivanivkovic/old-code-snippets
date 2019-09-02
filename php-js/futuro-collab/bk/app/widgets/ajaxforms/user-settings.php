<form class="form" id="user-settings">
	
	<div class="border-bottom"></div>
	<div class="success alert-success alert display-none"><button type="button" class="close" data-dismiss="alert">&times;</button></div>
	<div class="error alert-error alert display-none"><button type="button" class="close" data-dismiss="alert">&times;</button></div>
	
	<span class="pull-left margin-right-5"><?= $txt[4] ?></span>
	
	<? foreach( libTemplate::$langs as $key => $lang ): ?>
		
		<label class="radio pull-left margin-right-5">
			<input type="radio" 
			
			<?
				if( $key === libTemplate::$lang )
				{
					echo 'checked';
				}
			?>
			
			onclick="changeLang(this)"
			name="lang" value="<?= $key ?>"/>
			<?= $lang ?>
		</label>
		
	<? endforeach; ?>
	<br/>
	<hr />
	
	<?= $txt[6] ?>:
	
	<br />
	<br />
	
	<input onkeyup="validatePassword( this )" type="password" class="pull-left margin-right-5" id="old-pass" placeholder="Unesite staru lozinku" />
	<input type="password" class="pull-left margin-right-5 inline" id="new-pass" placeholder="Unesite novu lozinku" />
	<input type="button" class="pull-left btn" id="submit-pass" value="Potvrdi" onclick="submitNewPass()" />
	
	<br/>
	<br/>
</form>

<script>

function showNotification(text)
{
	var Text = $('<span>' + text + '</span>');

	if( $('.success').find('span').length )
	{
		$('.success').find('span').remove();
	}
	
	$('.success').css('display', 'block');
	
	Text.appendTo('.success').delay(1000).fadeOut(300, function()
	{
		$(this).remove();
		$('.success').css('display', 'none');
	});
	
}

function showError(text)
{
	var Text = $('<span>' + text + '</span>');

	if( $('.error').find('span').length )
	{
		$('.error').find('span').remove();
	}
	
	$('.error').css('display', 'block');
	
	Text.appendTo('.error').delay(1000).fadeOut(300, function()
	{
		$(this).remove(); $('.error').css('display', 'none');
	});
}

function submitNewPass()
{
	var oldPassObj = document.getElementById('old-pass');
	var newPassObj = document.getElementById('new-pass');
	
	if( $(oldPassObj).hasClass('valid') )
	{
		if( newPassObj.value.length < 6 )
		{
			showError('Vaša lozinka mora imati najmanje 6 znakova.');
		}
		else
		{
			$.ajax(
			{
				url: '/ajax.php/action/updateMyPass/libuser/',
				type: 'post',
				dataType: 'json',
				async: false,
				data:
				{
					oldpass : oldPassObj.value,
					newpass : newPassObj.value
				},
				success: function(data)
				{
					if( data.error == 0 )
					{
						showNotification('Promjene su spremljene!');
					}
				}
			});
		}
	}
	else
	{
		showError('Unijeli ste netočnu lozinku.');
	}
	
	return false;
}

function changeLang(obj)
{
	$.ajax(
	{
		url: '/ajax.php/action/updateField/userData/',
		type: 'post',
		dataType: 'json',
		async: false,
		data:
		{
			field: 'lang',
			value: obj.value,
			userid: '<?= Core::$user->id ?>'
		},
		success: function(data)
		{
			if( data.error == 0 )
			{
				showNotification('Promjene su spremljene!');
			}
		}
	});
}

function validatePassword( obj, value )
{
	if( obj.value !== '' )
	{
		$.ajax(
		{
			url: '/ajax.php/action/checkMyPassword/libuser/',
			type: 'post',
			dataType: 'json',
			async: false,
			data:
			{
				password: obj.value
			},
			success: function(data)
			{
				console.log(data);
				
				if( data.error == 0 )
				{
					$(obj).addClass('valid');
					$(obj).removeClass('invalid');
				}
				else
				{
					$(obj).addClass('invalid');
					$(obj).removeClass('valid');
				}
			}
		});
	}
	else
	{
		$(obj).removeClass('invalid');
		$(obj).removeClass('valid');
	}
}

</script>