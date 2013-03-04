<?php

// Libraries
require_once __DIR__ . "/../../libs/database.php";


// Models
require_once __DIR__ . "/ApplicationComponent.php";


class ContactInformation extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_ContactInformation';
	protected static $primaryKeys = array('contactInformationId', 'applicationId');

	public static function getWithId($contactInformationId)
	{
		$application = ApplicationController::getActiveApplication();
		if( $application == null)
		{
			return null;
		}
		$dbObject = Database::getFirst("SELECT * FROM APPLICATION_ContactInformation WHERE applicationId = %d AND contactInformationId = %d", $application->id, $contactInformationId);

		$contactInfo = Model::factory('ContactInformation');

		$contactInfo->loadFromDB($dbObject);
		return $contactInfo;
	} 

	public static function createAndGetId()
	{
		$application = ApplicationController::getActiveApplication();
		if( $application == null)
		{
			return null;
		}		
		Database::iquery("INSERT INTO APPLICATION_ContactInformation(applicationId) VALUES (%d)", $application->id);
		$tmp = Database::getFirst('SELECT LAST_INSERT_ID() as id');
		return $tmp['id'];
	}

}
