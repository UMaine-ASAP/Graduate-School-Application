<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";

class CivilViolation extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_CivilViolation';
	protected static $primaryKeys = array('civilViolationId', 'applicationId');
}
