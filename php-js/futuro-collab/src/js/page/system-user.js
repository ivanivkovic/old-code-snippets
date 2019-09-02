$(document).ready(function()
{
	$('.edit').click(function()
	{
		var obj = $(this);
		var Url = '/ajax.php/gethtml/updateform/userdata/' + obj.attr('data-id');
		
		$.get(
			Url,
			function(data)
			{
				var UpdateForm = data;
				
				$.poskok.popup.Ask(
				{
					title: 'Uređivanje korisnika',
					message: UpdateForm,
					
					onOk: function()
					{
						$('#user-update').submit();
					}
				});
			}
		);
	});
	
});