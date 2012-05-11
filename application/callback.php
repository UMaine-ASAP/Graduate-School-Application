<?php

require_once "libs/database.php";
require_once "libs/variables.php";

$db = new Database();
$db->connect();

function writeData($file, $stringData) {
	$fh = fopen($file, 'a') or die("can't open file");
	fwrite($fh, $stringData);
	fclose($fh);
}

# Parse POST data from Touchnet
$key = $_REQUEST['posting_key'];
$status = $_REQUEST['pmt_status'];
$trans_id = $_REQUEST['EXT_TRANS_ID'];
$identifier_array = explode("*", $trans_id);
$upaysiteID = $identifier_array[0];
$applicantID = $identifier_array[1];
$transID = $identifier_array[2];

$payment_method = "";
if ($status == 'success') {

	if( isset( $_REQUEST['card_type'] )) {
		$payment_method = "CREDIT";
	} else {
		$payment_method = "ACH";	
	}

}



writeData("log.txt", $status . "\n");
writeData("log.txt", json_encode($_REQUEST) . "\n");

# IF transaction was successful:
if ($status == "success") {

	$db->iquery("UPDATE applicants SET application_fee_payment_status='Y' WHERE applicants.applicant_id=%d", $applicantID);
	$db->iquery("UPDATE applicants SET application_fee_transaction_date='%s' WHERE applicants.applicant_id=%d", date("Y-m-d"), $applicantID);
	$db->iquery("UPDATE applicants SET application_fee_transaction_type='Online' WHERE applicants.applicant_id=%d", $applicantID);

	$db->iquery("UPDATE applicants SET application_fee_transaction_payment_method='%s' WHERE applicants.applicant_id=%d", $payment_method, $applicantID);

	$db->close();

} else {
	$db->close();
}

?>