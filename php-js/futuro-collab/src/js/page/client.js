function deleteClient( obj, list )
{
	var radio = $(this).attr('data-hasprojects') == 'true' ? {
				0 : {name: 'archive', value: 'keep', message: str[7], checked: true},
				1 : {name: 'archive', value: 'archivedone', message: str[8], checked: false},
				2 : {name: 'archive', value: 'archiveundone', message: str[9], checked: false}
		} : false;
	
	$.poskok.prompt.Ask(
	{
		title: str[10],
		message: str[11],
		radio: radio,
		
		onOk: function()
		{
			var DeleteUrl = '/ajax.php/action/delete/client/' + obj.attr('data-id') + '/' + this.radioChecked;
			
			$.get(
				DeleteUrl,
				function ( data )
				{
					if( data.error == '0' )
					{
						if( typeof list !== 'undefined' && list === true)
						{
							showSuccess(17);
							
							obj.parent().parent().fadeOut(siteSettings.widgetFade, function()
							{
								obj.parent().parent().remove();
							});
						}
						
						return true;
					}
					else
					{
						console.log( data.error );
						return false;
					}
				}, 'json'
			);
		}
	});
}

function editClient( obj )
{
	$.get(
		'/ajax.php/gethtml/updateform/client/' + obj.attr('data-id'),
		function(data)
		{
			var UpdateForm = data;
			
			jQuery.poskok.popup.Ask(
			{
				title: str[12],
				message: UpdateForm,
				
				onOk: function()
				{
					$('#client-update').submit();
				}
			});
		}
	);
}

$(document).ready(function()
{
	$('#pagination-content').on('click', '.delete', function()
	{
		deleteClient( $(this), true );
	});
	
	$('#pagination-content').on('click', '.edit', function()
	{
		editClient( $(this) );
	});
	
	$('.delete').click(function()
	{
		if( deleteClient( $(this) ) )
		{
			loadPage('/task?success=17');
		}
	});
	
	$('.edit').click(function()
	{
		editClient( $(this) );
	});
});
