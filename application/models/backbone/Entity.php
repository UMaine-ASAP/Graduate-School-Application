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

	protected $values;
	protected $is_dirty;

	private $whereClause;
	private $whereReplacements;



	function Entity($name = '')
	{
		$this->is_dirty = array();
		$this->values = array();

		$this->whereClause = '%s = %s';
		$this->whereReplacements = array('1', '1');	

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
	public function loadData($data)
	{
		foreach($data as $key=>$value)
		{
			$this->setValue($key, $value);
		}
		return $this;
	}

	// Set value without making dirty
	private function setValue($key, $value) 
	{

		$this->values[$key] = "$value";
	}

	public static function factory($entityName)
	{
		return new Entity($entityName);
	}

	public function query($query, $args)
	{
		// Add tablename to query
		$args = array_merge( array($query, $this->tableName), $args);
		return call_user_func_array( array('Database', 'getFirst'), $args);		
	}

	public function __get($name)
	{

		if( isset($this->values[$name]) )
		{
			return $this->values[$name];
		}
	}

	public function __set($key, $value)
	{	

		if( isset($this->values[$key]) )
		{
			$this->is_dirty[] = $key;
			$this->values[$key] = $value;
		} else {
			//throw new Exception("Key $key does not exist");
		}
	}


	public function save()
	{
		$query = "UPDATE %s SET ";

		$is_first = true; 
		foreach($this->is_dirty as $dirty_column) {
			if($is_first) 
			{
				$is_first = false;
			} else {
				$query .= ",";
			}
			$query .= " `%s`='%s'";
		}

		$query .= " WHERE `%s` = %d";

		// Set args
		$args = array($query, $this->tableName);

		foreach($this->is_dirty as $dirty_column) {
			$args[] = $dirty_column;
			$args[] = $this->values[$dirty_column];
		}

		// set where clause
		$args[] = $this->columnId;
		$args[] = $this->values['id'];

		$result = call_user_func_array( array('Database', 'iquery'), $args);
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

			// find the unique identifier
			if( isset($result[$entity->columnId]) )
			{
				$entity->setValue('id', $result[$entity->columnId]);
			} else {
				throw new Exception("ID not found when loading.");
			}
			$entity->loadData($result);

			return $entity;
		}
	}	
}