<?php
/************************************************************************
 Class: DATABASE
 Purpose: To allow access to a MySQL database
************************************************************************/

class Database
{
	var $database_name;
	var $database_user;
	var $database_pass;
	var $database_host;	
	var $database_link;
		
	function Database($user="grad3", $pass="temp1e", $host="localhost", $name="grad5app")
	{
		$this->database_user = $this->escapeString($user);
		$this->database_pass = $this->escapeString($pass);
		$this->database_host = $this->escapeString($host);
		$this->database_name = $this->escapeString($name);
	}

	function changeAll($user, $pass, $host, $name)
	{
		$this->database_user = $this->escapeString($user);
		$this->database_pass = $this->escapeString($pass);
		$this->database_host = $this->escapeString($host);
		$this->database_name = $this->escapeString($name);
	}
	
	function changeUser($user)
	{
		//Change the name of the User
		$this->database_user = $this->escapeString($user);	
	}
	
	function changePass($pass)
	{
		//Change the password for the User
		$this->database_pass = $this->escapeString($pass);
	}
	
	function changeHost($host)
	{
		//Change the host name where the database is located
		$this->database_host = $this->escapeString($host);	
	}
	
	function changeName($name)
	{
		//Change the name of the Database you are connecting to
		$this->database_name = $this->escapeString($name);	
	}
	
	function connect()
	{
		$this->database_link = mysql_connect($this->database_host, $this->database_user, $this->database_pass) or die("Could not make connection to MySQL");
		mysql_select_db($this->database_name) or die ("Could not open database: ". $this->database_name);
	}

	function query(/*$query, [[, $args [, $... ]]*/)
	{
		$args = func_get_args();
		$query = array_shift($args);

  		if ($args)
			$query = vsprintf($query, $this->escapeArgs($args));
		if(!isset($this->database_link))
			$this->connect();

 		$result = mysql_query($query, $this->database_link) or die("Error: ".$query." ". mysql_error());
		$returnArray = array();
		$i=0;
		while ($row = mysql_fetch_array($result, MYSQL_BOTH))
			$returnArray[ $i++ ] = $row;

		return $returnArray;	
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
		if($this->database_link != null)
			$escape = mysql_real_escape_string($str, $this->database_link);
		else
			$escape = mysql_real_escape_string($str);
		if(!$escape)
			return addslashes($str);
		else
			return $escape;
	}
	
	function returnTable($qry,$wdth,$bdr,$clr1,$clr2)
	{
		$retn = $this->query($qry);
		$bgflop = 0;
		
		$mystring = "<table width='".$wdth."' border='".$bdr."' cellspacing='0' cellpadding='0'>";
		for($i=0;$i<count($retn);$i++)
		{
			if($bgflop==0) { $mystring .= "<TR bgcolor='".$clr1."'>"; $bgflop=1; }
			else { $mystring.= "<TR bgcolor='".$clr2."'>"; $bgflop=0; }
			
			$temphold = $retn[$i];
			for($z=0;$z<(count($temphold)/2);$z++)
			{
				$mystring .= "<TD border=0>".$temphold[$z]."</TD>";	
			}
			$mystring .= "</TR>";
		}
		$mystring .= "</table>";
		return $mystring;
	}

	function iquery(/*$query, [[, $args [, $... ]]*/)
	{
		$args = func_get_args();
		$query = array_shift($args);
  		if ($args)
			$query = vsprintf($query, $this->escapeArgs($args));
		if(!isset($this->database_link))
			$this->connect();
		mysql_query($query, $this->database_link) or die("Error: ".$query." ". mysql_error());
	}

	function getFirst(/*$query, [[, $args [, $... ]]*/)
	{
		$args = func_get_args();
		$query = array_shift($args);

  		if ($args)
			$query = vsprintf($query, $this->escapeArgs($args));
		if(!isset($this->database_link))
			$this->connect();
 
		$result = mysql_query($query, $this->database_link) or die("Error: ".$query." ". mysql_error());
		$row = mysql_fetch_array($result, MYSQL_BOTH);
		return $row[0];
	}
	
	function GetRandomRecord($tableName)
	{
		$records = $this->query("SELECT * FROM %s", $tableName);
		srand((double)microtime()*1000000); 
		shuffle ($records);
		return $records[0];
	} 
	
    	function close()
	{
		if(isset($this->database_link))
			mysql_close($this->database_link); 
		else
			mysql_close();	
	}
}
?>
