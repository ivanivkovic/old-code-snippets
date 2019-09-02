<?php

class libSession
{
	public static function get($name)
	{
		if( isset( $_SESSION[ $name ] ) )
		{
			return $_SESSION[ $name ];
		}
		else
		{
			return false;
		}
	}

	public static function set($name, $value)
	{
		$_SESSION[ $name ] = $value;
	}
	
	public static function destroy()
	{
		session_destroy();
	}
}