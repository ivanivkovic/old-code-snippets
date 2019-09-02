function strpos (haystack, needle, offset, occurence)
{
	if(occurence === 0)
	{
		var i = (haystack+'').indexOf(needle, offset);
	}
	else
	{
		var i = (haystack+'').lastIndexOf(needle, offset);
	}
	
	return i === -1 ? false : i;
}

// Changes ex. test[0] string to test[1]. If occurence 0 it'll change the first occurence of the string, if 1 then last.
function editFirstNameTag(string, replace)
{
	var pos = strpos(string, '[', 0, 0);
	var tagName = string.slice(0, pos);
	var tagNumber = string.slice(pos, string.length);
	
	pos = strpos(tagNumber, ']', 0, 0);
	tagNumber = tagNumber.slice(0, pos + 1);
	
	var number = tagNumber.replace('[', '');
	number = parseInt(number.replace(']', ''));
	
	if(mode === 'create'){
		var newNumber = '[' + replace + ']';
	}
	
	if(mode === 'update'){
		replace = replace;
		var newNumber = '[new][' + replace + ']';
	}
	
	return string.replace(tagNumber, newNumber);
}

function editLastNameTag(string, replace, obj)
{
	var strlen = string.length;
	var pos = strpos(string, '[', strlen, 1);
	var replace = '][' + string.slice(pos + 1, strlen -1) + ']';
	
	if(obj === 'question'){
		var newEntry = '][]';
	}
	if(obj === 'answer'){
		var newEntry = '][new][]';
	}
	return string.replace(replace, newEntry);
}

function addQuestion()
{
	var $index = $('.question:eq(0)').attr('data-number');
	
	boxes = [];
	
	$('.question[data-number="' + $index + '"]').each(function()
	{
		var question_data_number = parseInt($('.question').last().attr('data-number')) + 1;
		var $domobj = $(this).clone();
		
		$domobj.attr('data-number', question_data_number);
		
		if(mode == 'update')
		{
			if(!$('.new').length)
			{
				question_data_number = 0;
			}else{
				question_data_number = parseInt($('.new:last').attr('data-newnumber')) + 1;
			}
			
			$domobj.attr('data-newnumber', question_data_number);
			$domobj.addClass('new');
		}
		
		$domobj.find('input[type="text"]').val('');
		$domobj.find('.o-limit option:eq(0)').attr('selected', 'selected');
		$domobj.find('.answer-container').slice(3, $domobj.find('.answer').length - 1).remove();
		
		$domobj.find('.o-limit').each(function(){
			var $this = $(this);
			$this.attr('name', editFirstNameTag($this.attr('name'), question_data_number, 1));
		});
		
		$domobj.find('.title').each(function(){
			var $this = $(this);
			$this.attr('name', editFirstNameTag($this.attr('name'), question_data_number, 1));
		});
		
		$domobj.find('.answer').each(function(){
			var $this = $(this);
			$this.attr('name', editFirstNameTag($this.attr('name'), question_data_number, 1));
			$this.attr('name', editLastNameTag($this.attr('name'), '', 'question'));
			$this.attr('name', $this.attr('name').replace('][new]', ']') );
		});
		
		boxes.push($domobj);
	});
	
	$('.questionContainer').append(boxes);
	
	delete window.boxes;
}

function addAnswer(obj)
{
	var qIndex = $(obj).parent().attr('data-number');
	
	$('.question[data-number="' + qIndex + '"]').each(function(){
		
		var $this = $(this);
		var $domobj = $this.find('.answer-container:eq(0)').clone();
		
		$domobj.find('.answer').val('');
		
		if(mode == 'update' && !$this.hasClass('new')){
			$domobj.find('.answer').attr('name', editLastNameTag($domobj.find('.answer').attr('name'), '', 'answer'));
		}
		
		$this.find('.answer-container:last').after($domobj);
		$this.find('.answer:last').focus();
	});
	
	appendSelectOption(qIndex, $('.question[data-number="' + qIndex + '"]:first').find('.answer').length);
}

function removeAnswer(obj, callback)
{
	if($(obj).parent().parent().find('.answer-container').length > 2)
	{
		jQuery.ffcms.prompt.Ask({
			title: 'Brisanje opcije',
			message: 'Jeste li sigurni da želite izbrisati opciju? (vrijedi za sve jezike)',
			onOk: function(){
				
				var qIndex = $(obj).parent().parent().attr('data-number');
				
				if($.isFunction(callback)){
					callback();
				}
				
				removeSelectOption(qIndex, $('.question[data-number="' + qIndex + '"]:first').find('.answer-container').length);
				
				$('.question[data-number="' + qIndex + '"]').each(function(){
					$(this).find('.answer-container:eq(' +  $(obj).parent().parent().find('answer-container').index($(obj).parent()) + ')').remove();
				});
			}
		});
	}
}

function changeOptionLimit(obj)
{
	var qIndex = $(obj).parent().parent().attr('data-number');
	
	$('.question[data-number="' + qIndex + '"]').each(function(){
		$(this).find('.o-limit option:eq(' + $(obj).find('option:selected').index() + ')').attr('selected', 'selected');
	});
}

function appendSelectOption(qIndex, number)
{
	$('.question[data-number="' + qIndex + '"]').each(function(){
		$(this).find('.o-limit').append('<option value="' + number + '">' + number + '</option>');
	});
}

function removeSelectOption(qIndex, number)
{
	$('.question[data-number="' + qIndex + '"]').each(function(){
		$(this).find('.o-limit option[value="' + number + '"]').remove();
	});
}

function removeQuestion(obj)
{
	delQindex = parseInt($(obj).parent().attr('data-number'));
		
	jQuery.ffcms.prompt.Ask({
	
		title: 'Brisanje pitanja',
		message: 'Jeste li sigurni da želite izbrisati pitanje? (vrijedi za sve jezike)',
		onOk: function(){
			
			var lang = $('.question:last').attr('data-lang');
			
			if( $('.question[data-lang="' + lang + '"]').length < 2 )
			{
				jQuery.ffcms.prompt.Ask({ title: 'Brisanje pitanja', message: 'Žao nam je, anketa mora sadržavati najmanje jedno pitanje.', onOk : false});
			}
			else
			{
				$('.question[data-number="' + delQindex + '"]').remove();
			}
			
			delete window.delQindex;
		}
		
	});

}

function inputActions(obj){
	
	var e = e || window.event;
	
	var target = e.target || e.srcElement;
	
	var $this = $(obj);
	
	switch(e.keyCode){
	
		case 8: // Backspace
	
			if($this.val() == '')
			{
				e.preventDefault();
				
				removeAnswer(obj, function(){
					if($this.parent().prev().hasClass('answer-container'))
					{
						$this.parent().prev().find('.answer').focus();
					}
					else
					{
						if($this.parent().next().hasClass('answer-container')){
							$this.parent().next().find('.answer').focus();
						}
					}
				});
				
			}
		
		break;
	
		case 13: // Enter
		case 40: // Down
		
			if(!$this.parent().next().hasClass('answer-container'))
			{
				addAnswer($this.parent());
			}
			
			$this.parent().next().find('.answer').focus();
			
		break;
		
		case 38: // Up
			
			if($this.parent().prev().hasClass('answer-container')){
				$this.parent().prev().find('.answer').focus();
			}
			
		break;
		
	}
}

$(document).ready(function()
{
	mode = document.getElementById('mode').value;
});