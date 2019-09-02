

var BlogListOptiones = {
		page: 0,
		perpage: 20,
		categoryid: 0,
		authorid: 0,
		orderby: 'publishtime',
		orderdirection: 'desc'
}

function ChangeBlogListOptiones()
{
	BlogListLoad();
}

function BlogListLoad()
{
	var BlogListUrl = FFCMSRampUrl + '?blog&list&sessionid=' + sessionid;
	$.post(BlogListUrl,
			{
				page: BlogListOptiones.page,
				perpage: BlogListOptiones.perpage,
				categoryid: BlogListOptiones.categoryid,
				authorid: BlogListOptiones.authorid,
				orderby: BlogListOptiones.orderby,
				orderdirection: BlogListOptiones.orderdirection
			},
			function(BlogListRespJson)
			{
				var BlogListResp = $.parseJSON(BlogListRespJson);
				
				if( ! BlogListResp['error'] )
				{
					$('#blog-list-table').empty();
					for( BlogIndex in BlogListResp['blog'] )
					{
						var BlogData = BlogListResp['blog'][BlogIndex];
						
						var BlogRow = '<tr>'+
											'<td><a href="#!/blog/edit/blogid=' + BlogData['blogid'] + '">' + BlogData['blogid'] + '</a></td>' +
											'<td>' + BlogData['title'] + '</td>' +
											'<td>' + BlogData['categoryName'] + '</td>' +
											'<td>' + BlogData['author']['name'] + '</td>' +
											'<td><a href="#!/comments/list;CommentsSettings.LoadWhere=blog;CommentsSettings.LoadInterId=' + BlogData['blogid'] + '">' + BlogData['num_comments']['number'] + '</td>' +
											'<td>' + BlogData['publishtime'] + '</td>' +
											'<td>';
						
						if( BlogData['editable'] )
						{
							BlogRow = BlogRow + '<a class="icon edit" href="#!/blog/edit/blogid=' + BlogData['blogid'] + '" title="Uredi"></a>';
						}
						
						if( BlogData['delitable'] )
						{
							BlogRow = BlogRow + '<a class="icon del" href="#" title="Obriši" onclick="BlogListDelete(' + BlogData['blogid'] + '); return false;"></a>';
						}

						BlogRow = BlogRow + '</td>';
		
						
						
						if( BlogData['published'] == 1 )
						{
							BlogRow = BlogRow + '<td><a href="#" onclick="BlogListUnPublish(this, ' + BlogData['blogid'] + '); return false;" class="icon statusOn" title="Objavljena"></a></td>'; 
						}
						else
						{
							BlogRow = BlogRow + '<td><a href="#" onclick="BlogListPublish(this, ' + BlogData['blogid'] + '); return false;" class="icon statusOff" title="Ne objavljena"></a></td>'; 
						}
						
						BlogRow = BlogRow + '</tr>';
						
						$('#blog-list-table').append(BlogRow);
						
						BlogRow = null;
						BlogData = null;
					}
					
					$('#blog-list-table tr:odd').addClass('odd');
					
					PrintPaginator('#blog-list-paginator', BlogListResp['pageno'], BlogListOptiones.page, 'BlogListPage');
				}
				
				BlogListResp = null;
				BlogListRespJson = null;
			}
	);
}


function BlogListPage(PageNo)
{
	SetLocalVariables('BlogListOptiones.page', PageNo);
}


function BlogListPublish(BlogPubElem, BlogId)
{
	var BlogPublishUrl = FFCMSRampUrl + '?blog&edit&action=publish&sessionid=' + sessionid;
	$.post(BlogPublishUrl,
			{
				blogid: BlogId
			},
			function(BlogPublishRespJson)
			{
				
				var BlogPublishResp = $.parseJSON(BlogPublishRespJson);
				BlogPublishRespJson = null;
				
				if( !BlogPublishResp['error'] )
				{
					$(BlogPubElem).removeClass('statusOff');
					$(BlogPubElem).addClass('statusOn');
					$(BlogPubElem).attr('onClick', 'BlogListUnPublish(this, ' + BlogId + '); return false;');
					BlogPubElem = null;
				}
				BlogPublishResp = null;
			}
	);
}


function BlogListUnPublish(BlogPubElem, BlogId)
{
	var BlogUnPublishUrl = FFCMSRampUrl + '?blog&edit&action=unpublish&sessionid=' + sessionid;
	$.post(BlogUnPublishUrl,
			{
				blogid: BlogId
			},
			function(BlogUnPublishRespJson)
			{
				var BlogUnPublishResp = $.parseJSON(BlogUnPublishRespJson);
				BlogUnPublishRespJson = null;
				
				if( ! BlogUnPublishResp['error'] )
				{
					$(BlogPubElem).removeClass('statusOn');
					$(BlogPubElem).addClass('statusOff');
					$(BlogPubElem).attr('onClick', 'BlogListPublish(this, ' + BlogId + '); return false;');
					BlogPubElem = null;
				}
				BlogUnPublishResp = null;
			}
	);
}


function BlogListOrderBy(OrderBy)
{
	if( BlogListOptiones.orderby != OrderBy )
	{
		SetLocalVariables('BlogListOptiones.orderby', OrderBy);
		SetLocalVariables('BlogListOptiones.orderdirection', 'desc');
	}
	else
	{
		if( BlogListOptiones.orderdirection == 'desc' )
		{
			SetLocalVariables('BlogListOptiones.orderdirection', 'asc');
		}
		else
		{
			SetLocalVariables('BlogListOptiones.orderdirection', 'desc');
		}
	}
}


function BlogListDelete(BlogId)
{
	jQuery.ffcms.prompt.Ask({
		title: 'Brisanje blogova',
		message: 'Jeste li sigurni da želite obrisati blog?',
		onOk: function()
		{
			var BlogDeleteUrl =  FFCMSRampUrl + '?blog&edit&action=delete&sessionid=' + sessionid;
			$.post(BlogDeleteUrl,
				{
					blogid: BlogId
				},
				function(BlogDeleteRespJson)
				{
					var BlogDeleteResp = $.parseJSON(BlogDeleteRespJson);
					BlogDeleteRespJson = null;
					if( ! BlogDeleteResp['error'] )
					{
						BlogListLoad();
					}
					BlogDeleteResp = null;
				}
			);
		}
	});
}


$(document).ready(function()
{
	BlogListLoad();
	
	$('#blog-list-categoryid').change(function()
	{
		SetLocalVariables('BlogListOptiones.categoryid', $(this).val());
	});
	$('#blog-list-authorid').change(function()
	{
		SetLocalVariables('BlogListOptiones.authorid', $(this).val());
	});
});