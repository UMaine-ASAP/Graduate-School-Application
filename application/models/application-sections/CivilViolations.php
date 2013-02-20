<?php

// Models
require_once __DIR__ . "/ApplicationInternalModel.php";

class CivilViolations extends ApplicationInternalModel
{
	protected static $tableName   = 'APPLICATION_CivilViolation';
	protected static $primaryKeys = array('civilViolationId');
}
