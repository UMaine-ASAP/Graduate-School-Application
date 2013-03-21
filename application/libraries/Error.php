<?php

/**
 * Manage error logs
 */
class Error
{

	/**
	 * Log error and crash
	 * 
	 * @param    string    Message to log
	 * 
	 * @return void
	 */
	public static function fatal($msg = '')
	{
		Error::log($msg);
		Error::crash('A fatal error has occurred');
	}	


	/**
	 * Log error
	 * 
	 * @param    string    Message to log
	 * 
	 * @return void
	 */
	public static function log($msg = '')
	{
		error_log($msg);
	}


	/**
	 * Crash application
	 * 
	 * @param    string    Message to display
	 * 
	 * @return void
	 */
	public static function crash($msg = '')
	{
		throw new Exception($msg);
		exit();
	}
}