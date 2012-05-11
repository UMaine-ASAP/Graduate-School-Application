<?
	# Update Proxy DB
	# Requires php_oci8 extension
	include_once "libs/variables.php";

	$database_user = $GLOBALS["touchnet_db_user"];
	$database_pass = $GLOBALS["touchnet_db_pass"];
	$database_name = $GLOBALS["touchnet_db_name"];




	$db  = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)";
	$db .=	"(HOST = " . $GLOBALS["touchnet_db_host"] . ")(PORT=" . $GLOBALS["touchnet_db_port"] . "))";
	$db .=	"(CONNECT_DATA=";//(SERVER=DEDICATED)";
	$db .=	"(SID=xxxx)))";//".$database_name.")))";

	$db  = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)";
	$db .= "(HOST = ".$GLOBALS['touchnet_db_host'].")";
	$db .= "(PORT = ".$GLOBALS["touchnet_db_port"].")";
//	$db .= ")))";
	$db .= "))(CONNECT_DATA=(SID=1234)))";
//	$db .= "))(CONNECT_DATA=(SERVICE_NAME=".$database_name.")))";


function writeData2($file, $stringData) {
	$fh = fopen($file, 'a') or die("can't open file");
	fwrite($fh, $stringData);
	fclose($fh);
}

	//$db = $GLOBALS["touchnet_db_host"] . ":" . $GLOBALS["touchnet_db_port"] . "/" . $database_name . ":POOLED";
	print $db;
	print "<br>";
	if($c = oci_connect($database_user, $database_pass, $db) ) {
		writeData("log.txt", "Openning connection: ");		
		//perform query
		//$qry = "UPDATE UPAY_REQUESTS SET APP_STATUS='C', APP_MSG='".$app_msg."', APP_DATE=SYSDATE WHERE REQ_APP_TRAN_ID=".$trans_id;
		$qry = "SELECT * FROM UPAY_REQUESTS WHERE ext_trans_id = " . $trans_id;

		$stid = oci_parse($c, $qry) or logStuff("ERROR: ". var_dump(oci_error($c)));
		$r = oci_execute($stid) or logStuff("ERROR: ". var_dump(oci_error($stid)));
		oci_free_statement($stid);
		oci_close($c);
		
		writeData2("log.txt", var_export($r));

	} else {
		//writeData2("log.txt", "Could not make connection to or open database: ". $database_name ." : ". var_dump(oci_error()) );
		die("Could not make connection to or open database: ". $database_name ." : ". var_dump(oci_error()));
	}
?>
