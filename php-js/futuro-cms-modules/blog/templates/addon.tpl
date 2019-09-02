<h2>Izbor kategorije blogova koja će se prikazivati na stranici</h2>
<div class="inner-box">
	<h3>Kategorija</div>
	<select class="filter" style="margin-left: 30px; width: 200px;" name="blog[categoryid]">
		<option value="0">Bez blogova</option>
		{@ $CategoryList as $Category }
			<option 
			{? $SelectedCategory && $Category['categoryid'] == $SelectedCategory['categoryid'] }
				selected="selected"
			{/?}
			value="{$Category['categoryid']}">{$Category['title']}</option> 
		{/@}
	</select>
</div>