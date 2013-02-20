<?php

// Models
require_once __DIR__ . "/ApplicationInternalModel.php";


class Personal extends Model
{
	protected static $tableName   = 'APPLICATION_Primary';
	protected static $primaryKeys = array('applicationId');
}
