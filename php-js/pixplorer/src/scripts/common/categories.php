$(document).ready(function(){		
	
	$('.category').click(
		function(){
			loadAjaxPopup(600, 300, 'search_criteria', '', $(this).attr('data-id'));
		}
	);	
	
	$('#background').click(function(){
		closePopups();
	});
	
});