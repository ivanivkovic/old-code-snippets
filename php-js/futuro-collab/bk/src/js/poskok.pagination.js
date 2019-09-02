(function( $ )
{
	$.fn.pagination = function( options )
	{
		var settings = $.extend(
		{
		    perPage 		: 15,
		    pagesPerView	: 13,
		    startPage		: 0,								// Može se odrediti da je recimo početna upaljena stranica osma.
		    baseUrl 		: '/ajax.php/',
			updateStyle 	: 'pagination',    					// Paginacija ili show-more ( show-more još nije napravljeno )
			search 			: false,							
			updateDiv 		: '#pagination-content',			// defaultni elementi
			searchBox 		: '#pagination-search',				
			paginationDiv	: '#pagination',					
			dataType 		: '',								// Mora biti postavljen u init-u.
			keypress		: true,								// Strelice lijevo - desno. Nije poželjno kad je više paginator-a
			mousewheel		: true,
			filters			: [],
			resultsCount	: true,
			debug			: false,
			urlChange		: false
		},
			options
	    );
		
		var currentPagination = {};
		
		// Dohvati ključnu riječ.
		function getKeyword()
		{
			if( $( settings.searchBox ).length )
			{
				if( $( settings.searchBox ).val() == '' )
				{
					return 0;
				}
				else
				{
					return $( settings.searchBox ).val();
				}
			}
			else
			{
				return 0;
			}
		}
		
		// Dohvati broj rezultata i broj stranica.
		function getPages( dataType, limit,  keyword )
		{
			var Result = null;
			var Url = settings.baseUrl + 'action/getNumPages/' + dataType;
			
			if( settings.debug === true )
			{
				$.ajax({
					url: Url,
				    type: 'post',
				    async: true,
				    data:
				    {
						limit : limit,
						keyword : keyword,
						filters : settings.filters
				    },
				    success: function(data)
					{
						console.log( "Pagination data available : " + data);
					}
				});
			}
			
			$.ajax({
				url: Url,
			    type: 'post',
		   		dataType: 'json',
			    async: false,
			    data:
			    {
					limit : limit,
					keyword : keyword,
					filters : settings.filters
			    },
			    success: function(data)
				{
					Result = data;
				}
			});
			
			return Result;
		};
		
		// Učitaj stranicu sa setom rezultata.
		function loadPage( dataType, limit, page, keyword )
		{
			$.ajax(
			{
			  	type: "POST",
			 	url: settings.baseUrl + 'gethtml/getPage/' + dataType,
				data:
				{
					limit : limit,
					page : page,
					keyword : keyword,
					filters : settings.filters
				},
				success: function(data)
				{
					if( settings.debug === true ){ console.log( "Pagination data : " + data ); }
					$(settings.updateDiv).html(data);
				}
			});
		};
		
		function getCurrentPageNumber()
		{
			return $(settings.paginationDiv + ' .active').attr('data-page');
		}
		
		// Vraća indexe najvećeg i najmanjeg broja u stacku.
		function getStack()
		{
			return [$( numberButton + ':first').index(), $(settings.paginationDiv + ' .pagination-number:not(.first, .last, .next, .previous, .next-stack):last').index()];
		}
		
		// Koristi se za 3 točkice kad ima više reulzata u stacku.
		function refreshDots()
		{
			if( $( numberButton + ':first').attr('data-page') > 0 )
			{
				if( ! $(settings.paginationDiv + ' .prev-stack').length )
				{
					$(settings.paginationDiv + ' .previous').after('<li class="pagination-number prev-stack" data-page="prev-stack"><a>...</a></li>');
				}
			}
			else
			{
				if( $(settings.paginationDiv + ' .prev-stack').length )
				{
					$(settings.paginationDiv + ' .prev-stack').remove();
				}
			}
			
			if( $( numberButton + ':last').attr('data-page') != (currentPagination.pages - 1) )
			{
				if( ! $(settings.paginationDiv + ' .next-stack').length )
				{
					$(settings.paginationDiv + ' .next').before('<li class="pagination-number next-stack" data-page="next-stack"><a>...</a></li>');
				}
			}
			else
			{
				if( $(settings.paginationDiv + ' .next-stack').length )
				{
					$(settings.paginationDiv + ' .next-stack').remove();
				}
			}
		}
		
		// Učitaj novi stack.
		function fixStack(number)
		{
			if( currentPagination.pages > settings.pagesPerView )
			{
				var Stack = getStack();
				var middle = Math.ceil( ( Stack[1] - Stack[0] ) / 2 );
				
				// Procesiraj raspon promjene za stack.
				var range = 0;
				
				if( number > middle )
				{
					range = parseInt( number - middle );
					
					for( var i = 0; i < range; i++ )
					{
						var lastElem = parseInt(  $(numberButton + ':last').attr('data-page') );
						
						if( currentPagination.pages > lastElem + 1 )
						{
							$( numberButton + ':first').remove();
							$( numberButton + ':last').after( '<li class="pagination-number" data-page="' + ( lastElem + 1 ) + '"><a>' + ( lastElem + 2 ) +  '</a></li>' );
						}
					}
				}
				
				if( number < middle )
				{
					range = parseInt( middle - number );
				
					for( var i = 0; i < range; i++ )
					{
						var firstElem = parseInt( $(numberButton + ':first').attr('data-page') );
						
						if( firstElem > 0 )
						{
							$( numberButton + ':last').remove();
							$( numberButton + ':first').before( '<li class="pagination-number" data-page="' + ( firstElem - 1 ) + '"><a>' + ( firstElem ) +  '</a></li>' );
						}
					}
				}
				
				refreshDots();
				loadBind();
			}
		}
		
		// Otvori novu stranicu.
		function selectPage(number)
		{
			$(settings.paginationDiv + ' .active').removeClass('active');
			$( numberButton + '[data-page="' + number + '"]').addClass('active');
			
			if( settings.urlChange === true )
			{
				setHash('page', number + 1);
			}
			
			loadPage( settings.dataType, settings.perPage, getCurrentPageNumber(), getKeyword());
		}
		
		// Ukloni paginator.
		function removePagination()
		{
			$(settings.paginationDiv + ' .pagination-number').unbind('click');
			$(settings.updateDiv).unbind('DOMMouseScroll mousewheel');
			
			if( settings.keypress === true )
			{
				$('body').unbind('keyup');
			}
			
			$(settings.paginationDiv + ' *').remove();
			if( settings.resultsCount ){ $(settings.paginationDiv).next('p').remove(); }
		}
		
		// Ukloni sadržaj stranice.
		function removeContent()
		{
			$(settings.updateDiv + ' *').remove();
		}
		
		// Učitavanje bind-a za elemente.
		function loadBind()
		{
			$(settings.paginationDiv + ' .pagination-number').unbind('click');
			
			$(settings.paginationDiv + ' .pagination-number').bind('click', function()
			{
				var data = $(this).attr('data-page');
				var number = 0;
				
				switch( data )
				{
					default: number = parseInt(data); break;
					case '+1': number = parseInt( getCurrentPageNumber() ) + 1; break;
					case '-1': number = parseInt( getCurrentPageNumber() ) - 1; break;
					case 'last': number = parseInt( $(numberButton + ':last').attr('data-page') ); break;
					case 'first': number = parseInt( $(numberButton + ':first').attr('data-page') ); break;
				}
				
				if( number < currentPagination.pages && number > -1 && number != getCurrentPageNumber())
				{
					fixStack( $(settings.paginationDiv + ' .pagination-number[data-page="' + number + '"]').index('.pagination-number:not(.first, .last, .next, .previous, .next-stack, .prev-stack)') );
					selectPage( number );
				}
			});
		}
		
		// Generiraj paginator.
		function generatePagination( pages )
		{
			var PaginationHtml = '<li class="pagination-number first" data-page="first"><a><<</a></li><li class="pagination-number previous" data-page="-1"><a><</a></li>';
			currentPagination.pages = pages['numpages'];
			
			// Kreiraj početni stack i zabilježi količinu stranica.
			for ( var i = currentPagination.startPage; i < currentPagination.pages; i++ )
			{
				if( i < settings.pagesPerView )
				{
					PaginationHtml += '<li class="pagination-number" data-page="' + i + '"><a>' + (i + 1) + '</a></li>';
				}
			}
			
			PaginationHtml += '<li class="pagination-number next" data-page="+1"><a>></a></li><li class="pagination-number last" data-page="last"><a>>></a></li>';
			$(settings.paginationDiv).append(PaginationHtml);
			
			if( settings.resultsCount )
			{
				$(settings.paginationDiv).parent().append('<p class="muted"><small>' + pages['numresults'] + ' rezultata pronađeno.</small></p>');
			}
			
			loadBind();
			
			if( settings.keypress === true )
			{
				$('body').bind('keyup', function(e)
				{
					if( ! $( document.activeElement ).is('input') && !$( document.activeElement ).is('textarea') )
					{
						if( e.keyCode == '39' )
						{
							$(settings.paginationDiv + ' .next').click();
						}
						
						if( e.keyCode == '37' )
						{
							$(settings.paginationDiv + ' .previous').click();
						}
					}
				});
			}
			
			// Mousewheel navigacija.
			if( settings.mousewheel === true )
			{
				$(settings.updateDiv).bind('DOMMouseScroll mousewheel', function( e )
				{
					var evt = e.originalEvent;
					var direction = evt.detail ? evt.detail * (-120) : evt.wheelDelta;
					
					if( direction > 0)
					{
						$(settings.paginationDiv + ' .previous').click();
					}
					else
					{
						$(settings.paginationDiv + ' .next').click();
					}
				
					evt.preventDefault();
				});
			}
		};
		
		// Uništi i pokreni paginator.
		function Run()
		{
			destroy();
			init();
		}
		
		function destroy()
		{
			removePagination(); removeContent(); $(settings.searchBox).unbind('keyup');
		}
		
		// Započinjanje cijele funkcionalnosti.
		function init()
		{
			currentPagination.startPage = 0;
			var pages = getPages( settings.dataType, settings.perPage, getKeyword() ) ;
	
			if( pages['numpages'] > 0 )
			{
				if( settings.updateStyle === 'pagination' )
				{
					generatePagination( pages );
				}
				
				$(settings.paginationDiv + ' .pagination-number:eq(' + ( settings.startPage + 2 ) + ')').click();
			}
			else
			{
				$(settings.updateDiv).html('<td colspan="100%">Trenutno nema rezultata koji odgovaraju vašem upitu.</td>');
			}
			
			if( $( settings.searchBox ).length )
			{
				$( settings.searchBox ).bind('keyup', function( e )
				{
					if( this.value !== $(this).attr('data-lastvalue') )
					{
						Run();
						
						$(this).attr('data-lastvalue', this.value);
					}
					
					if( e.keyCode == '39' ){ $(settings.paginationDiv + ' .next').click(); }
					if( e.keyCode == '37' ){ $(settings.paginationDiv + ' .previous').click(); }
				});
			}
		}
		
		var numberButton = settings.paginationDiv + ' .pagination-number:not(.first, .last, .next, .previous, .next-stack, .prev-stack)';
		if( settings.dataType !== '' )
		{
			Run();
		}
		else
		{
			if( settings.debug === true ) { console.log('Error : dataType attribute must be set!'); }
		}
	};
})( jQuery );