function FuturoSite( settings )
{	
	this.settings = settings;
	this.strings = [];
	
	this.init = function()
	{
		if( this.checkSettings() )
		{
			this.getStrings();
		}
	};
	
	this.checkSettings = function()
	{
		if( typeof this.settings.lang === 'undefined' )
		{
			console.log('Futuro error: Jezik nije definiran!');
			return false;
		}
		
		return true;
	}
	
	this.getStrings = function ()
	{
		var str = [];
		
		$.ajax({
			url: '/inc/lang/js_' + settings['lang'] + '.php',
			success: function(data)
			{
				str = data;
			},
			dataType: 'json',
			async : false
		});
		
		this.strings = str;
	};
	
	this.str = function( code )
	{
		return typeof strings[code] !== 'undefined' ? strings[code] : '';
	};
	
	this.init();
};

$(document).ready(function()
{
	var fsite = new FuturoSite( SITE_SETTINGS );
});