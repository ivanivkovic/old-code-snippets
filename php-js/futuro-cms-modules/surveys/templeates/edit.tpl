{js surveys|edit.js}
{css surveys|edit.css}

{~$firstobj = $Data['hr'];}

<div class="box">

	<h2>{_survey_stats}</h2>	
	<div class="inner_box">
		
		{? $Stats !== false }
		
			<div class="chart-container fl">
				
				<h4>{=$firstobj->title}</h4>
				
				{@ $Stats as $qId => $qArray }
				
					<h3>{=$firstobj->questions[$qId]['title']}</h3>
					
					{@ $qArray['options'] as $oId => $oArray }
						
						<div class="chart-box fl">
							<div class="chart-fill" style="width: {=$oArray['percentage']}%; {? $oArray['percentage'] != 0 } border: 1px solid #aaa; {/?}"></div>
						</div>
						<div class="chart-info fl">
							<h5 class="">{=$oArray['number']} {_votes}, {=$oArray['percentage']}%</h5>
						</div>
						<div class="chart-title fl">
							<h5 class="">{=$firstobj->questions[$qId]['options'][$oId]['title']}</h5>
						</div>
						<div class="clear"></div>
						
					{/@}
					
					<br/>
					
				{/@}
				
			</div>
			
			<div class="clear"></div>
			
		{/?}
		
		{? !$Stats }
			<p>{_no_answers}</p>
		{/?}
		
	</div>
</div>

<form method="post" action="#">

<input type="hidden" name="mode" id="mode" value="update"/>
<div class="box questionContainer">
	
	<h2 class="lang-toggle">{_survey_edit}</h2>
	
	<div class="inner-box">
	
		<a class="button2 fr" href="#" onclick="history.go(-1); return false;" style="">{_back}<span></span></a>
		<a href="#" class="fr button2" onclick="addQuestion(); return false;">{_add_more_questions}</a>
		<div class="fl">
			{_publish_now} &nbsp;<input type="checkbox" name="status" {? $firstobj->active == '1'} checked {/?}/>
		</div>
		<div class="clear"></div>
	</div>
	
	<input type="hidden" name="survey_id" value="{~ echo $firstobj->id; }"/>
	
	{@lang}
		<div class="inner-box lang-title lang-{$LangKey}">
	       	<h3 class="lang-title lang-{$LangKey}">{_title} ({strtoupper($LangKey)})</h3>
			<input type="text" class="text lang-input lang-{$LangKey}" name="title[{$LangKey}]" value="{=$Data[$LangKey]->title}" />
		</div>
	{/@}
	
	{@lang}
		
		
			
		{~ $questions = $Data[$LangKey]->questions; $c = 0; }
			{@ $questions as $qID => $qArray }
		
			<div class="inner-box lang-title lang-{$LangKey} question" data-lang="{$LangKey}" data-number="{=$c}">
			
				<a class="questionDelete" onclick="removeQuestion(this); return false;">[{_delete_question}]</a>
				<h3 class="lang-title lang-{$LangKey}">{_question} ({strtoupper($LangKey)})</h3>
				<br/>
				
				<input type="text" class="text title lang-input lang-{$LangKey}" name="question[{$qID}][title][{$LangKey}]" value="{=$qArray['title']}" />
				
				<br/>
				
				<h3 class="lang-title lang-{$LangKey}">{_answers} ({strtoupper($LangKey)})</h3>
				<div class="clear"></div><br/>
				
				{@ $qArray['options'] as $oID => $oTitle}
					<div class="answer-container">
						<input type="text" onkeyup="inputActions(this); return false;" class="answer text fl" name="question[{$qID}][options][{$LangKey}][{$oID}]" value="{=$oTitle['title']}" />
						<a href="#" class="icon del fr" title="{_delete}" onclick="removeAnswer(this, function(){}); return false;"></a>
						<div class="clear"></div>
					</div>
				{/@}
				
				<div class="fl">
					<span>{_option_limit}</span>
					
					<select class="filter o-limit" onchange="changeOptionLimit(this);" name="question[{$qID}][option_limit]">
					
						{~ $c2 = 1; }
						
						{@ $qArray['options'] as $option }
							
							<option value="{=$c2}" {? $c2 == $qArray['option_limit']} selected {/?} >{=$c2}</option>
							
							{~ $c2++ }
							
						{/@}
						
					</select>
				</div>
				
				<a href="#" class="fr add_answer" onclick="addAnswer(this); return false;">{_add_more_answers}</a>
				<div class="clear"></div>
			</div>
			
			{~ ++$c; }
		{/@}
	{/@}
	
</div>

<div class="box">
	<h2>Akcije</h2>
	<a class="button2 fr" onclick="addQuestion(); return false;" href="#" style="">{_add_more_questions}<span></span></a>
	<a id="surveys-edit-save" class="button2 fr submit" href="#surveys_save=publis" style="">{_save}<span></span></a>
</div>

</form>