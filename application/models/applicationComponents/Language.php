<?php

require_once __DIR__ . "/ApplicationComponent.php";

class Language extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_Language';
	protected static $primaryKeys = array('languageId', 'applicationId');

	/**
	 * Get Option
	 * 
	 * Accessor for accepted values for enumerated DB fields. Includes values and display names.
	 * 
	 * @return array
	 */
	protected static $availableOptions = array('options_proficiency');
	
	public static function getOption($optionName)
	{
		switch($optionName)
		{
		 	case 'options_proficiency':
				return array(	''	  	=> '- None -',
							'Good' 	=> 'Good',
							'Fair' 	=> 'Fair',
							'Poor' 	=> 'Poor');
		 	break;

		}
		return null; // nothing found
	}

}