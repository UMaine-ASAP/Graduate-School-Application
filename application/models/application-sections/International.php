<?php

// Models
require_once __DIR__ . "/ApplicationInternalModel.php";


class International extends ApplicationInternalModel
{
	protected static $tableName   = 'APPLICATION_International';
	protected static $primaryKeys = array('internationalId', 'applicationId');
}
