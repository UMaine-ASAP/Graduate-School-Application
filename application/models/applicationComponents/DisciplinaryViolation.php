<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";

class DisciplinaryViolation extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_DisciplinaryViolation';
	protected static $primaryKeys = array('disciplinaryViolationId', 'applicationId');

	public static function getWithId($violationId)
	{
		$application = ApplicationController::getActiveApplication();
		if( $application == null)
		{
			return null;
		}
		$dbObject = Database::getFirst("SELECT * FROM APPLICATION_DisciplinaryViolation WHERE applicationId = %d AND disciplinaryViolationId = %d ", $application->id, $violationId);

		$violation = Model::factory('DisciplinaryViolation');
		$violation->loadFromDB($dbObject);
		return $violation;
	} 	
}
