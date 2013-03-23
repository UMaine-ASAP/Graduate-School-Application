<?php

/**
 * Manages a single object tied to a database table
 * 
 * Specify tableName, identifying keys, and any extra properties for use in Twig template using protected variables
 * 
 * @author Tim Westbaker
 */
class Model
{
	protected static $entityName;  // store the name of the model
	protected static $tableName;	 // Database table name
	protected static $primaryKeys; // Array of identifying columns

	// Twig isset parameters. See __isset() function override call below
	protected static $availableProperties = array(); // Custom properties 
	protected static $availableOptions    = array(); // Array of options stored as value=>name


	private $values;	 // Array of values associated with fields in the table
	private $is_dirty; // Array of values changed that need saving

	private $whereClause;		// query wherestring addition
	private $whereReplacements;	// Where replacement information

	private $query; // the query string to run
	private $args;  // the arguments for the query string



	/**
	 * Model Initializer
	 */
	function Model($name = '')
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

		$this->resetQuery();
	}
	

	/**
	 * Magic Getter
	 * 
	 * Retrieve value from options or database table
	 */
	public function __get($name)
	{
		// Check if this is a request for available options
		if( strpos($name, 'options_') !== false) {
			$result = static::getOption($name);
			if( ! is_null($result) )
			{
				return $result;
			}
		}

		if( isset($this->values[$name]) )
		{
			return $this->values[$name];
		}
	}


	/**
	 * Magic setter
	 * 
	 * Set a field property
	 */
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


	/**
	 * Magic isset
	 * 
	 * We need to correctly overide __isset in order to use any magic variables in Twig. See magicGetters, available properties, and available options for more information
	 */
	public function __isset($name)
	{
		// check if option is set in child classes getters or available options
		if ( in_array($name, static::$availableProperties) || in_array($name, static::$availableOptions) ) 
		{
			return true;
		}

		if ( array_key_exists($name, $this->values) ) 
		{
			return true;
		}
		return false;
	}


	/**
	 * Set value without making dirty
	 */
	private function setValue($key, $value) 
	{
		$this->values[$key] = "$value";
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

	
	// Convert DB Array to entity object
	public function loadFromDB($dbData)
	{
		if( $dbData == array() )
		{
			return null;
		} else {
			// find the primary keys
			foreach( static::$primaryKeys as $keyNumber=>$primaryKey )
			{
				if( isset($dbData[$primaryKey]) )
				{
					$idName = 'id' . ($keyNumber + 1);
					$this->setValue($idName, $dbData[$primaryKey]);
				} else {
					throw new Exception("ID $primaryKey for Model " . $this->entityName . " not found when loading.");
				}
			}

			// load remaining data
			$this->loadData($dbData);			

			// id is a synonym for id1
			if( count(static::$primaryKeys) != 0 )
			{
				$this->setValue('id', $this->values['id1']);				
			}

			return $this;
		}
	}


	// Access options in a database table formated as id, value, title
	static protected function getOptionsFromDB($optionName)
	{
		$idColumnName = strtolower($optionName) . "Id";

		$options = Database::query("SELECT * FROM %s Order BY %s", 'OPTIONS_' . $optionName, $idColumnName);
		// convert to associative array
		$result = array();
		foreach($options as $option) {
			$result[$option['value']] = $option['title'];
		}
		return $result;
	}	


	/* =============================== */
	/* = Database Query Functions
	/* =============================== */

	/**
	 * Starts a new database query
	 * 
	 * @param    string    Model class name to create
	 * 
	 * @return    object    New model of the specified type
	 */
	public static function factory($entityName)
	{
		return new $entityName($entityName);
	}


	/**
	 * Deletes the object from the database
	 * 
	 * @return void
	 */
	public function delete()
	{
		$this->query = "DELETE FROM %s LIMIT 1";
		$this->args = array(static::$tableName);

		$this->queryAppendUnique();
		$this->iquery();
	}


	/**
	 * Save database changes
	 * 
	 * @return void
	 */
	public function save()
	{
		$this->query = "UPDATE %s SET ";

		$is_first = true;
		
		if ($this->is_dirty == array()) return; // nothing to save

		// build query string
		foreach($this->is_dirty as $dirty_column) {
			if($is_first) 
			{
				$is_first = false;
			} else {
				$this->query .= ",";
			}
			$this->query .= " `%s`='%s'";
		}

		// Set arguments
		$this->args = array(static::$tableName);

		foreach($this->is_dirty as $dirty_column) {
			$this->args[] = $dirty_column;
			$this->args[] = $this->values[$dirty_column];
		}

		// set to save unique value
		$this->queryAppendUnique();

		// run query
		$this->iquery();
	}


	/**
	 * Retrieve all model objects from the query
	 * 
	 * @return    array    Array of models of specified type
	 */
	public function get()
	{
		$this->query = "SELECT * FROM %s WHERE %s = %d ORDER BY %s ASC";
		$this->args = array(static::$tableName, $this->whereReplacements[0], $this->whereReplacements[1], static::$primaryKeys[0]);
		$dataAr = $this->query();

		$result = array();
		foreach($dataAr as $data) {
			$entity = new static::$entityName(static::$entityName);
			$entity->loadFromDB($data);
			$result[] = $entity;
		}
		return $result;
	}	


	/**
	 * Retrieve a model object from the query
	 * 
	 * @return    Object    Model
	 */
	public function first()
	{
		$this->query = "SELECT * FROM %s WHERE %s = %d ";
		$this->args = array(static::$tableName, $this->whereReplacements[0], $this->whereReplacements[1]);		

		$result = $this->queryFirst();
		$this->loadFromDB($result);

		return $this;
	}	


	/* --------------- */
	/* - Query Helpers
	/* --------------- */

	/**
	 * Retrieve first database result based on built query string
	 * 
	 * @return array
	 */
	private function queryFirst()
	{
		// Add tablename to query
		$args = array_merge( array($this->query), $this->args);

		$result = call_user_func_array( array('Database', 'getFirst'), $args);
		$this->resetQuery();
		return $result;		
	}


	/**
	 * Retrieve database result based on built query string
	 * 
	 * @return array
	 */
	private function query()
	{
		// Add tablename to query
		$args = array_merge( array($this->query), $this->args);

		$result = call_user_func_array( array('Database', 'query'), $args);		
		$this->resetQuery();
		return $result;
	}


	/**
	 * Run iquery
	 * 
	 * @return void
	 */
	private function iquery()
	{
		// Add tablename to query
		$args = array_merge( array($this->query), $this->args);

		call_user_func_array( array('Database', 'iquery'), $args);
		$this->resetQuery();
	}


	/**
	 * Reset query
	 * 
	 * @return void
	 */
	private function resetQuery()
	{
		$this->query = '';
		$this->args = array();		
	}


	/**
	 * Appends where equal clause to query
	 * 
	 * @param    string    Fieldname for where clause
	 * @param    string    Value to check equality
	 * 
	 * @return    this
	 */
	public function whereEqual($fieldName, $value)
	{
		$this->whereClause = ' %s = %s ';
		$this->whereReplacements = array($fieldName, $value);
		return $this;
	}


	/**
	 * Appends where clause to query string to ensure a unique item is selected
	 * 
	 * @param    bool    True if where string should be included, false if where clause has already been started
	 * 
	 * @return    void
	 */
	protected function queryAppendUnique($beginsWhere = TRUE)
	{
		// Build Query Tag on - WHERE key1 = %d AND key2 = %d ...
		$queryPieces = array_map( function($key) { return " `$key` = %d "; }, static::$primaryKeys);

		if( $beginsWhere )
		{
			$this->query .= ' WHERE ';
		}  else {
			$this->query .= ' AND ';			
		}

		$this->query .= ' ' . implode(' AND ', $queryPieces);

		// Add args
		foreach( static::$primaryKeys as $keyNumber=>$key )
		{
			$idName = 'id' . ((int)$keyNumber + 1);
			$this->args[] = $this->values[$idName];
		}
	}

}