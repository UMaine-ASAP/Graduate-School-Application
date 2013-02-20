<?php

// Models
require_once __DIR__ . "/ApplicationInternalModel.php";

class DisciplinaryViolations extends ApplicationInternalModel
{
	protected static $tableName   = 'APPLICATION_DisciplinaryViolation';
	protected static $primaryKeys = array('disciplinaryVioliationId');
}
