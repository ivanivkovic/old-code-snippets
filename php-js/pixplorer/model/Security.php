<?php

# General input security.
class Security{

	# Processing all input type="text" inputs.
	public static function secureTextInput($string)
	{
		$string = stripslashes($string);
		return $string;
	}
	
	# Processing all numeric inputs (select values etc...)
	public static function checkNumNotNull($num)
	{
		if(is_numeric($num) && $num != 0)
		{
			return $num;
		}
		else
		{
			return false;
		}
	}
	
	# Processing all numeric inputs (select values etc...)
	public static function checkNum($num)
	{
		if(is_numeric($num))
		{
			return $num;
		}
		else
		{
			return false;
		}
	}
	
	# Processing all textareas with more content.
	public static function secureTextArea($string)
	{
		$string = nl2br($string);
		$string = self::secureTextInput($string);
		return $string;
	}
}