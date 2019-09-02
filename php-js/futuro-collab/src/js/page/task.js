function deleteTaskSingle( taskid )
{
	$.poskok.prompt.Ask(
	{
		title: str[20],
		message: str[21],
		
		onOk: function()
		{
			var DeleteUrl = '/ajax.php/action/delete/task/' + taskid;
			
			$.get
			(
				DeleteUrl,
				function ( data )
				{
					if( data.error == '0' )
					{
						loadPage('/task?success=16');
					}
					else
					{
						$.poskok.popup.Ask({
							'title' : str[22],
							'message' : str[23]
						});
					}
				},
				'json'
			);
		}
	});
}

function deleteSubTask( obj )
{
	$.poskok.prompt.Ask(
	{
		title: 'Brisanje podzadatka',
		message: 'Jeste li sigurni da želite izbrisati podzadatak?',
		
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
						showSuccess( 16 );
						
						obj.parent().fadeOut(siteSettings.widgetFade, function()
						{
							obj.parent().remove();
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

function editTaskSingle( taskid )
{
	$.get(
		'/ajax.php/gethtml/updateform/task/' + taskid,
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


function deleteTask( obj )
{
	var obj = $(obj);
	
	$.poskok.prompt.Ask(
	{
		title: str[24],
		message: str[25],
		
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
						showSuccess( 16 );
						
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

function createNewSubTask( taskId )
{
	$.get(
		'/ajax.php/gethtml/newSubTaskForm/task/' + taskId,
		function(data)
		{
			var UpdateForm = data;
			
			jQuery.poskok.popup.Ask(
			{
				title: 'Stvaranje podzadataka',
				message: UpdateForm,
				onOk: function()
				{
					var data = 
					{
						taskid : taskId,
						description : $('#newsubtask-description').val()
					};
					
					$.ajax(
					{
						type : 'post',
						async : 'false',
						url: '/ajax.php/action/postNewSubTask/task/' + taskId,
						success: function(data)
						{
							$('#no-subtasks').remove();
							$('.sub-tasks-container').prepend( data.html );
						},
						data : data,
						dataType : 'json'
					}
					);
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
	
	$('.task .priority').on('click', 'a', function()
	{
		$('#filter-priority').val( $(this).attr('data-value') );
		
		setTimeout(
			function()
			{
				$('#filter-priority').change();
			},
			100
		);
	});
	
	$('.update-status').click(function()
	{
		$.get(
			'/ajax.php/gethtml/statusForm/task/' + $(this).attr('data-id'),
			function(data)
			{
				var UpdateStatusForm = data;
				
				$.poskok.popup.Ask(
				{
					title : 'Dovrši zadatak',
					message : UpdateStatusForm,
					onOk : function(){ $('#task-status-update').submit(); },
					onLoad : function(){ loadDatepicker('.datep'); }
				});
			}
		);
	});
	
	$('.sub-tasks-container').on('click', '.delete-subtask',
		function(e){
			e.preventDefault();
			
			deleteSubTask( $(this) );
			
			return false;
		}
	);
});
