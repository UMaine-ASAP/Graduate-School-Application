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
	protected static $entityName; // store the name of the entity
	protected static $tableName;	// Database table name
	protected static $columnId;	// Identifying column

	protected $values;	
	protected $is_dirty;	// Values changed for saving

	private $whereClause;		// WhereEqual structure
	private $whereReplacements;	// Replacement information


	function Entity($name = '')
	{
		$this->is_dirty = array();
		$this->values = array();

		$this->whereClause = '%s = %s';
		$this->whereReplacements = array('1', '1');	

		if ($name == '') {
			static::$entityName = get_class($this);
		} else {
			static::$entityName = $name;
		}
	}
	


	public static function factory($entityName)
	{
		return new $entityName($entityName);
	}


	public function delete()
	{
	}


	// Set value without making dirty
	private function setValue($key, $value) 
	{

		$this->values[$key] = "$value";
	}


	// @pragma Querying

	public function whereEqual($fieldName, $value)
	{
		$this->whereClause = ' %s = %s ';
		$this->whereReplacements = array($fieldName, $value);
		return $this;
	}



	public function queryFirst($query, $args)
	{
		// Add tablename to query
		$args = array_merge( array($query, static::$tableName), $args);

		return call_user_func_array( array('Database', 'getFirst'), $args);		
	}

	public function query($query, $args)
	{
		// Add tablename to query
		$args = array_merge( array($query, static::$tableName), $args);

		return call_user_func_array( array('Database', 'query'), $args);		
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

	public function __isset($name)
	{		
		if ( array_key_exists($name, $this->values) ) 
		{
			return true;
		}
		return false;
	}

	public function save()
	{
		$query = "UPDATE %s SET ";

		$is_first = true; 
		if ($this->is_dirty == array()) return;
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

		$args = array($query, static::$tableName);

		foreach($this->is_dirty as $dirty_column) {
			$args[] = $dirty_column;
			$args[] = $this->values[$dirty_column];
		}

		// set where clause
		$args[] = static::$columnId;
		$args[] = $this->values['id'];

		// run query
		$result = call_user_func_array( array('Database', 'iquery'), $args);
	}

	public function get()
	{
		$dataAr = $this->query("SELECT * FROM %s WHERE %s = %d ", $this->whereReplacements);

		$result = array();
		foreach($dataAr as $data) {
			$entity = new static::$entityName(static::$entityName);
			$entity->loadFromDB($data);
			$result[] = $entity;
		}
		return $result;
	}	


	public function first()
	{
		$result = $this->queryFirst("SELECT * FROM %s WHERE %s = %d ", $this->whereReplacements);
		$this->loadFromDB($result);
		return $this;
	}	

	// @pragma Load data

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


	// Convert DB Array to entity object
	protected function loadFromDB($dbData)
	{
		if( $dbData == array() )
		{
			return null;
		} else {
			$columnId = static::$columnId;

			// find the unique identifier
			if( isset($dbData[$columnId]) )
			{
				$this->setValue('id', $dbData[$columnId]);
			} else {
				throw new Exception("ID not found when loading.");
			}
			$this->loadData($dbData);

			return $this;
		}


	}
}