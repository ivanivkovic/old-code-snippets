$(document).ready(function()
{
	$('.delete').live('click', function()
	{
		var obj = $(this);
		
		var radio = $(this).attr('data-hasprojects') == 'true' ? {
					0 : {name: 'archive', value: 'keep', message: 'Zadrži vezane projekte kakvi jesu', checked: true},
					1 : {name: 'archive', value: 'archivedone', message: 'Spremi vezane projekte u arhivu dovršenih projekata', checked: false},
					2 : {name: 'archive', value: 'archiveundone', message: 'Spremi vezane projekte u arhivu nedovršenih projekata', checked: false}
			} : false;
		
		$.poskok.prompt.Ask(
		{
			title: 'Brisanje klijenta',
			message: 'Jeste li sigurni da želite obrisati klijenta?',
			radio: radio,
			
			onOk: function()
			{
				var DeleteUrl = '/ajax.php/action/delete/' + obj.attr('data-item') + '/' + obj.attr('data-id') + '/' + this.radioChecked;
				
				$.get(
					DeleteUrl,
					function ( data )
					{
						if( data.error == '0' )
						{
							obj.parent().parent().fadeOut(siteSettings.widgetFade, function()
							{
								obj.parent().parent().remove();
							});
						}
						else
						{
							console.log( data.error );
						}
					}, 'json'
				);
			}
		});
	});
	
	
	$('.edit').live('click', function()
	{
		var obj = $(this);
		
		$.get(
			'/ajax.php/gethtml/updateform/client/' + obj.attr('data-id'),
			function(data)
			{
				var UpdateForm = data;
				
				jQuery.poskok.popup.Ask(
				{
					title: 'Uređivanje klijenta',
					message: UpdateForm,
					
					onOk: function()
					{
						$('#client-update').submit();
					}
				});
				
			}
		);
	});
});
