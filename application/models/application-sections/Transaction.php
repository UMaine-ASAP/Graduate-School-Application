<?php

// Models
require_once __DIR__ . "/ApplicationInternalModel.php";


class Transaction extends ApplicationInternalModel
{
	protected static $tableName   = 'APPLICATION_Transaction';
	protected static $primaryKeys = array('transactionId');
}
