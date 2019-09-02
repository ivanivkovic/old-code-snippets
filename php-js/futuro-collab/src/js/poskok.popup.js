jQuery.poskok.popup = 
{
	Ask : function ( options )
	{
		var Settings = jQuery.extend({
					        onClose : function(){},
							onOk : function(){},
							onLoad : function(){},
							className : false
					   },
					   options
		);
		
		var PopUp = $('<div class="modal hide fade' + ( Settings.className !== false ? Settings.className : '' ) + '">');
		
		var ClosePopup = $('<button type="button" class="close">&times;</button>').click(function(){
			Settings.onClose(); $(this).parents('.modal').modal('hide'); return false;
		});
		
		var PopUpHead = $('<div>').addClass('modal-header').append(ClosePopup).append('<h3>' + Settings.title + '</h3>');
		var PopUpBody = $('<div>').addClass('modal-body').append('<p>' + Settings.message + '</p>');
		var PopUpFooter = $('<div>').addClass('modal-footer');
		
		if( Settings.onOk !== false )
		{
			$(PopUpFooter).append($('<button class="btn pull-right">' + str[4] + '</button>').click(function()
			{
				Settings.onClose(); $(this).parents('.modal').modal('hide'); return false;
			}));
			
			$(PopUpFooter).append($('<button class="btn btn-primary pull-right margin-right-10">' + str[5] + '</button>').click(function()
			{
				Settings.onOk(); $(this).parents('.modal').modal('hide'); return false;
			}));
		}
		else
		{
			$(PopUpFooter).append($('<button class="btn pull-right" data-dismiss="modal" aria-hidden="true">' + str[6] + '</button>').click(function()
			{
				Settings.onClose(); $(this).parents('.modal').modal('hide'); return false;
			}));
		}
		
		$(PopUp).append(PopUpHead);
		$(PopUp).append(PopUpBody);
		$(PopUp).append(PopUpFooter);
		
		PopUpHeader = null;
		PopUpBody 	= null;
		PopUpFooter = null;
		
		$(PopUp).modal({
			backdrop 	: true,
			keyboard 	: true,
			show 		: true
		});
		
		setTimeout(function()
		{
			Settings.onLoad();
			$(PopUp).draggable({ handle : '.modal-header'});
			$('body').trigger('modalLoad');
		}, 
		
		200);
	}
};