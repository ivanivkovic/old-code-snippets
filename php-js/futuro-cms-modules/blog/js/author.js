$(document).ready(function()
{
	$('#blog-author-optiones').hide();
});


function BlogAuthorNew()
{
	$('#blog-author-name').val('');
	$('#blog-author-authorid').val(0);
	$('#blog-email').val('');
	$('#blog-phone').val('');
	$('.blog-title').val('');
	$('.blog-role').val('');
	$('#addon-container').html('<input class="ffcms-inline-addon" value="images,blog,0,author_image,author_image_big"/>');

	$('#blog-author-optiones').fadeIn();
	LoadInlineAddons();
}

function BlogAuthorSave()
{
	var AuthorId 			= $('#blog-author-authorid').val();
	var Name 				= $('#blog-author-name').val();
	var Phone 				= $('#blog-phone').val();
	var Email 				= $('#blog-email').val();
	
	var ImageId 			= $('.image-imageid').val();
	var ImageIncarnation 	= $('.image-incarnation').val();
	var ImageCaption 		= $('.image-caption').val();
	var ImageWaterMark 		= $('.image-watermark').val();
		
	var AuthorSaveUrl = FFCMSRampUrl + '?blog&author&action=save&sessionid=' + sessionid;
	
	var Texts = { 'title' : {}, 'role' : {} };
	
	$('.blog-title').each(function(){
		Texts.title[$(this).attr('data-lang')] = $(this).val();
	});
	
	$('.blog-role').each(function(){
		Texts.role[$(this).attr('data-lang')] = $(this).val();
	});
	
	$.post(AuthorSaveUrl,{
		authorid: AuthorId,
		name: Name,
		email: Email,
		phone: Phone,
		
		ImageId : ImageId,
		ImageIncarnation : ImageIncarnation,
		ImageCaption : ImageCaption,
		ImageWaterMark : ImageWaterMark,
		texts : Texts
	},
	function( AuthorSaveRespJson )
	{
		var AuthorSaveResp = $.parseJSON(AuthorSaveRespJson);
		AuthorSaveRespJson = null;
		
		if( ! AuthorSaveResp['error'] )
		{
			jQuery.ffcms.notifi.WriteSuccess('Uspješno spremanje autora!', '#blog-author-error');
			if( AuthorId != 0 )
			{
				$('#blog-author-' + AuthorId).html(Name);
				var Added = false;
				$('#blog-author-list li').each(function(ElementIndex, Element)
				{
					if( $(Element).children('a').html() > Name )
					{
						$(Element).before($('#blog-author-' + AuthorId).parent());
						Added = true;
						return false;
					}
				});
				if( ! Added )
				{
					$('#blog-author-list').append($('#blog-author-' + AuthorId).parent());
				}
			}
			else
			{
				$('#blog-author-authorid').val(AuthorSaveResp['authorid']);
				var NewAuthorEl = '<li><a id="blog-author-' + AuthorSaveResp['authorid'] +'" href="#" onclick="BlogAuthorLoad(' + AuthorSaveResp['authorid'] +'); return false;">'+Name+'</a><span></span></li>';
				var Added = false;
				$('#blog-author-list li').each(function(ElementIndex, Element)
				{
					if( $(Element).children('a').html() > Name )
					{
						$(Element).before(NewAuthorEl);
						Added = true;
						return false;
					}
					});
				if( ! Added )
					{
					$('#blog-author-list').append(NewAuthorEl);
				}
				NewAuthorEl = null;
			}
		}
		else
		{
			jQuery.ffcms.notifi.WriteError(AuthorSaveResp['error'], '#blog-author-error');
		}
		AuthorSaveResp = null;
	}
	);
}


function BlogAuthorDelete()
{
	var AuthorId = $('#blog-author-authorid').val();
	var AuthorDeleteUrl = FFCMSRampUrl + '?blog&author&action=delete&sessionid=' + sessionid;
	
	jQuery.ffcms.prompt.Ask({
	
		title: 'Brisanje autora',
		message: 'Jeste li sigurni da želite izbrisati autora?',
		onOk: function(){
			$.post(AuthorDeleteUrl,
			{
				authorid: AuthorId,
			},
				function( AuthorDeleteRespJson )
				{
					var AuthorDeleteResp = $.parseJSON(AuthorDeleteRespJson);
					AuthorDeleteRespJson = null;
					
					if( ! AuthorDeleteResp['error'] )
					{
						jQuery.ffcms.notifi.WriteSuccess('Uspješno brisanje autora!', '#blog-author-error');
						$('#blog-author-name').val('');
						$('#blog-author-authorid').val(0);
						$('#blog-author-' + AuthorId).parent().fadeOut(200).remove();
						$('#addon-container').html('<input class="ffcms-inline-addon" value="images,blog,0,author_image,author_image_big"/>');
						LoadInlineAddons();
					}
					else
					{
						jQuery.ffcms.notifi.WriteError(AuthorDeleteResp['error'], '#blog-author-error');
					}
					
					AuthorDeleteResp = null;
				}
			
			);
		}
		
	});
}


function BlogAuthorLoad(AuthorId)
{
	var AuthorLoadUrl = FFCMSRampUrl + '?blog&author&action=load&sessionid=' + sessionid;
	
	$.post(AuthorLoadUrl,
	{
		authorid: AuthorId,
	},
	function( AuthorLoadRespJson )
	{
		var AuthorLoadResp = $.parseJSON(AuthorLoadRespJson);
		AuthorLoadRespJson = null;
		
		if( ! AuthorLoadResp['error'] )
		{
			jQuery.ffcms.notifi.WriteSuccess('Uspješno učitavanje autora!', '#blog-author-error');
			
			$('#blog-author-name').val(AuthorLoadResp['author']['name']);
			$('#blog-author-authorid').val(AuthorId);
			$('#addon-container').html('<input class="ffcms-inline-addon" value="images,blog,' + AuthorId + ',author_image,author_image_big"/>');
			$('#blog-email').val(AuthorLoadResp['author']['email']);
			$('#blog-phone').val(AuthorLoadResp['author']['phone']);
			
			$('.blog-title').each(function()
			{
				var $this = $(this);
				$this.val( AuthorLoadResp['texts'][ $this.attr('data-lang') ]['title'] );
			});
			
			$('.blog-role').each(function()
			{
				var $this = $(this);
				$this.val( AuthorLoadResp['texts'][ $this.attr('data-lang') ]['role'] );
			});
			
			LoadInlineAddons();
			
			$('#blog-author-optiones').fadeIn();
		}
		else
		{
			jQuery.ffcms.notifi.WriteError(AuthorLoadResp['error'], '#blog-author-error');
		}
		
		AuthorLoadResp = null;
	}
	
	);
}