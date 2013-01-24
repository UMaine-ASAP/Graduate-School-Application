<?php

class Error
{
	public static function fatal($msg = '')
	{
		Error::log($msg);
		Error::crash('A fatal error has occurred');
	}	

	public static function log($msg = '')
	{
		error_log($msg);
	}

	public static function crash($msg = '')
	{
		throw new Exception($msg);
		exit();
	}


}