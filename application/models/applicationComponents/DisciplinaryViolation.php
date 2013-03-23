<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";

class DisciplinaryViolation extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_DisciplinaryViolation';
	protected static $primaryKeys = array('disciplinaryViolationId', 'applicationId');

}
