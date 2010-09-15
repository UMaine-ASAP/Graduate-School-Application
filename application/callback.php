<?php

	# Parse POST data from Touchnet
	$status = $_REQUEST['pmt_status'];
	$identifier = $_REQUEST['EXT_TRANS_ID'];
	$identifier_array = explode("*", $identifier);

	$upaysiteID = $identifier_array[0];
	$applicantID = $identifier_array[1];
	$transID = $identifier_array[2];

	# IF transaction was successful:
	if ($status == "success") {

		# Update Gradsite DB
		require_once "libs/database.php";
		$db = new Database();
		$db->connect();
		$db->iquery("UPDATE applicants SET application_fee_payment_status='Y' WHERE applicants.applicant_id=%d", $applicantID);
		$db->iquery("UPDATE applicants SET application_fee_transaction_date='%s' WHERE applicants.applicant_id=%d", date("Y-m-d"), $applicantID);
		$db->iquery("UPDATE applicants SET application_fee_transaction_type='Online' WHERE applicants.applicant_id=%d", $applicantID);
		$db->close();

		# Update Proxy DB
		require_once "libs/oracle.php";
		$odb = new Oracle();
		$odb->connect();
		$odb->iquery("UPDATE UPAY_REQUESTS SET APP_STATUS='C', APP_MSG='success', APP_DATE=SYSDATE WHERE REQ_APP_TRAN_ID=".$identifier);
		$odb->close();
	}
//*/
?>
