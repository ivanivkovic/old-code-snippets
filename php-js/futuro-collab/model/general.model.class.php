<?php

// Za općenite stvari. Valjda.
class modelGeneral
{
	public static function help()
	{
		libTemplate::loadTemplateFile( Conf::DIR_INCLUDES . 'help.php' );
	}
}
