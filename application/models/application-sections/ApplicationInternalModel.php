<?php

require_once __DIR__ . "/../Model.php";

class ApplicationInternalModel extends Model
{
	public static function createNew()
	{
		$application = ApplicationController::getActiveApplication();
		if( $application == null) { return null; }

		$appId = $application->applicationId;

		// Get current index
		$newIndex = -1;
		$temp = Database::getFirst("SELECT %s as id FROM %s WHERE applicationId = %d ORDER BY %s DESC", static::$primaryKeys[0], static::$tableName, $appId, static::$primaryKeys[0]);
		if( $temp == array())
		{
			$newIndex = 1;
		} else {
			$newIndex = (int) $temp['id'] + 1;
		}
		Database::iquery("INSERT INTO %s(%s, %s) VALUES (%d,%d)", static::$tableName, static::$primaryKeys[0], 'applicationId', $newIndex, $appId);

		$result = Database::getFirst("SELECT * FROM %s WHERE %s=%d AND applicationId = %d", static::$tableName, static::$primaryKeys[0], $newIndex, $appId);
		//$result['id'] = $result[static::$columnId];

		$entityName = get_called_class();
		$entity = new $entityName($entityName);
		$entity->loadFromDB($result);
		return $entity;
	}	

	public static function all($appId)
	{
		return Database::query("SELECT * FROM %s WHERE applicationID = %i", static::$tableName, $appId);


	}

}