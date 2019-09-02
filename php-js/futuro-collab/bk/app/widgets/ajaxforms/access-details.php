<form class="form">
	<label for="projectname">Upišite ime željenog projekta:</label>
	<input type="text" name="projectname" onkeyup="getAccessInfo(this.value)"/>
</form>

<div id="return-content"></div>

<script>

function getAccessInfo(string)
{
	if(string != '')
	{
		$.poskok.getAjax(
			'gethtml/getAccessDetails/project/' + string,
			function(data){
				document.getElementById("return-content").innerHTML=data;
			}
		);
	}
	else
	{
		document.getElementById("return-content").innerHTML='';
	}
}

</script>