<?php
  /************************************************************************
 Class: Oracle
 Purpose: To allow access to an Oracle database
  ************************************************************************/

class Oracle
{
	var $database_name;
	var $database_user;
	var $database_pass;
	var $database_host;	
	var $database_port;	
	var $database_link;

	//DEVELOPMENT
	//function Oracle($database_user="TNUMGRAD", $database_pass="B21gkS", $database_host="admdev.db.unet.maine.edu", $database_port=1521, $database_name="UPAY_REQUESTS")

	//PRODUCTION
	function Oracle($database_user="TNUMGRAD", $database_pass="B21gkS", $database_host="admapps.db.unet.maine.edu", $database_port=1521, $database_name="UPAY_REQUESTS")

	{
		$this->database_user = $database_user;
		$this->database_pass = $database_pass;
		$this->database_host = $database_host;
		$this->database_port = $database_port;
		$this->database_name = $database_name;
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
