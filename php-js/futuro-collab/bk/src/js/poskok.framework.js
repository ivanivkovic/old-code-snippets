// Refresh
function refresh( hashOff )
{
	window.location = location.href;
}

// Tajmirani refresh
function timedBack( timeout )
{
	setTimeout(
		function()
		{
			$('body').fadeOut(siteSettings.contentFade, function()
			{
				goBack();
			})
		},
		timeout
	);
}

// Back botuni.
function goBack()
{
	history.go( - hashCount );
}

// Provjera ima li hasParent.
$.fn.hasParent = function(objs)
{
	var objs = $(objs); var found = false;
	
	$(this[0]).parents().andSelf().each(function()
	{
		if ($.inArray(this, objs) != -1)
		{
			found = true;
			return false;
		}
	});
	
	return found;
}

// Konzola zamjena.
if( console === undefined )
{
	console = { log : function(){} };
}

var siteSettings = {
					menuFade : 100,
					widgetFade : 250,
					contentFade : 200,
				};

var hashCount = 1;

$.poskok = {};

$.poskok = {
	
	getObjSettings : function (obj)
	{
		var SettingsString = obj.getAttribute('data-settings');
		var SettingsObj = [];
		
		if( SettingsString == '' || SettingsString == false || SettingsString == null)
		{
			return false;
		}
		else
		{
			SettingsString = SettingsString.split(',');
			
			var len = SettingsString.length;
			
			for ( var i = 0; i < len; i++ )
			{
				SettingsObj[i] = SettingsString[i];
			}
		}
		
		return SettingsObj;
	},
	
	getAjax : function ( url, callback )
	{
		var url = '/ajax.php/' +  url;
		
		$.get(
			url,
			function(data)
			{
				if( typeof callback !== 'undefined' )
				{
					callback(data);
				}
			}
		);
	},
	
	returnAjax : function( url )
	{
		var Result = null;
		var url = '/ajax.php/' +  url;
		
		$.ajax(
		{
			url: url,
		    type: 'get',
		   	dataType: 'html',
		    async: false,
		    success: function(data)
			{
				Result = data;
			}
		});
		
		return Result;
	}
};