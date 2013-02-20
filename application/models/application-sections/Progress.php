<?php

// Models
require_once __DIR__ . "/ApplicationInternalModel.php";

class Progress extends ApplicationInternalModel
{
	protected static $tableName   = 'APPLICATION_Process';
	protected static $primaryKeys = array('ProcessId');
}
