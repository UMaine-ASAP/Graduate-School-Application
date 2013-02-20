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
class Model
{
	protected static $entityName; // store the name of the entity
	protected static $tableName;	// Database table name
	protected static $primaryKeys;	// Identifying columns

	protected $values;	
	protected $is_dirty;	// Values changed for saving

	private $whereClause;		// WhereEqual structure
	private $whereReplacements;	// Replacement information

	private $query; // the query string to run
	private $args; // the arguments for the query string

	// Twig isset parameters. See __isset() function override call below
	protected static $availableProperties = array();
	protected static $availableOptions    = array();


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

	// We need to correctly overide __isset in order to use any magic variables in Twig. See magicGetters and available options for more information
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



	// Set value without making dirty
	private function setValue($key, $value) 
	{

		$this->values[$key] = "$value";
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


	// @pragma Querying

	public static function factory($entityName)
	{
		return new $entityName($entityName);
	}

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

	public function delete()
	{
		$this->query = "DELETE FROM %s ";
		$this->args = array(static::$tableName);

		$this->queryAppendUnique();
		$this->iquery();
	}


	public function whereEqual($fieldName, $value)
	{
		$this->whereClause = ' %s = %s ';
		$this->whereReplacements = array($fieldName, $value);
		return $this;
	}



	public function queryFirst()
	{
		// Add tablename to query
		$args = array_merge( array($this->query), $this->args);

		$result = call_user_func_array( array('Database', 'getFirst'), $args);
		$this->resetQuery();
		return $result;		
	}

	public function query()
	{
		// Add tablename to query
		$args = array_merge( array($this->query), $this->args);

		$result = call_user_func_array( array('Database', 'query'), $args);		
		$this->resetQuery();
		return $result;
	}

	public function iquery()
	{
		// Add tablename to query
		$args = array_merge( array($this->query), $this->args);

		$result = call_user_func_array( array('Database', 'iquery'), $args);
		$this->resetQuery();
		return $result;
	}

	protected function resetQuery()
	{
		$this->query = '';
		$this->args = array();		
	}


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

	public function get()
	{
		$this->query = "SELECT * FROM %s WHERE %s = %d ";
		$this->args = array(static::$tableName, $this->whereReplacements[0], $this->whereReplacements[1]);
		$dataAr = $this->query();

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
		$this->query = "SELECT * FROM %s WHERE %s = %d ";
		$this->args = array(static::$tableName, $this->whereReplacements[0], $this->whereReplacements[1]);		
		$result = $this->queryFirst();
		$this->loadFromDB($result);

		return $this;
	}	


}