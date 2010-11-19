<?
	# Update Proxy DB
	#Requires php_oci8 extension
	include_once "libs/variables.php";

	$database_user = $GLOBALS["touchnet_db_user"];
	$database_pass = $GLOBALS["touchnet_db_pass"];
	$database_name = $GLOBALS["touchnet_db_name"];
	$connection_string = $GLOBALS["touchnet_db_host"] . ":" . $GLOBALS["touchnet_db_port"] . "/" . $database_name . ":POOLED";

	if($c = oci_connect($database_user, $database_pass, $connection_string) ) {
		
		//perform query
		$qry = "UPDATE UPAY_REQUESTS SET APP_STATUS='C', APP_MSG='".$app_msg."', APP_DATE=SYSDATE WHERE REQ_APP_TRAN_ID=".$identifier;

		$stid = oci_parse($c, $qry) or logStuff("ERROR: ". var_dump(oci_error($c)));
		$r = oci_execute($stid) or logStuff("ERROR: ". var_dump(oci_error($stid)));
		oci_free_statement($stid);
		
	oci_close($c);

	} else {

		die("Could not make connection to or open database: ". $database_name ." : ". var_dump(oci_error()));
	}
?>