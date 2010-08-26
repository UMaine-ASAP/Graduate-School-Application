<?php
include_once "libs/database.php";
include_once "libs/template.php";
include_once "../forms/signin/includes/corefuncs.php";

//*********************************************************************************************
// Database Login
//*********************************************************************************************
$db = new Database();
$db->connect();

//*********************************************************************************************
// Determine User  ***check for sql injection risks***
//*********************************************************************************************
$user = check_ses_vars();
$user = ($user)?$user:header("location:../forms/signin/");

// $app_cost = $db->query("SELECT application_fee_transaction_amount FROM applicants WHERE applicant_id ={$user}");//risk?

$app_cost = $db->query("SELECT application_fee_transaction_amount FROM applicants WHERE applicant_id =%d", $user);

$app_cost = $app_cost[0][0];

if ($_POST["AMT"] != $app_cost){
	//echo "Hidden fields have been changed";
	header("location:../forms/signin/");
}else{
	do_post_request("https://beech.unet.maine.edu/UPayDev/checkAuth", $_POST);
}

function do_post_request($url, $data, $optional_headers = null)
  {
     $params = array('http' => array(
                  'method' => 'POST',
                  'content' => $data
               ));
     if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
     }
     $ctx = stream_context_create($params);
     $fp = @fopen($url, 'rb', false, $ctx);
     if (!$fp) {
        throw new Exception("Problem with $url, $php_errormsg");
     }
     $response = @stream_get_contents($fp);
     if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
     }
     return $response;
  }
	
	?>