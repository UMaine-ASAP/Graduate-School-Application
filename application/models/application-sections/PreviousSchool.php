<?php

// Models
require_once __DIR__ . "/ApplicationInternalModel.php";

class PreviousSchool extends ApplicationInternalModel
{
	protected static $tableName   = 'APPLICATION_PreviousSchool';
	protected static $primaryKeys = array('previousSchoolId');
}
