<?php


require_once __DIR__ . "/../libs/database.php";
require_once __DIR__ . "/../libs/corefuncs.php";

class Reference extends Model
{
	protected static $tableName = 'APPLICATION_Reference';
	protected static $columnId  = 'ReferenceId';

	protected static $availableProperties = array('options_relationship');

	function __get($name) {
		switch($name)
		{
			case 'options_relationship':
				return array(''    => '- None -',
						'Work'   => 'Work',
						'School' => 'School',
						'Family' => 'Family',
						'Friend' => 'Friend');
			break;
		}
		return parent::__get($name);
	}

}