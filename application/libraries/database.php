<?php
/************************************************************************
 Class: DATABASE
 Purpose: To allow access to a MySQL database
************************************************************************/

include_once __DIR__ . "/../configuration.php";
include_once __DIR__ . "/Error.php";

class ActiveRecord
{
	private $action;
	private $table;
	private $selection;
	private $where;


	function __construct()
	{
		$selection = array();
		$where = array();
	}

	public function get()
	{

	}

	public function first()
	{

	}

	public function select($name, $alias = '')
	{
		$this->selection[] = array('name' => $name, 'alias' => $alias);
	}

	private function buildQueryString()
	{
		$replacements = array();

		switch($action)
		{
			case 'select':
				$query = 'SELECT ';

				// build selection options
				if( count($selection) == 0 ) 
				{
					$query .= ' * ';
				} else {
					foreach( $selection as $item)
					{
						$query .= ' %s ';
						$replacements[] = $item['name'];
						if($item['alias'] != '')
						{
							$query .= ' as %s';
							$replacements[] = $item['alias'];
						}
					}
				}

				// Table

			break;
			default:
			Error::fatal("Action: $action not found");
		}
	}


}


class Database
{
	private static $database_name;
	private static $database_user;
	private static $database_pass;
	private static $database_host;	
	private static $database_link;
	
	private static $database_instance;

	// Used to create database object
	private static function getInstance() 
	{ 
    		if (!self::$database_instance) 
    		{ 
			self::$database_instance = new Database(); 
			self::$database_instance->connect();
		}

    	return self::$database_instance; 
	}  


	/**
	 * Allow user to explicitly set database to connect to
	 */
	public static function connect($user=null, $pass=null, $host=null, $name=null)
	{
		self::createConnection($user, $pass, $host, $name);
	}

	/**
	 * Internal processing
	 */
	private static function createConnection($user=null, $pass=null, $host=null, $name=null)
	{
		// In case database doesn't exist or user is overridding connection
		if( ! is_null($user) || self::$database_link == null ) {
			self::$database_name = isset($name)?$name:$GLOBALS["db_name"];		
			self::$database_user = isset($user)?$user:$GLOBALS["db_user"];
			self::$database_pass = isset($pass)?$pass:$GLOBALS["db_pass"];
			self::$database_host = isset($host)?$host:$GLOBALS["db_host"];

			self::$database_link = new mysqli(
				self::$database_host,
				self::$database_user,
				self::$database_pass,
				self::$database_name			
			);
			/* check connection */
			if (mysqli_connect_errno(self::$database_link)) {
			    error_log("Connect failed: %s\n", mysqli_connect_error());
			    exit();
			}

		}
	}
	

	public static function isConnected() {
		return self::$database_link == TRUE; //return boolean not a reference
	}

	public static function getFirst()
	{
		$result = call_user_func_array('self::query', func_get_args());
	
		if( $result != array() )
		{
			return $result[0];
		} else {
			return null;
		}
	}

	public static function query(/*$query, [[, $args [, $... ]]*/)
	{
		self::createConnection();

		$returnArray = array();

		$args = func_get_args();
		$query = array_shift($args);

		//Available binding types mysqli accepts
		$avail_types = array("i", "d", "s", "b");

		//Get the types in the order they appear in the query
		$reg_ex = "/%[".implode($avail_types, "|")."]/";
		preg_match_all($reg_ex, $query, $matches);
		$types = str_replace("%", "", implode($matches[0]));

		//Some queries have quotes that need to be removed.
		//Also need placeholers to be ? instead of %s or %d ... etc
		$replacements = array();
		foreach($avail_types as $type)
			$replacements[] = "%".$type;
		$query = str_replace($replacements, "?", $query);

		//remove quotes
		$quote_types = array("'", "`", '“', '”');
		$diff_ways_to_quote = array();
		foreach($quote_types as $type1)
			foreach($quote_types as $type2)
				$diff_ways_to_quote[] = $type1."?".$type2;
		$query = str_replace($diff_ways_to_quote, "?", $query);

		if($stmt = self::$database_link->prepare($query)) {

			//Bind parameters
			if( count($args) != 0) {
				//In order to dynmically bind parameter, references to the 
				//bound parameter variables are required in call_user_func_array
				$bindVars = array();
				foreach($args as $key => $value) 
				    $bindVars[$key] = &$args[$key];
				$params = array_merge(array($stmt, $types), $bindVars);

				call_user_func_array('mysqli_stmt_bind_param', $params);				
			}		

			//Run query
			$stmt->execute();
			$stmt->store_result();

			//Retrieve meta info on the results.
			//This provides the variable names + the correct 
			//number of variables to bind the results to
			$meta = $stmt->result_metadata();
			$result = array();
			while($field = $meta->fetch_field())
				$result[$field->name] = 0;
			$meta->close();

			//In order to dynmically bind results, references to the 
			//bound result variables are required in call_user_func_array
			$bindVars = array();
			foreach($result as $key => $value) 
			    $bindVars[$key] = &$result[$key];
			$params = array_merge(array($stmt) , $bindVars);
			call_user_func_array('mysqli_stmt_bind_result', $params);

			//Format results + store each row's results for every fetch()
			$i = 0;
			while($stmt->fetch()) {
				$returnArray[$i] = array();
				foreach($result as $key => $value) {
					//$returnArray[$i][] = $value;
					$returnArray[$i][$key] = $value;
				}
				$i++;
			}

			$stmt->close();

		} else {
			//Prepare may have failed because statement had varaibles 
			//on both sides of operator not allowed.  EX: ... ?=? ...
			//Try escaped strings instaed

			$args = func_get_args();
			$query = array_shift($args);
			if($args)
	  			$query = vsprintf($query, self::escapeArgs($args));

			if(($result = self::$database_link->query($query)) instanceof MySQLi_Result) {
				$i = 0;
				while($row = $result->fetch_object()) {
					$returnArray[$i] = array();
					foreach($row as $key => $value) {
						//$returnArray[$i][] = $value;
						$returnArray[$i][$key] = $value;

					}
					$i++;
				}
				$result->close();
			}
		}

		// foreach($returnArray[] as $key=>$var){ 
		//      if(is_numeric($key)){ 
  //    	 		unset($returnArray[$key]); 
 	// 		} 
		// }

		return $returnArray;
	}


	public static function iquery(/*$query, [[, $args [, $... ]]*/)
	{
		self::createConnection();
		
		$args = func_get_args();
		$query = array_shift($args);

		//Available binding types mysqli accepts
		$avail_types = array("i", "d", "s", "b");

		//Get the types in the order they appear in the query
		$reg_ex = "/%[".implode($avail_types, "|")."]/";
		preg_match_all($reg_ex, $query, $matches);
		$types = str_replace("%", "", implode($matches[0]));

		//Some queries have quotes that need to be removed.
		//Also need placeholers to be ? instead of %s or %d ... etc
		$replacements = array();
		foreach($avail_types as $type)
			$replacements[] = "%".$type;
		$query = str_replace($replacements, "?", $query);

		//remove quotes
		$quote_types = array("'", "`", '“', '”');
		$diff_ways_to_quote = array();
		foreach($quote_types as $type1)
			foreach($quote_types as $type2)
				$diff_ways_to_quote[] = $type1."?".$type2;
		$query = str_replace($diff_ways_to_quote, "?", $query);

		if($stmt = self::$database_link->prepare($query)) {
	
			//Bind parameters
			if( count($args) != 0) {			
				//In order to dynmically bind parameter, references to the 
				//bound parameter variables are required in call_user_func_array
				$bindVars = array();
				foreach($args as $key => $value) 
				    $bindVars[$key] = &$args[$key];
				$params = array_merge(array($stmt, $types), $bindVars);
				call_user_func_array('mysqli_stmt_bind_param', $params);
			}

			//Run query
			$stmt->execute();
			$stmt->close();

		} else {
			//Prepare may have failed because statement had varaibles 
			//on both sides of operator not allowed.  EX: ... ?=? ...
			//Try escaped strings instaed

			$args = func_get_args();
			$query = array_shift($args);
			if($args)
	  			$query = vsprintf($query, self::escapeArgs($args));

			if(($result = self::$database_link->query($query)) instanceof MySQLi_Result)
			 	$result->close();
		}
	}
	
	


	public static function escapeArgs($object)
	{
		if (is_array($object))
   			foreach ($object as $key => $value)
				if(is_array($value))
					$object[$key] = self::escapeArgs($value);
				else
					$object[$key] = self::escapeString($value);
  		else
    			$object = self::escapeString($object);
  		return $object;
	}

	public static function escapeString($str)
	{
		if (get_magic_quotes_gpc())
			$str = stripslashes($str);
		if(self::$database_link)
			$str = self::$database_link->real_escape_string($str);
		else
			$str = addslashes($str);
		return $str;
	}
}

