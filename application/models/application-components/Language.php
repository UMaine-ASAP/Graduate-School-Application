<?php

// Models
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

	public static function getWithId($languageId)
	{
		$application = ApplicationController::getActiveApplication();
		if( $application == null)
		{
			return null;
		}
		$dbObject = Database::getFirst("SELECT * FROM APPLICATION_Language WHERE applicationId = %d AND languageId = %d", $application->id, $languageId);

		$language = Model::factory('Language');
		$language->loadFromDB($dbObject);
		return $language;
	} 

}