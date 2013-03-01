<?php

// Models
require_once __DIR__ . "/ApplicationComponent.php";

class Progress extends ApplicationComponent
{
	protected static $tableName   = 'APPLICATION_Process';
	protected static $primaryKeys = array('ProcessId');
}
