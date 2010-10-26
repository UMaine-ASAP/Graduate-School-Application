<?php
/************************************************************************
 Class: Oracle
 Purpose: To allow access to an Oracle database
************************************************************************/

include_once "variables.php";

class Oracle
{
	var $database_name;
	var $database_user;
	var $database_pass;
	var $database_host;	
	var $database_port;	
	var $database_link;

	//PRODUCTION
	function Oracle($database_user, $database_pass, $database_host, $database_port, $database_name)
	{
		$this->database_user = $this->isset($user)?$user:$GLOBALS["touchnet_db_user"];
		$this->database_pass = $this->isset($pass)?$pass:$GLOBALS["touchnet_db_pass"];
		$this->database_host = $this->isset($host)?$host:$GLOBALS["touchnet_db_host"];
		$this->database_host = $this->isset($port)?$host:$GLOBALS["touchnet_db_port"];
		$this->database_name = $this->isset($name)?$name:$GLOBALS["touchnet_db_name"];
	}
	
	function connect()
	{
    		$connection_string = $this->database_host . ":". $this->database_port ."/". $this->database_name .":POOLED";

$ora_conn = oci_connect($ora_user,$ora_pass,'//'.$ora_host.'/'.$ora_db);

		$this->database_link = oci_connect($this->database_user, $this->database_pass, $connection_string)
		or die("Could not make connection to or open database: ". $this->database_name ." : ". var_dump(oci_error()));
	}

	function iquery($qry)
	{
		# Prepare statement
		$stid = oci_parse($this->database_link, $qry) or die("ERROR: ". var_dump(oci_error($this->database_link)));

		# Perform the logic of the query
		$r = oci_execute($stid) or die("ERROR: ". var_dump(oci_error($stid)));

		# Uncomment this section to use results of query
		# Fetch the results of the query
		# while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
		# #  do something with $row
		# }
		oci_free_statement($stid);
	}

	function close()
	{
		oci_close($this->database_link);
	}
	
}
?>
