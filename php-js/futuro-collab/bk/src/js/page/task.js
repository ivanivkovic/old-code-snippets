function deleteTask( obj )
{
	var obj = $(obj);
	
	$.poskok.prompt.Ask(
	{
		title: 'Brisanje zadatka',
		message: 'Jeste li sigurni da želite obrisati zadatak i sve stavke?',
		
		onOk: function()
		{
			var DeleteUrl = '/ajax.php/action/delete/task/' + obj.attr('data-id');
			
			$.get
			(
				DeleteUrl,
				function ( data )
				{
					if( data.error == '0' )
					{
						obj.parent().parent().fadeOut(siteSettings.widgetFade, function()
						{
							obj.parent().parent().remove();
							
							updateTaskManager( getHashObj() );
						});
					}
					else
					{
						console.log( data.error );
					}
				},
				'json'
			);
		}
	});
}

function editTask( obj )
{
	var obj = $(obj);

	$.get(
		'/ajax.php/gethtml/updateform/task/' + obj.attr('data-id'),
		function(data)
		{
			var UpdateForm = data;
			
			jQuery.poskok.popup.Ask(
			{
				title: 'Uređivanje zadatka',
				message: UpdateForm,
				onOk: function()
				{
					$('#task-update').submit();
				},
				onLoad : function()
				{
					loadAutocomplete();
					loadDatepicker('.datep');
				}
			});
		}
	);
}

function initTaskManager( hashObj )
{
	updateFilter();
	runPagination( hashObj );
}

function updateTaskManager( hashObj )
{
	runPagination( hashObj );
}

// Pokreni paginator.
function runPagination( hashObj )
{
	var startPage = typeof hashObj.page !== 'undefined' ? hashObj.page - 1 : 0;
	
	$("#paginator").pagination(
	{
		search : false,
		dataType : "task",
		pagesPerView : 12,
		filters : hashObj,
		perPage : 10,
		urlChange : true,
		startPage : startPage
	});
}

// Updatea sve hash promjene unutar html-a za filtere kad nanovo učitamo stranicu s javascript hashom.
function updateFilter()
{
	if( getHash() !== '')
	{
		var hashObj = getHashObj();
		
		if( typeof hashObj.projectid !== 'undefined' ) { $('#filter-project').val( hashObj.projectid );}
		if( typeof hashObj.userid !== 'undefined' ) { $('#filter-user').val( hashObj.userid ); }
		if( typeof hashObj.priority !== 'undefined' ) { $('#filter-priority').val( hashObj.priority ); }
		if( typeof hashObj.clientid !== 'undefined' ) { $('#filter-client').val( hashObj.clientid ); }
		if( typeof hashObj.status !== 'undefined' && hashObj.status == '1' ) { $('#filter-status').click(); }
		if( typeof hashObj.expired !== 'undefined' && hashObj.expired == '1' ) { $('#filter-expired').click(); }
	}
}

$(document).ready(function()
{
	// Pokreni i postavi binde.
	var hashObj = getHashObj();
	
	initTaskManager( hashObj );

	$('body').bind('hashchange', function( e, hashObj )
	{
		hashCount++;
		
		if( PreviousHashProperty !== 'page')
		{
			updateTaskManager( hashObj );
		}
	});
	
	$('#filter-expired').change(function()
	{
		setHash('expired', ( $(this).is(':checked') ? 1 : '' ) );
	});
	
	$('#filter-status').change(function()
	{
		setHash('status', ( $(this).is(':checked') ? 1 : '' ) );
	});
	
	$("#datepicker").datepicker({ language: "' . $lang . '", format: "dd. MM yyyy." })
		.on("changeDate", function(ev){
			$("#datepicker").datepicker("hide");
		}
	);
	
	$('.task .priority a').live('click', function()
	{
		$('#filter-priority option[value="' + $(this).attr('data-value') + '"]').attr('selected', true);
		$('#filter-priority').change();
	});
});
