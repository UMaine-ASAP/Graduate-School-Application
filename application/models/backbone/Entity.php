<?php

/**
 * Entity
 * 
 * Defines how object instantiation classes work
 * 
 * An entity is a single distinct object of the system. Examples include a particular user, an evaluation, a question, etc...
 * 
 * @author Tim Westbaker
 * @created 11/8/12
 */
class Entity
{
	protected $entityName; 	// store the name of the entity
	protected $tableName;
	protected $columnId;	

	function Entity($name = '')
	{
		if ($name == '') {
			$this->entityName = get_class($this);
		} else {
			$this->entityName = $name;
		}
	}
	
	public function callFunctionOnDB($function, $arguments)
	{
		return forward_static_call_array(array($this->entityName.'DBAccess', "$function"), $arguments);
	}

	public function save()
	{
		return callFunctionOnDB('save', $this);
	}

	public function delete()
	{
		return callFunctionOnDB('delete', $this);
	}


	/**
	 * Load associative data into entity
	 * 
	 * This method sets the entities properties using an associative array 
	 * 
	 * @param 	associative array 		data to load into entity in format property=>value
	 * 
	 * @return 	null
	 */		
	protected function loadData($data)
	{
		foreach($data as $key=>$value)
		{
			if ( ! (isset($this->$key) || property_exists($this, $key) ) )
			{
				throw new Exception("ENTITY ERROR: Property $key in " . get_class($this) . ' does not exist!');
			}
			$this->{$key} = $value;
		}
	}

	public static function factory($entityName)
	{
		return new EntityTemplate($entityName);
	}

	public function query($query, $whereValues)
	{
		return Database::getFirst($query, $this->tableName, $whereValues[0], $whereValues[1]);
	}
}

class EntityTemplate
{
	private $entityName;
	private $whereClause;
	private $whereReplacements;

	function EntityTemplate($name)
	{
		$this->entityName = $name;
		$this->whereClause = '%s = %s';
		$this->whereReplacements = array('1', '1');		
	}

	public function createFromDatabase($row)
	{
		foreach($row as $column=>$value) 
		{
			$this->$column = $value;
		}
		return $this;
	}

	public function whereEqual($fieldName, $value)
	{
		$this->whereClause = ' %s = %s ';
		$this->whereReplacements = array($fieldName, $value);
		return $this;
	}

	public function first()
	{
		$entity = new $this->entityName($this->entityName);

		$result = $entity->query("SELECT * FROM %s WHERE %s = %d ", $this->whereReplacements);

		if( $result == array() )
		{
			return null;
		} else {
			$this->createFromDatabase($result);
			return $this;			
		}
	}	
}