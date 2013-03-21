<?php

$SITEMODE = "DEVELOPMENT"; // DEVELOPMENT or PRODUCTION

date_default_timezone_set('America/New_York');

/* ================================================================ */
/* = Website Settings
/* ================================================================ */

$SERVERNAME        = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
$GRADUATE_HOMEPAGE = "http://$SERVERNAME/grad/drupal6/";           // where the drupal site is located
$WEBROOT           = "http://$SERVERNAME";

$ADMIN_EMAIL     = "crystal.burgess@maine.edu";
$session_timeout = 600; // Session timeout in seconds - 10 minutes


/* ================================================================ */
/* = Database Settings
/* ================================================================ */

$db_user = "root";                       // Database User
$db_pass = "";                           // Database Password
$db_host = "localhost";                  // Database Host
$db_name = "gradschool-application-2";   // Database Table

$key = "asp0df8ijapwonlkjs0a7sd092"; // DES Encryption Key


/* ================================================================ */
/* = Touchnet Settings
/* ================================================================ */

if ($SITEMODE == "DEVELOPMENT") {
	// Testing Settings
	$touchnet_site_id 	  = "94";
	$touchnet_app_id 	  = "UMGRAD";
    	$touchnet_proxy_url   = "https://secure.touchnet.com:8443/C22921test_upay/web/index.jsp";	
	$touchnet_posting_key = "+73ht$";
} else {
	// Production Settings
	$touchnet_site_id 	  = "59";
	$touchnet_app_id 	  = "UMGRAD";
	$touchnet_proxy_url   = "https://secure.touchnet.com/C22921_upay/web/index.jsp";
	$touchnet_posting_key = "6#us8$";
}


/* ================================================================ */
/* = File Directories
/* ================================================================ */

// Applicant Files
$_applicant_file_path = __DIR__ . "/applicationFiles/";
$essays_path          = $_applicant_file_path . "essays/";            // Where Essays are stored on submit
$resumes_path         = $_applicant_file_path . "essays/";            // Where resumés are stored on submit
$completed_pdfs_path  = $_applicant_file_path . "completed_pdfs/";    // Where pdfs are stored on submit
$recommendations_path = $_applicant_file_path . "recommendations/";   // Where recommendations are stored, after the recommender fills out the online form and submits
$tmp_path             = $_applicant_file_path . "tmp/";

// Templates
$email_templates = 'views/templates/emails/';


/* ================================================================ */
/* = SFTP Settings
/* ================================================================ */

$gradschool_path        = "/Users/gradd2app/gradschool/";    // Path to the Umaine Graduate School user account on the server to be accessed through sftp
$mainestreet_path       = "/Users/grad1mainst/mainestreet/"; // Path to the MaineStreet user account on the server to be accessed through sftp
$mainestreet_group_name = "gradmainstreet";
$gradschool_group_name  = "gradoffice";

