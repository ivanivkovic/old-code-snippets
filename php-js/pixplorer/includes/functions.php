<?php

# Just removes the asterisk from a string. 
function clearAsterisk($string)
{
	$string = str_replace('*', '', $string);
	return $string;
}

# Determines if a given url has a pattern in the url. (http://www.facebook.com has acebook.co)
function hasUrl($string, $url)
{
	if(strpos($url, $string) !== false){
		return true;
	}else{
		return false;
	}
}

# Returns a limited string, if it's longer than the limit, the limited version will have an ending (example "This a a strin...")
function limitString($string, $length, $ending = '')
{
	$ending = strlen($string) > $length ? $ending : '';
	return substr($string, 0, $length) . $ending;
}

# Removes whitespace
function compressCSS($string)
{

	# Strips Comments
	$string = preg_replace('!/\*.*?\*/!s','', $string);
	$string = preg_replace('/\n\s*\n/',"\n", $string);

	# Minifies 
	$string = preg_replace('/[\n\r \t]/',' ', $string);
	$string = preg_replace('/ +/',' ', $string);
	$string = preg_replace('/ ?([,:;{}]) ?/','$1',$string);

	# Kill Trailing Semicolon, Contributed by Oliver 
	$string = preg_replace('/;}/','}',$string);

	# Return Minified CSS 
	return $string;
}

# Removes whitespace
function htmlCompress($buffer)
{
	$search = array(
	"/ +/" => " ",
	"/<!–\{(.*?)\}–>|<!–(.*?)–>|[\t\r\n]|<!–|–>|\/\/ <!–|\/\/ –>|<!\[CDATA\[|\/\/ \]\]>|\]\]>|\/\/\]\]>|\/\/<!\[CDATA\[/" => ""
	);
	$buffer = preg_replace(array_keys($search), array_values($search), $buffer);
	return $buffer;

}

?>