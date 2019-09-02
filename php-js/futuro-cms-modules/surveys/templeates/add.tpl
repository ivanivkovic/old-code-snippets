{js surveys|edit.js}
{css surveys|edit.css}

<form method="post" action="#">

<input type="hidden" name="mode" id="mode" value="create"/>
<div class="box questionContainer">

	<h2 class="lang-toggle">{_surveys_add2}</h2>

	{? isset($error) } {$error} {/?}
	
	<div class="inner-box">
		<a class="button2 fr" href="#" onclick="history.go(-1); return false;" style="">{_back}<span></span></a>
		<a href="#" class="fr button2" onclick="addQuestion(); return false;">{_add_more_questions}</a>
		<div class="fl">
			{_publish_now} &nbsp;<input type="checkbox" name="status"/>
		</div>
		<div class="clear"></div>
	</div>
	
	{@lang}
		<div class="inner-box lang-title lang-{$LangKey}">
	       	<h3 class="lang-title lang-{$LangKey}">{_title} ({strtoupper($LangKey)})</h3>
			<input type="text" class="text lang-input lang-{$LangKey}" name="title[{$LangKey}]" value="{=$Data['title'][$LangKey]['title']}" />
		</div>
	{/@}
	
	{@lang}
			
		<div class="inner-box lang-title lang-{$LangKey} question" data-lang="{$LangKey}" data-number="0">
		
			<a href="#" onclick="removeQuestion(this); return false;" class="questionDelete">[{_delete_question}]</a>
	       	<h3>Pitanje ({strtoupper($LangKey)})</h3>
			
			<input type="text" class="text title" name="question[0][title][{$LangKey}]" value="{=$Data['question'][$LangKey]['title']}" />
			<h3>{_answers} ({strtoupper($LangKey)})</h3>
			
			<div class="clear"></div></br>
			
			<div class="answer-container">
				<input type="text" onkeyup="inputActions(this); return false;" class="answer fl text" name="question[0][options][{$LangKey}][]" value="" />
				<a href="#" class="icon del fr" title="{_delete}" onclick="removeAnswer(this, function(){}); return false;"></a>
				<div class="clear"></div>
			</div>
			
			<div class="answer-container">
				<input type="text" onkeyup="inputActions(this); return false;" class="answer fl text" name="question[0][options][{$LangKey}][]" value="" />
				<a href="#" class="icon del fr" title="{_delete}" onclick="removeAnswer(this); return false;"></a>
				<div class="clear"></div>
			</div>
			
			<div class="answer-container">
				<input type="text" onkeyup="inputActions(this); return false;" class="answer fl text" name="question[0][options][{$LangKey}][]" value="" />
				<a href="#" class="icon del fr" title="{_delete}" onclick="removeAnswer(this); return false;"></a>
				<div class="clear"></div>
			</div>
			
			<div class="fl">
			
				<span>{_option_limit}</span>
				
				<select class="filter o-limit" onchange="changeOptionLimit(this);" name="question[0][option_limit]">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
				</select>
				
			</div>
			
			<a href="#" class="fr add_answer" onclick="addAnswer(this); return false;">{_add_more_answers}</a>
			<div class="clear"></div>
		</div>
	{/@}
</div>

<div class="box">
	<h2>Akcije</h2>
	<a class="button2 fr" onclick="addQuestion(); return false;" href="#" style="">{_add_more_questions}<span></span></a>
	<a id="surveys-edit-save" class="button2 fr submit" href="#surveys_save=publis" style="">{_save}<span></span></a>
</div>

</form>