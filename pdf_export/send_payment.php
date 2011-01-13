<?php
include_once "../application/libs/database.php";
include_once "../application/libs/template.php";
include_once "../application/libs/corefuncs.php";
include_once "../application/libs/variables.php";

$user = check_ses_vars();
$user = ($user)?$user:header("location:../application/pages/login.php");

$db = new Database();
$db->connect();

//Get has_been_submitted status
$query  = "SELECT `has_been_submitted`";
$query .= "FROM `applicants`";
$query .= "WHERE `applicant_id`= %d";
$result = $db->query($query, $user);
$has_been_submitted = $result[0]['has_been_submitted'];

//process application only once!
if($has_been_submitted == 0){

	//update database to show that application has been submitted
	$db_update = "";
	$db_update .= "UPDATE `applicants` ";
	$db_update .= "SET `has_been_submitted` = '1' ";
	$db_update .= "WHERE `applicant_id` = $user LIMIT 1";
	$db->iquery($db_update);
		
	//update application submit date in database
	$db_update = "";
	$db_update .= "UPDATE `applicants` ";
	$db_update .= "SET `application_submit_date` = '". date("Y-m-d");
	$db_update .= "' WHERE `applicant_id` = $user LIMIT 1";
	$db->iquery($db_update);



	//Send Recommendation and create pdf of application
	require "recommender.php";
	require "pdf_export_server.php";

	//Send Payment

	//Generate transaction ID
	$trans_id = '*'.$user.'*'.time();

	//update database transaction_id
	$db->iquery("UPDATE applicants SET application_fee_transaction_number='%s' WHERE applicant_id=%d", $trans_id, $user);

	//Fetch application cost
	$app_cost_query = $db->query("SELECT application_fee_transaction_amount FROM applicants WHERE applicant_id=%d", $user);
	$app_cost = $app_cost_query[0][0];

	//Build request
	$data ='UPAY_SITE_ID='.$GLOBALS["touchnet_site_id"].'&';
	$data.='UMS_APP_ID='.$GLOBALS["touchnet_app_id"].'&';
	$data.='EXT_TRANS_ID='.$trans_id.'&';
	$data.='AMT='. $app_cost;
	$header = array("MIME-Version: 1.0","Content-type: application/x-www-form-urlencoded","Contenttransfer-encoding: text");

	//Execute request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_URL, $GLOBALS["touchnet_proxy_url"]);
	curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
	curl_setopt($ch, CURLPROTO_HTTPS, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);


	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	if ( ! $result = curl_exec($ch) ) {
		trigger_error(curl_error($ch));
	}
	curl_close($ch);

} else {
	header('Location: ../application/pages/lockout.php');
}

?>
