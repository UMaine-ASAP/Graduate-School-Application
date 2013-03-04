<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";


class Transaction extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_Transaction';
	protected static $primaryKeys = array('transactionId');
}
