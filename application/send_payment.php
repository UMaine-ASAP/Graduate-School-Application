<?php
include_once "libs/database.php";
include_once "libs/template.php";
include_once "libs/corefuncs.php";
include_once "libs/variables.php";

$user = check_ses_vars();
$user = ($user)?$user:header("location:./pages/login.php");

$db = new Database();
$db->connect();

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
$data.='AMT='.$app_cost;
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
curl_exec($ch);
curl_close($ch);

?>
