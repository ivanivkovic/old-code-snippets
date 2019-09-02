jQuery.poskok.prompt = 
{
	Ask : function ( options )
	{
		var Settings = jQuery.extend({
					        onClose : function(){},
							onOk : function(){},
							checkbox: false, // Array! [ 0 : { name, message } ]
							radio: false,
							radioChecked: false,
							className : false
					   },
					   options
		);
	
		var PopUp = $('<div style="display: none;" class="modal hide fade ' + ( Settings.className !== false ? Settings.className : '' ) + '">');
		var ClosePopup = $('<button type="button" class="close">&times;</button>').click(function(){
			Settings.onClose(); $(this).parents('.modal').modal('hide'); return false;
		});
		
		var PopUpHead = $('<div>').addClass('modal-header').append(ClosePopup).append('<h3>' + Settings.title + '</h3>');
		var PopUpBody = $('<div>').addClass('modal-body').append(Settings.message);
		
		// RADIO
		if( Settings.checkbox !== false )
		{
			var CheckBox = $('<div class="popup-checkbox"><label class="checkbox"><input type="checkbox" name="' + Settings.checkbox.name + '" ' + ( Settings.checkbox.checked === true ? 'checked="checked"' : '' ) + '/></label><span class="popup-message">' + Settings.checkbox.message + '</span></div>');
			
			CheckBox.find('.popup-message').click(function()
			{
				if ( CheckBox.find('input').prop('checked') !== true )
				{
					CheckBox.find('input').prop('checked', true);
					Settings.checkbox.checked = true;
				}
				else
				{
					CheckBox.find('input').prop('checked', false);
					Settings.checkbox.checked = false;
				}
			});
			
			CheckBox.appendTo( PopUpBody );
		}
		
		// CHECKBOX
		
		if( Settings.radio !== false )
		{
			var RadioBox = $('<div class="popup-radiobox"></div>');
			
			for( RadioButton in Settings.radio )
			{
				if( Settings.radio[RadioButton].checked === true )
				{
					Settings.radioChecked = Settings.radio[RadioButton].value;
				}
				
				var Radio = $('<label class="radio"><input value="' + Settings.radio[RadioButton].value + '" type="radio" name="' + Settings.radio[RadioButton].name + '" ' + ( Settings.radio[RadioButton].checked === true ? 'checked="checked"' : '' ) + '/>' + Settings.radio[RadioButton].message + '</label>');
				
				Radio.click(function()
				{
					for ( var i = 0; i < Object.keys(Settings.radio).length; i++ )
					{
						if( Settings.radio[i] )
						{
							Settings.radio[i].checked = false;
						}
					}
					
					Settings.radioChecked = $(this).val();
				});
				
				Radio.appendTo( RadioBox );
			}
			
			RadioBox.appendTo( PopUpBody );
		}
		
		var PopUpFooter = $('<div class="modal-footer">');
		
		if( Settings.onOk !== false )
		{
			$(PopUpFooter).append($('<a href="#close" class="btn pull-right">' + str[4] + '<span></span></a>').click(function()
			{
				Settings.onClose(); $(this).parents('.modal').modal('hide'); return false;
			}));
			
			$(PopUpFooter).append($('<a href="#ok" class="btn btn-primary pull-right margin-right-10">' + str[5] + '<span></span></a>').click(function()
			{
				Settings.onOk(); $(this).parents('.modal').modal('hide'); return false;
			}));
		}
		else
		{
			$(PopUpFooter).append($('<a href="#ok" class="btn pull-right">' + str[6] + '<span></span></a>').click(function()
			{
				Settings.onClose(); $(this).parents('.modal').modal('hide'); return false;
			}));
		}
		
		$(PopUp).append(PopUpHead);
		$(PopUp).append(PopUpBody);
		$(PopUp).append(PopUpFooter);
		
		PopUpBody = null;
		PopUpHead = null;
		PopUpFooter = null;
		
		$(PopUp).modal(
		{
			backdrop 	: true,
			keyboard 	: true,
			show 		: true
		});
		
		setTimeout(function()
		{
			$(PopUp).draggable({ handle : '.modal-header'});
			$('body').trigger('modalLoad');
		}, 
		
		200);
	}
};