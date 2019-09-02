var SurveysListOptiones = {
	page: 0,
	perpage: 20,
	orderby: 'time',
	orderdirection: 'desc'
}

function surveysPublishToggle(obj, id){
	
	$.post(
		FFCMSRampUrl + '?surveys&edit&action=publish&sessionid=' + sessionid,
		{
			id: id,
			status: $(obj).attr('data-published') == 0 ? 1 : 0
		},
		function(returnJson){
			
			var response = $.parseJSON(returnJson);
			
			if( ! response['error'] )
			{
				if($(obj).attr('data-published') == '0')
				{
					$(obj).attr('data-published', 1);
					$(obj).attr('title', 'Objavljena');
					$(obj).removeClass('statusOff');
					$(obj).addClass('statusOn');
				}
				else
				{
					$(obj).attr('data-published', 0);
					$(obj).attr('title', 'Neobjavljena');
					$(obj).removeClass('statusOn');
					$(obj).addClass('statusOff');
				}
			}
		}
	);
}

function SurveyDelete(id){

	jQuery.ffcms.prompt.Ask({
		title: 'Brisanje opcije',
		message: 'Jeste li sigurni da želite izbrisati anketu?',
		onOk: function(){
			$.post(
				FFCMSRampUrl + '?surveys&edit&action=delete&sessionid=' + sessionid,
				{
					id: id,
				},
				function(returnJson)
				{	
					var response = $.parseJSON(returnJson);
					console.log(response);
					if( ! response['error'] )
					{
						$('#survey_' + id).slideUp().remove();
					}
				}
			);
		}
	});
	
}

function SurveysListLoad(){

	$.post(
		FFCMSRampUrl + '?surveys&list&sessionid=' + sessionid,
		{
			page: SurveysListOptiones.page,
			perpage: SurveysListOptiones.perpage,
			orderby: SurveysListOptiones.orderby,
			orderdirection: SurveysListOptiones.orderdirection
		},
		function(SurveysRespJson)
		{
			var SurveysResp = $.parseJSON(SurveysRespJson);
			
			if( ! SurveysResp['error'] ){
				
				$('#surveys-list-table').empty();
				
				for( SurveysIndex in SurveysResp['surveysData'] )
				{
					var SurveysData = SurveysResp['surveysData'][SurveysIndex];
					
					var published = SurveysData['active'] == '1' ?
									'<a href="#" onclick="surveysPublishToggle(this, '+ SurveysData['id'] +'); return false;" class="icon statusOn" title="Objavljena" data-published="1"></a>' 
									: '<a href="#" onclick="surveysPublishToggle(this, '+ SurveysData['id'] +'); return false;" class="icon statusOff" title="Neobjavljena" data-published="0"></a>';
					
					var SurveysRow = '<tr id="survey_' + SurveysData['id'] + '">' +
									'<td><a href="#!/surveys/edit/surveyid=' + SurveysData['id'] + '">' + SurveysData['id'] + '</a></td>' +
									'<td>' + SurveysData['title'] + '</td>' +
									'<td>' + SurveysData['count'] + '</td>' +
									'<td>' + (SurveysData['latest_answer_date'] != 0 ? SurveysData['latest_answer_date'] + ' ' + SurveysData['latest_answer_time']: 'Nema odgovora') + '</td>' +
									'<td>' + SurveysData['time'] + '</td>' +
									'<td>' +
									'<a class="icon edit" href="#!/surveys/edit/surveyid=' + SurveysData['id'] + '" title="Uredi"></a>' +
									'<a class="icon del" href="#" onclick="SurveyDelete(' + SurveysData['id'] + '); return false;" data-id="' + SurveysData['id'] + '" title="Izbriši"></a>' +
									'</td>' +
									'<td>' + published + '</td>' +
								'</tr>'
								;
								
					$('#surveys-list-table').append(SurveysRow);
					
				}
				PrintPaginator('#surveys-list-paginator', SurveysResp['pageno'], SurveysListOptiones.page, 'SurveysListPage');
			}
		}
	);
	
}

function SurveysListOrderBy(OrderBy)
{
	if( SurveysListOptiones.orderby != OrderBy )
	{
		SetLocalVariables('SurveysListOptiones.orderby', OrderBy);
		SetLocalVariables('SurveysListOptiones.orderdirection', 'desc');
	}
	else
	{
		if( SurveysListOptiones.orderdirection == 'desc' )
		{
			SetLocalVariables('SurveysListOptiones.orderdirection', 'asc');
		}
		else
		{
			SetLocalVariables('SurveysListOptiones.orderdirection', 'desc');
		}
	}
}

function SurveysListPage(PageNo)
{
	SetLocalVariables('SurveysListOptiones.page', PageNo);
}

function ChangeSurveysListOptiones()
{
	SurveysListLoad();
}

$(document).ready(function(){  SurveysListLoad(); });