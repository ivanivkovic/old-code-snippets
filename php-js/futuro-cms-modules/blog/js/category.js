$(document).ready(function()
{
	
	$('#blog-category-optiones').hide();
	
});


function BlogCategoryNew()
{
	$('.blog-category-title').val('');
	$('#blog-category-categoryid').val(0);
	$('#blog-category-optiones').fadeIn();
}





function BlogCategorySave()
{
	var CategoryId = $('#blog-category-categoryid').val();
	var Title = $('.blog-category-title').val();
		
	var CategorySaveUrl = FFCMSRampUrl + '?blog&category&action=save&sessionid=' + sessionid;
	
	var Texts = { 'title' : {} };
	
	$('.blog-category-title').each(function(){
		Texts.title[$(this).attr('data-lang')] = $(this).val();
	});
	
	$.post(CategorySaveUrl,
			{
				categoryid: CategoryId,
				title: Title,
				texts : Texts
			},
			function( CategorySaveRespJson )
			{
				var CategorySaveResp = $.parseJSON(CategorySaveRespJson);
				CategorySaveRespJson = null;
				
				if( ! CategorySaveResp['error'] )
				{
					jQuery.ffcms.notifi.WriteSuccess('Uspješno spremanje kategorije!', '#blog-category-error');
					if( CategoryId != 0 )
					{
						$('#blog-category-' + CategoryId).html(Title);
						var Added = false;
						$('#blog-category-list li').each(function(ElementIndex, Element)
						{
							if( $(Element).children('a').html() > Title )
							{
								$(Element).before($('#blog-category-' + CategoryId).parent());
								Added = true;
								return false;
							}
						});
						if( ! Added )
						{
							$('#blog-category-list').append($('#blog-category-' + CategoryId).parent());
						}
					}
					else
					{
						$('#blog-category-categoryid').val(CategorySaveResp['categoryid']);
						var NewCategoryEl = '<li><a id="blog-category-' + CategorySaveResp['categoryid'] +'" href="#" onclick="blogCategoryLoad(' + CategorySaveResp['categoryid'] +'); return false;">'+Title+'</a><span></span></li>';
						var Added = false;
						$('#blog-category-list li').each(function(ElementIndex, Element)
						{
							if( $(Element).children('a').html() > Title )
							{
								$(Element).before(NewCategoryEl);
								Added = true;
								return false;
							}
						});
						if( ! Added )
						{
							$('#blog-category-list').append(NewCategoryEl);
						}
						NewCategoryEl = null;
					}
				}
				else
				{
					jQuery.ffcms.notifi.WriteError(CategorySaveResp['error'], '#blog-category-error');
				}
				
				CategorySaveResp = null;
			}
	
	);
}


function BlogCategoryDelete()
{
	var CategoryId = $('#blog-category-categoryid').val();
	
	var CategoryDeleteUrl = FFCMSRampUrl + '?blog&category&action=delete&sessionid=' + sessionid;
	
	jQuery.ffcms.prompt.Ask({
	
		title: 'Brisanje kategorije',
		message: 'Jeste li sigurni da želite izbrisati kategoriju?',
		onOk: function(){
	
			$.post(CategoryDeleteUrl,
			{
				categoryid: CategoryId,
			},
			function( CategoryDeleteRespJson )
			{
				var CategoryDeleteResp = $.parseJSON(CategoryDeleteRespJson);
				CategoryDeleteRespJson = null;
				
				if( ! CategoryDeleteResp['error'] )
				{
					jQuery.ffcms.notifi.WriteSuccess('Uspješno brisanje kategorije!', '#blog-category-error');
					$('.blog-category-title').val('');
					$('#blog-category-categoryid').val(0);
					$('#blog-category-' + CategoryId).parent().fadeOut(function()
					{
						$('#blog-category-optiones').fadeOut('slow');
					});
				}
				else
				{
					jQuery.ffcms.notifi.WriteError(CategoryDeleteResp['error'], '#blog-category-error');
				}
				
				CategoryDeleteResp = null;
			}
			);
		}
	});
}


function BlogCategoryLoad(CategoryId)
{
	var CategoryLoadUrl = FFCMSRampUrl + '?blog&category&action=load&sessionid=' + sessionid;
	
	$.post(CategoryLoadUrl,
	{
		categoryid: CategoryId,
	},
	function( CategoryLoadRespJson )
	{
		var CategoryLoadResp = $.parseJSON(CategoryLoadRespJson);
		CategoryLoadRespJson = null;
		
		if( ! CategoryLoadResp['error'] )
		{
			jQuery.ffcms.notifi.WriteSuccess('Uspješno učitavanje kategorije!', '#blog-category-error');
			
			$('.blog-category-title').each(function()
			{
				var $this = $(this);
				$this.val( CategoryLoadResp['category']['texts'][ $this.attr('data-lang') ]['title'] );
			});
			
			$('#blog-category-categoryid').val(CategoryId);
			$('#blog-category-optiones').fadeIn();
		}
		else
		{
			jQuery.ffcms.notifi.WriteError(CategoryLoadResp['error'], '#blog-category-error');
		}
		
		CategoryLoadResp = null;
	}
);
}