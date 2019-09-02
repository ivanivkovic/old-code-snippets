

function AddNewInfo()
{
	
	var NewInfoElem = '<div class="info-obavijest" style="margin-bottom: 40px;">'+
						'<div class="inner-box">'+
		                	'<h3>Obavijest <a href="#" class="icon del fl" title="Obriši obavijest" onclick="DeleteInfo(this); return false;"></a></h3>'+
		                    '<textarea style="width: 500px; height: 60px;" name="info-obavijest[]"></textarea>'+
	               		'</div>'+
	               		
	               		'<div class="inner-box" style="margin-top: -7px;">'+
							'<h3>Datum objave</h3>'+
                       	 	'<input style="width: 150px;" name="info-date[]" class="text datepicker" type="text" value=""/>'+
						'</div>'+
					
						'<input type="hidden" name="info-infoid[]" value="0"/>'+
	               		
					'</div>';
	
	$('#info-obavijesti-list').append(NewInfoElem);
	$(".datepicker").datepicker(
			{
				firstDay: 1,
				dateFormat: 'dd.mm.yy.',
				dayNames: ['Nedjelja', 'Ponedeljak', 'Utorak', 'Srijeda', 'Četvrtak', 'Petak', 'Subota'],
				dayNamesMin: ['Ne', 'Po', 'Ut', 'Sr', 'Če', 'Pe', 'Su'],
				dayNamesShort: ['Ned', 'Po', 'Uto', 'Sri', 'Čet', 'Pet', 'Sub'],
				monthNames: ['Siječanj', 'Veljača', 'Ožujak', 'Travanj', 'Svibanj', 'Lipanj', 'Srpanj', 'Kolovoz', 'Rujan', 'Listopad', 'Studeni', 'Prosinac'],
				monthNamesShort: ['Sije', 'Velj', 'Ožu', 'Tra', 'Svi', 'Lip', 'Srp', 'Kol', 'Ruj', 'Lis', 'Stu']
			}
	);
}