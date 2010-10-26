<?php

require_once "libs/database.php";

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

	# Update Gradsite DB
	require_once "libs/database.php";
	$db = new Database();
	$db->connect();


	$saved_trans_id_query = $db->query("Select application_fee_transaction_number FROM applicants WHERE applicant_id=%d", $user);
	$saved_trans_id = $saved_trans_id_query[0][0];

	if($saved_trans_id == $trans_id) {
		$app_msg = "success";
		$db->iquery("UPDATE applicants SET application_fee_payment_status='Y' WHERE applicants.applicant_id=%d", $applicantID);
		$db->iquery("UPDATE applicants SET application_fee_transaction_date='%s' WHERE applicants.applicant_id=%d", date("Y-m-d"), $applicantID);
		$db->iquery("UPDATE applicants SET application_fee_transaction_type='Online' WHERE applicants.applicant_id=%d", $applicantID);
	} else {
		$app_msg = "failure";
		$db->iquery("UPDATE applicants SET application_fee_payment_status='N' WHERE applicants.applicant_id=%d", $applicantID);
		$db->iquery("UPDATE applicants SET application_fee_transaction_date='%s' WHERE applicants.applicant_id=%d", date("Y-m-d"), $applicantID);
		$db->iquery("UPDATE applicants SET application_fee_transaction_type='Online' WHERE applicants.applicant_id=%d", $applicantID);
	}

	# Update Proxy DB
	require_once "libs/oracle.php";
	$odb = new Oracle();
	$odb->connect();
	$odb->iquery("UPDATE UPAY_REQUESTS SET APP_STATUS='C', APP_MSG='".$app_msg."', APP_DATE=SYSDATE WHERE REQ_APP_TRAN_ID=".$identifier);
	$odb->close();
}

$db->close();

?>
