<?php
/************************************************************************
 Class: DATABASE
 Purpose: To allow access to a MySQL database
************************************************************************/

include_once "variables.php";


class Database
{
	private static $database_name;
	private static $database_user;
	private static $database_pass;
	private static $database_host;	
	private static $database_link;
	
	private static $database_instance;
	private static $instance_count;

	// Used to create database object
	public static function getInstance() 
	{ 
    	if (!self::$database_instance) 
    	{ 
    	    self::$database_instance = new Database(); 
    	    self::$instance_count = 1;
    	    self::$database_instance->connect();
	    } else { 
    	    self::$instance_count++;
	    }

    	return self::$database_instance; 
	}  

	private function Database($user=null, $pass=null, $host=null, $name=null)
	{
		self::$database_name = isset($name)?$name:$GLOBALS["db_name"];		
		self::$database_user = isset($user)?$user:$GLOBALS["db_user"];
		self::$database_pass = isset($pass)?$pass:$GLOBALS["db_pass"];
		self::$database_host = isset($host)?$host:$GLOBALS["db_host"];
	}

	function __destruct() {
    	self::$instance_count--;
    	if( self::$instance_count < 0) self::$instance_count = 0;

    	if( self::$instance_count == 0 && self::$database_instance) {    		
			self::$database_instance->close();
			self::$database_instance = null;
    	}

	}

	function connect()
	{
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
	
    function close()
	{
		// Only allow close after all instances are gone
		if(self::$instance_count != 0) {
			$this->__destruct();
		} else {
		if(isset(self::$database_link))
			self::$database_link->close();
		}
	}

	function isConnected() {
		return self::$database_link == TRUE; //return boolean not a reference
	}

	function query(/*$query, [[, $args [, $... ]]*/)
	{
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
					$returnArray[$i][] = $value;
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
	  			$query = vsprintf($query, $this->escapeArgs($args));

			if(($result = self::$database_link->query($query)) instanceof MySQLi_Result) {
				$i = 0;
				while($row = $result->fetch_object()) {
					$returnArray[$i] = array();
					foreach($row as $key => $value) {
						$returnArray[$i][] = $value;
						$returnArray[$i][$key] = $value;

					}
					$i++;
				}
				$result->close();
			}
		}

		return $returnArray;
	}


	function iquery(/*$query, [[, $args [, $... ]]*/)
	{
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
	  			$query = vsprintf($query, $this->escapeArgs($args));

			if(($result = self::$database_link->query($query)) instanceof MySQLi_Result)
			 	$result->close();
		}
	}
	
	
	function getFirst(/*$query, [[, $args [, $... ]]*/)
	{
		$returnValue = "";

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
			//Don't need variable names because only first is required, 
			//but do need the correct number of variables to bind the results to
			$meta = $stmt->result_metadata();
			$result = array();
			while($field = $meta->fetch_field())
				$result[] = 0;
			$meta->close();

			//In order to dynmically bind results, references to the 
			//bound result variables are required in call_user_func_array
			$bindVars = array();
			foreach($result as $key => $value) 
			    $bindVars[$key] = &$result[$key];
			$params = array_merge(array($stmt) , $bindVars);
			call_user_func_array('mysqli_stmt_bind_result', $params);

			//Format store first filed of first fetch() to be returned
			$stmt->fetch();
			$returnValue = $result[0];
			$stmt->close();

		} else {
			//Prepare may have failed because statement had varaibles 
			//on both sides of operator not allowed.  EX: ... ?=? ...
			//Try escaped strings instaed

			$args = func_get_args();
			$query = array_shift($args);
			if($args)
	  			$query = vsprintf($query, $this->escapeArgs($args));

			if(($result = self::$database_link->query($query)) instanceof MySQLi_Result) {
				if($row = $result->fetch_row())
					$returnValue = $row[0];
				$result->close();
			}
		}

		return $returnValue;
	}

	function escapeArgs($object)
	{
		if (is_array($object))
   			foreach ($object as $key => $value)
				if(is_array($value))
					$object[$key] = $this->escapeArgs($value);
				else
					$object[$key] = $this->escapeString($value);
  		else
    			$object = $this->escapeString($object);
  		return $object;
	}

	function escapeString($str)
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

