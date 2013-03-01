<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";

class PreviousSchool extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_PreviousSchool';
	protected static $primaryKeys = array('previousSchoolId', 'applicationId');

	public static function getWithId($previousSchoolId)
	{
		$application = ApplicationController::getActiveApplication();
		if( $application == null)
		{
			return null;
		}
		$dbObject = Database::getFirst("SELECT * FROM APPLICATION_PreviousSchool WHERE applicationId = %d AND previousSchoolId = %d", $application->id, $previousSchoolId);

//		$language = new Language('Language');
		$previousSchool = Model::factory('PreviousSchool');
		$previousSchool->loadFromDB($dbObject);
		return $previousSchool;
	} 	

	protected static $availableOptions = array('options_state', 'options_country');
	
	public static function getOption($optionName)
	{
		switch($optionName)
		{
			case 'options_country':
				return Application::getOption('options_country');
			break;
			case 'options_state':
				return Application::getOption('options_state');
			break;

		}
		return null; // nothing found
	}

}
