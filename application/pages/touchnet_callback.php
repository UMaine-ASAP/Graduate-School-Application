<?php

require_once "libs/database.php";
require_once "libs/variables.php";

$db = new Database();
$db->connect();

// Parse data from Touchnet
$key 	  = $_REQUEST['posting_key'];
$status   = $_REQUEST['pmt_status'];
$trans_id = $_REQUEST['EXT_TRANS_ID'];

$identifier_array = explode("*", $trans_id);

$upaysiteID  = $identifier_array[0];
$applicantID = $identifier_array[1];
$transID 	 = $identifier_array[2];

$result = $db->query('SELECT application_fee_transaction_number FROM applicants WHERE applicant_id=%d', $applicantID);
$stored_transaction_id = $result[0];

// Process successful payments
if ($status == "success" 
	&& $key == $GLOBALS['touchnet_posting_key']
	&& $stored_transaction_id == $trans_id
	) 
{

	// Set Payment method
	$payment_method = "";

	if( isset( $_REQUEST['card_type'] )) {
		$payment_method = "CREDIT";
	} else {
		$payment_method = "ACH";	
	}

	// Update Database
	$db->iquery("UPDATE applicants SET application_fee_payment_status='Y' WHERE applicants.applicant_id=%d", $applicantID);
	$db->iquery("UPDATE applicants SET application_fee_transaction_date='%s' WHERE applicants.applicant_id=%d", date("Y-m-d"), $applicantID);
	$db->iquery("UPDATE applicants SET application_fee_transaction_type='Online' WHERE applicants.applicant_id=%d", $applicantID);

	$db->iquery("UPDATE applicants SET application_fee_transaction_payment_method='%s' WHERE applicants.applicant_id=%d", $payment_method, $applicantID);

}

$db->close();


?>