function removeTag( obj ) // Napravljen za tagove (autocomplete). Briše iz input type hidden-a vrijednosti.
{
	var inputObj = $(obj).parent().parent().find('.typehead-value');
	inputObj.val( inputObj.val().replace( $(obj).find('a').text() + ',', '' ) );
	
	$(obj).fadeOut(siteSettings.contentFade, function()
	{
		$(obj).remove();
	});
}

function loadAutocomplete()
{
	$('.typehead').each(function()
	{
		var source = $(this).attr('data-source').split(',');
		var Obj = $(this);
		
		Obj.typeahead(
	    {
    		source : source,
    		
			updater: function(item)
			{
	    		var source = this.source;
				var valueObj = Obj.parent().find('.typehead-value');

	    		for( var i = 0; i < source.length; i++)
	    		{
					if( source[i] === item )
					{
						source.splice(i, 1);
						valueObj.val( valueObj.val() + item + ',' );
					}
	    		}
	    	
	    		var button = $('<a href="#">' + item + '</a>').click(function()
	    		{
					$(this).parent().fadeOut(siteSettings.contentFade, function(){
						$(this).remove();
					});
					
					source.push( item );
					valueObj.val( valueObj.val().replace( item + ',', '' ) );
					
					return false;
	    		});
	    	
				Obj.parent().find('.typehead-container').append( $('<li>').append( button ) );
	        },
	        
	        sorter : function( matches )
	        {
	        	var len = matches.length;
	        	
	        	Obj.parent().find('.typehead-container li a').each(function()
	        	{
					for( var i = 0; i < len; i++)
	        		{
						if( matches[i] == $(this).text() )
						{
							matches.splice(i, 1);
							
						}
	        		}
	        	});
	        	
	        	return matches;
	        }
	    });
	});
}

function loadDatepicker( selector )
{
	$(selector).each(function()
	{
		$(this).datepicker({ language: SITE_SETTINGS.lang, format: "dd. MM yyyy." })
			.on("changeDate", function(ev){
				$(selector).datepicker("hide");
			}
		);
	});
}

function bindInputTypeFileEvents()
{
	/// INPUT TYPE FILE!
	$('.form .input-file').on('click', 'button', function(e)
	{
		e.preventDefault();
		var $parent = $(this).parent();
		
		$parent.find('input').click();
	});
	
	$('.input-file').on('change', 'input', function()
	{
		var $domobj = $(this);
		
		if(navigator.appName === 'Microsoft Internet Explorer') // IE doesn't support multiple files.
		{
			var count = 1;
		}
		else
		{
			var count = $domobj[0].files.length;
		}

		$(this).parent().find('span').text(count + str[1]);
	});
}

jQuery(function($)
{
	$('body').on('modalLoad', function()
	{
		if( $('.modal:last').find('.form .input-file').length )
		{
			bindInputTypeFileEvents();
		}
	})
	
	bindInputTypeFileEvents();
});