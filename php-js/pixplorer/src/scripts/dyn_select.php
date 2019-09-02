$('.world').live('change', 
	function(){
		if($(this).attr('id') !== 'city'){
			loadResult( $(this).attr('id'), $(this).attr('value') );
		}
	}
);

$('#category').live('change', 
	function(){
		loadSubcategories($('#category option:selected').val());
	}
);

$('#search_criteria select').live('change',
	function(){
		createURL();
	}
);

function loadResult(loc_id, value){
	$.get(
	'<?php echo Conf::$page['ajax'] ?>' + 'world_select_update/' + loc_id + '/' + value,
	function(data){
		var city_result = data.search('id="city"');
		if(city_result != -1){
			returned = 'city';
		}
		
		var region_result = data.search('id="region"');
		if(region_result != -1){
			returned = 'region';
		}
		if(returned == 'region'){
			$('#region, #city').remove();
			$('#country').after(data);
		}
		if(returned == 'city'){
			if(loc_id == 'country'){
				if($('#region').length){
					$('#region').remove();
				}
			}
			if($('#city').length){
				$('#city').remove();
			}
			if(!$('#region').length){
				$('#country').after(data);
			}else{
				$('#region').after(data);
			}
		}
	},
	'html'
	);
}

function loadSubcategories(id){
	$.get(
		'<?php echo Conf::$page['ajax'] ?>dyn_categories/' + id,
		function(data){
			$('#subcategory').remove();
			$('#category').after(data);
		},
		'html'
	);
}

function createURL(){
	
	val = parseInt($('#cat').val());
	if(val != 'undefined' && val != false){
		catID = val;
	}else{
		catID = 0;
	}
	
	val = parseInt($('#sel_category option:selected').val());
	if(val != 'undefined' && val != false && isNaN(val) === false){
		subcatID = val;
	}else{
		subcatID = 0;
	}
	
	val = parseInt($('#country option:selected').val());
	if(val != 'undefined' && val != false && isNaN(val) === false){
		countryID = val;
	}else{
		countryID = 0;
	}
	
	val = parseInt($('#city option:selected').val());
	if(val != 'undefined' && val != false && isNaN(val) === false){
		cityID = val;
	}else{
		cityID = 0;
	}
	
	val = parseInt($('#region option:selected').val());
	if(val != 'undefined' && val != false && isNaN(val) === false){
		regionID = val;
	}else{
		regionID = 0;
	}
	
	url = $('#searchUrl').attr('data-urlbase') + cityID + '/' + catID + '/' + subcatID + '/' + countryID + '/' + regionID + '/';
	$('#searchUrl').attr('href', url);
	
}