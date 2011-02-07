<?php

require_once "libs/database.php";
require_once "libs/variables.php";

$db = new Database();
$db->connect();

# Parse POST data from Touchnet
$status = $_REQUEST['pmt_status'];
$trans_id = $_REQUEST['EXT_TRANS_ID'];
$identifier_array = explode("*", $trans_id);
$upaysiteID = $identifier_array[0];
$applicantID = $identifier_array[1];
$transID = $identifier_array[2];

# IF transaction was successful:
if ($status == "success") {

	$app_msg = "success";
	$db->iquery("UPDATE applicants SET application_fee_payment_status='Y' WHERE applicants.applicant_id=%d", $applicantID);
	$db->iquery("UPDATE applicants SET application_fee_transaction_date='%s' WHERE applicants.applicant_id=%d", date("Y-m-d"), $applicantID);
	$db->iquery("UPDATE applicants SET application_fee_transaction_type='Online' WHERE applicants.applicant_id=%d", $applicantID);

	$db->close();

	# Update Proxy DB

	include_once("return-callback.php");

} else {
	$db->close();
}

?>
