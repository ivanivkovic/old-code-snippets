/*
* Učitavanje skripti i neke DOM spike.
*/

function showSuccess( code )
{
	var Obj = $('#success-list');
	var HTML = '<button type="button" class="close" data-dismiss="alert">&times;</button><p class="margin-0">' + str['success'][code] + '</p>';
	
	Obj.append( HTML ).fadeIn(200, 
		function()
		{
			setTimeout(
				function()
				{
					Obj.fadeOut(200, function()
					{
						$(this).empty();
					});
				},
				3000
			);
		}
	);
}

function loadPopup( popup, title, width, onClose, onOk, onLoad)
{
	var Url 	= 'gethtml/' + popup;
	var Width 	= typeof width !== 'undefined' ? width + 'px' : '800px';
	var onClose = typeof onClose !== 'undefined' ? onClose : false;
	var onOk 	= typeof onOk !== 'undefined' ? onOk : false;
	var onLoad 	= typeof onLoad !== 'undefined' ? onLoad : false;
	
	$.poskok.getAjax(
		Url,
		function(data)
		{
			var UpdateForm = data;
			
			jQuery.poskok.popup.Ask(
			{
				title: title,
				message: UpdateForm,
				onOk: onOk !== false ? function(){ eval(onOk) } : function(){},
				onClose : onClose !== false ? function(){ eval(onClose); } : function(){},
				onLoad : onLoad !== false ? function(){ eval(onLoad); } : function(){}
			});
		}
	);
}

// Init.
function init()
{
	$('a:not([href])').attr('href', '#');
	$('a:not([title=""])').tooltip();
	
	$('a[href="#"]').each(function()
	{
		if( $(this).attr('onclick') === '' )
		{
			$(this).attr('onclick', 'return false;');
		}
	});
	
	setTimeout(
		function(){
			$('input').attr('spellcheck', 'false');
		},
		300
	);
}

jQuery(function($)
{
	init();
	
	// Paginator za globalni serach.
	$('.search-results').pagination(
	{
		searchBox : '#global-search input',
		dataType : 'completeSearch',
		updateDiv : '#global-search-pagination-content',
		paginationDiv : '#global-search-pagination',
		keypress : false,
		perPage : 20
	});
	
	// Ako je link hashan, ignoriraj ga za back botun.
	$('a').click(function()
	{
		if( hasHash( $(this).attr('href') ) && $(this).attr('href') !== '#' )
		{
			hashCount++;
		}
	});
	
	$(document).ajaxComplete(function()
	{
		init();
	});
	
	// ----- Top menu script.
	$('.header-menu a').mouseenter(function()
	{
		$(this).parent().find('ul').fadeIn(siteSettings.menuFade);
	});

	$('.header-menu').mouseleave(function()
	{
		$(this).parent().find('ul').fadeOut(siteSettings.menuFade);
	});
	
	$('#active-projects-toggle').click(function()
	{
		if( $('.active-project').css('display') == 'none' )
		{
			$('.active-project').slideDown(siteSettings.menuFade);
		}
		else
		{
			$('.active-project').slideUp(siteSettings.menuFade);
		}
	});
	
	// ---- Tab content
	if( $('.tab-content-toggle').length )
	{
		$('.tab-content').css('display', 'none');
		
		var activeId = $('.tab-content-toggle.active').attr('data-id');
		$('.tab-content[data-id="' + activeId + '"]').css('display', 'block');
		
		$('.tab-content-toggle').click(function()
		{
			$('.tab-content-toggle').parent().find('.active').removeClass('active');
			$(this).addClass('active');
			
			var thisId = this.getAttribute('data-id');
			
			$('.tab-content').css('display', 'none');
			$('.tab-content[data-id="' + thisId + '"]').css('display', 'block');
		});
		
		var curHash = getHash();
		
		if( curHash !== '')
		{
			$('.tab-content-toggle[data-id="' + curHash + '"]').click();
		}
		
		if( $('.tab-content-toggle' ).length )
		{
			$('body').bind('keydown', function(e)
			{
				if( !$( document.activeElement ).is('input') && !$( document.activeElement ).is('textarea') )
				{
					if( e.keyCode == 38 ){ e.preventDefault(); $('.tab-content-toggle.active').prev().click(); }
					if( e.keyCode == 40 ){ e.preventDefault(); $('.tab-content-toggle.active').next().click(); }
				}
			});
		}
	}
	
	// ---- Form script.
	$('.form-toggle').click(function()
	{
		var ID = this.getAttribute('data-id');
		$obj = $('#' + ID);
		
		if($obj.css('display') === 'none')
		{
			$obj.fadeIn(siteSettings.widgetFade).delay(siteSettings.widgetFade).removeClass('hidden');
			
			var textIndex = $obj.find('textarea:first').index();
			var inputIndex = $obj.find('input:first').index();
			
			if( textIndex > inputIndex || textIndex === -1 )
			{
				$obj.find('input:first').focus();
			}
			
			if( textIndex === -1 || textIndex < inputIndex)
			{
				$obj.find('textarea:first').focus();
			}
			
			this.innerHTML = this.getAttribute('data-active');
		}
		else
		{
			$obj.fadeOut(siteSettings.widgetFade).delay(siteSettings.widgetFade);
			this.innerHTML = this.getAttribute('data-text');
		}
	});
	
	// Success layout box za uspjele akcije.
	setTimeout(function()
	{
		$('#success-list').fadeOut(siteSettings.widgetFade, function(){
			$(this).empty();
		});
	},
	3000);
	
	// Access details.
	$('.popup-trigger').click(function()
	{
		var Text = $(this).text();
		var Popup = $(this).attr('data-id');
		var Width = $(this).attr('data-width');
		var OnClose = $(this).attr('data-onclose');
		var OnOk = $(this).attr('data-onok');
		var OnLoad = $(this).attr('data-onload');
		
		if( ! $('.popup:contains(' + $(this).text() + ')').length )
		{
			if( $(this).hasParent('.header-menu') )
			{
				$(this).parent().parent().fadeOut(siteSettings.menuFade, function()
				{
					loadPopup( Popup, Text, Width, OnClose, OnOk, OnLoad );
				});
			}
			else
			{
				loadPopup( Popup, Text, Width, OnClose, OnOk, OnLoad );
			}
		}
	});
	
	// GLOBAL BUTTON FUNCTIONS
	$('body').keydown(function(e)
	{
		if( e.keyCode == 27 )
		{
			if( $( document.activeElement ).is('input') || $( document.activeElement ).is('textarea') )
			{
				// Is input, allow propagation.
			}
			else
			{
				if( $('.esc-exit:last').hasClass( 'popup') )
				{
					if( $('.esc-exit:last a[href="#close"]').length )
					{
						$('.esc-exit:last a[href="#close"]').click();
					}
					else
					{
						if( $('.esc-exit:last a[href="#ok"]').length )
						{
							$('.esc-exit:last a[href="#ok"]').click();
						}
					}
				}
				else
				{
					if( $('.esc-exit:last').length )
					{
						$('.esc-exit:last').fadeOut(siteSettings.widgetFade, function() { $(this).remove(); } );
					}
				}
			}
		}
	});
	
	$('input, textarea').on('keypress keydown', function(e)
	{
		e.stopPropagation();
		
		if( e.keyCode == 27 )
		{
			if( $(this).val() == '' )
			{
				if( $(this).hasParent('.form') )
				{
					$('.form-toggle[data-id="' + $(this.form).attr('id') + '"]').click();
					$('body').focus();
				}
				
				if( $(this).hasParent('.popup') )
				{
					$('.popup').fadeOut(siteSettings.widgetFade, function() { $(this).remove(); } );
				}
				
				if( ! $(this).hasParent('.popup') && ! $(this).hasParent('.form') )
				{
					this.blur();
				}
			}
			else
			{
				$(this).val(''); this.value = '';
			}
		}
	});
	
	$('#global-search').on('keyup', 'input', function(e)
	{
		if( this.value !== '' )
		{
			$('.search-results .lead').html( str[0] + '<strong>"' + this.value + '"</strong>:');
			$('.search-results').fadeIn(siteSettings.widgetFade);
		}
		else
		{
			$('.search-results').fadeOut(siteSettings.widgetFade);
		}
	});
	
	$('.show').on('click', function()
	{
		$('.display-toggle[data-id="' + $(this).attr('data-id') + '"]').fadeIn(siteSettings.widgetFade);
	});
	
	$('select').on('DOMMouseScroll mousewheel', function( e )
	{
		var evt = e.originalEvent;
		var direction = evt.detail ? evt.detail * (-120) : evt.wheelDelta;
		
		if( direction > 0)
		{
			$(this).find('option:selected').next().attr('selected', true);
		}
		else
		{
			$(this).find('option:selected').prev().attr('selected', true);
		}
		
		$(this).change();

		evt.preventDefault();
	});
});
