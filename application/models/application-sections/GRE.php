<?php

// Models
require_once __DIR__ . "/ApplicationInternalModel.php";

class GRE extends ApplicationInternalModel
{
	protected static $tableName   = 'APPLICATION_GRE';
	protected static $primaryKeys = array('GREId');
}
