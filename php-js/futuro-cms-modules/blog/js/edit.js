$(document).ready(function()
{
	$('#blog-edit-category').change(function()
	{
		var CategoryId = $(this).val();
		if( CanEditArray[CategoryId] )
		{
			$('#blog-edit-save').show();
		}
		else
		{
			$('#blog-edit-save').hide();
		}
		
		if( CanPublishArray[CategoryId] )
		{
			$('#blog-edit-publish').show();
			$('#blog-edit-lock').show();
			$('#blog-edit-unlock').show();
		}
		else
		{
			$('#blog-edit-publish').hide();
			$('#blog-edit-lock').hide();
			$('#blog-edit-unlock').hide();
		}
	});
	$('#blog-edit-category').change();
	
	$('#blog_frontpage').change(function()
	{
		if( $('#blog_frontpage').is(':checked') )
		{
			$('#blog-sticky-select').fadeIn();
		}
		else
		{
			$('#blog-sticky-select').fadeOut();
			$('#blog-sticky-select input').attr('checked', false);
		}
	});
	$('#blog_frontpage').change();
});


function blogEditLock(blogd)
{
	var VijestLockUrl = FFCMSRampUrl + '?blog&edit&action=lock&sesionid=' + sessionid;
	
	$.post(VijestLockUrl,
			{
				blogd: blogd
			},
			function(VijestLockRespJson)
			{
				alert(VijestLockRespJson);
				var VijestLockResp = $.parseJSON(VijestLockRespJson);
				VijestLockRespJson = null;
				
				if( ! VijestLockResp['error'] )
				{
					$('#blog-edit-lock').attr('onclick', 'blogEditUnlock('+blogd+'); return false;');
					$('#blog-edit-lock').html('Otključaj <span></span>');
					$('#blog-edit-lock').attr('id', 'blog-edit-unlock');
				}
				VijestLockResp = null;
			}
			
	);
}


function blogEditUnlock(blogd)
{
	var VijestUnLockUrl = FFCMSRampUrl + '?blog&edit&action=lock&sesionid=' + sessionid;
	
	$.post(VijestUnLockUrl,
			{
				blogd: blogd
			},
			function(VijestUnLockRespJson)
			{
				var VijestUnLockResp = $.parseJSON(VijestUnLockRespJson);
				VijestUnLockRespJson = null;
				
				if( ! VijestUnLockResp['error'] )
				{
					$('#blog-edit-unlock').attr('onclick', 'blogEditLock('+blogd+'); return false;');
					$('#blog-edit-unlock').html('Zaključaj <span></span>');
					$('#blog-edit-unlock').attr('id', 'blog-edit-lock');
				}
				VijestUnLockRespJson = null;
			}
			
	);
}