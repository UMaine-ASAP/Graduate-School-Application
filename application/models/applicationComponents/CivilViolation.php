<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";

class CivilViolation extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_CivilViolation';
	protected static $primaryKeys = array('civilViolationId', 'applicationId');



	public static function getWithId($violationId)
	{
		$application = ApplicationController::getActiveApplication();
		if( $application == null)
		{
			return null;
		}
		$dbObject = Database::getFirst("SELECT * FROM APPLICATION_CivilViolation WHERE applicationId = %d AND civilViolationId = %d ", $application->id, $violationId);

		$violation = Model::factory('civilViolation');
		$violation->loadFromDB($dbObject);
		return $violation;	} 	

}
