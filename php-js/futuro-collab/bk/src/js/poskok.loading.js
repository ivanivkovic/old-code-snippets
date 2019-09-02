/* Promjena kursora pri ajax-u. */

function LoadingEnable()
{
	$('#Loading').css('display', 'block');
}

function LoadingDisable()
{
	$('#Loading').css('display', 'none');
}

$(document).ready(function()
{
	var LoadingElem = $('<div>').attr('id', 'Loading').css('width', '32px').css('height', '32px');
	$(LoadingElem).append($('<img>').attr('src', '/src/img/loading.gif').css('width', '32px').css('height', '32px') );
	$('body').append(LoadingElem);
	LoadingElem = null;
	
	$('#Loading').css('position', 'absolute');
	
	$('html').mousemove(function(e){
	    $('#Loading').css('left', e.pageX + 10);
	    $('#Loading').css('top', e.pageY + 20);
	});

	LoadingDisable();
	
	$('html').ajaxStart(function(){ LoadingEnable(); });
	$('html').ajaxStop(function(){ LoadingDisable(); });
});