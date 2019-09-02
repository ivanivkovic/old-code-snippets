<?php

class SurveysList{
	
	public static function Run()
	{
		if(OUTPUT === 'html')
		{			
			libLoad::Api('apiSurveyLoad', 'surveys');
			
			$tpl = new libTempleate('list.tpl', 'surveys');
			
			if(intval(apiSurveyLoad::getSurveysCount()) === 0)
			{
				$tpl->set('error', '<div class="inner-box"><h3>Trenutno nemate nijednu anketu. <a href="#!/surveys/edit">Stvori novu anketu</a></h3></div>');
			}
			else
			{
				$tpl->set('Data', apiSurveyLoad::getSurveys(0, 20, 'time', 'desc'));
			}
			
			usrAdmin::SetCentralContent($tpl);
		}
		
		if(OUTPUT === 'ajax')
		{
			libLoad::Api('apiSurveyLoad', 'surveys');
			
			if( isset($_POST['page']) && is_numeric($_POST['page']) )
			{
				$Page = $_POST['page'];
			} 
			else
			{
				$Page = 0;
			}
			
			if( isset($_POST['perpage']) && is_numeric($_POST['perpage']) )
			{
				$Limit = $_POST['perpage'];
			} 
			else
			{
				$Limit = 20;
			}
			
			if( isset($_POST['orderby']) && in_array($_POST['orderby'], array('survey_id', 'time')) )
			{
				$OrderBy = $_POST['orderby'];
			}
			else
			{
				$OrderBy = 'survey_id';
			}
			
			if( isset($_POST['orderdirection']) && $_POST['orderdirection'] == 'asc' )
			{
				$OrderDirection = 'ASC';
			}
			else
			{
				$OrderDirection = 'DESC';
			}
			
			$Offset = $Page * $Limit;
			
			usrAjax::SetOutputData( array( 'error' => false, 'surveysData' => apiSurveyLoad::getSurveys($Offset, $Limit, $OrderBy, $OrderDirection), 'pageno' => ceil(apiSurveyLoad::getSurveysCount() / $Limit)) );
			
		}
	}
}

SurveysList::Run();