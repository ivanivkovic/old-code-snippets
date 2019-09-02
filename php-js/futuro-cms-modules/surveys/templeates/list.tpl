<div class="box questionContainer">

	<h2>{_surveys_list}</h2>
	
	
	{? isset($error) } {$error} {/?}
	
	{? !isset($error) }
	
	<div class="inner-box">
		<a class="button2" href="{FFConf::GetUrl('fly')}#!/surveys/edit"">{_surveys_add}</a><br/>
	</div>
	
	<div class="table-roundup clear" width="width: 97%;">
		<table width="100%" class="table-new">
			<thead>
				<tr>
					<td width="7%"><a href="#" onclick="SurveysListOrderBy('survey_id'); return false;">ID</a></td>
					<td width="51%">{_title}</td>
					<td width="8%">{_num_questions}</td>
					<td width="12%">{_latest_answer}</td>
					<td width="10%"><a href="#" onclick="SurveysListOrderBy('time'); return false;">{_pub_date}</a></td>
					<td width="7%">{_action}</td>
					<td width="5%">{_status}</td>
				</tr>
			</thead>
			<tbody id="surveys-list-table"></tbody>
		</table>
	</div>
	
    <ul class="paginator" id="surveys-list-paginator"> </ul>
	
	{/?}
</div>

<!-- End box -->

{js surveys|list.js}