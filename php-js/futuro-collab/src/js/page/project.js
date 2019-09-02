$(document).ready(function()
{
	$('.delete').click(function()
	{
		var obj = $(this);
		
		var radio = {
					0 : {name: 'remove', value: 'activate', message: str[13], checked: false},
					1 : {name: 'remove', value: 'archiveundone', message: str[14], checked: false},
					2 : {name: 'remove', value: 'archivedone', message: str[15], checked: false},
					3 : {name: 'remove', value: 'delete', message: str[16], checked: false}
		};
		
		delete radio[ obj.attr('data-status') ];
		
		// Set first radiobox checked.
		for ( oneRadio in radio)
		{
			radio[oneRadio].checked = true;
			
			break;
		}
		
		$.poskok.prompt.Ask(
		{
			title: str[17],
			message: str[18],
			radio: radio,
			
			onOk: function()
			{
				var DeleteUrl = '/ajax.php/action/changestatus/project/' + obj.attr('data-id') + '/' + this.radioChecked;
				
				var radioChecked = this.radioChecked;
				
				$.get(
					DeleteUrl,
					function ( data )
					{
						if( data.error == '0')
						{
							if(  radioChecked == 'delete' )
							{
								window.location = '/project?success=0';
							}
							else
							{
								window.location = location.href;
							}
						}
						else
						{
							jQuery.poskok.prompt.Ask({
								title : 'Pogreška',
								message : data.error,
								onOk: false
							});
						}
					},
					'json'
				);
			}
		});
	});
	
	$('.edit').click(function()
	{
		var obj = $(this);
		var Url = '/ajax.php/gethtml/updateform/project/' + obj.attr('data-id');
		
		$.get(
			Url,
			function(data)
			{
				var UpdateForm = data;
				
				$.poskok.popup.Ask(
				{
					title: str[19],
					message: UpdateForm,
					
					onOk: function()
					{
						$('#project-update').submit();
					},
					onLoad: function()
					{
						loadAutocomplete();
					}
				});
			}
		);
	});
});