<?php
/*****************************
 * Database Settings
 ******************************/


date_default_timezone_set('America/New_York');

$db_user = "root"; 					 // Database User
$db_pass = ""; 					 // Database Password
$db_host = "localhost"; 				 // Database Host
$db_name = "gradschool_application_2.0"; // Database Table

$key = "asp0df8ijapwonlkjs0a7sd092"; // DES Encryption Key


/*****************************
 * Touchnet Settings
 ******************************/

//Production settings flag
$touchnet_production = FALSE;

if($touchnet_production) {
	$touchnet_site_id 	  = "59";
	$touchnet_app_id 	  = "UMGRAD";
	$touchnet_proxy_url   = "https://secure.touchnet.com/C22921_upay/web/index.jsp";
	$touchnet_posting_key = "6#us8$";
} else {
	$touchnet_site_id 	  = "94";
	$touchnet_app_id 	  = "UMGRAD";
    	$touchnet_proxy_url   = "https://secure.touchnet.com:8443/C22921test_upay/web/index.jsp";	
	$touchnet_posting_key = "+73ht$";
}


/*****************************
 * Website Settings
 ******************************/

$rootFilePath 		= '/Users/timbaker/';
$server_name 		= $_SERVER['SERVER_NAME'];
$graduate_homepage 	= "http://".$_SERVER['SERVER_NAME']."/grad/drupal6/"; 	// where the drupal site is located
$grad_app_root 	= "http://".$server_name."/gradschool/application/";	// Graduate Application document root

$admin_email = "crystal.burgess@maine.edu";

// Session timeout in seconds
$session_timeout = 600; // 10 minutes


/*****************************
 * File Directories
 ******************************/
$applicant_file_path = $rootFilePath . "/application files";

// Applicant Files
$essays_path 			= $applicant_file_path . "/essays/"; 			// Where Essays are stored on submit
$resumes_path 			= $applicant_file_path . "/essays/"; 			// Where resumés are stored on submit
$completed_pdfs_path 	= $applicant_file_path . "/completed_pdfs/"; 	// Where pdfs are stored on submit --- 
$recommendations_path 	= $applicant_file_path . "/recommendations/"; 	// Where recommendations are stored, after the recommender fills out the online form and submits

// Images
$grad_images = $grad_app_root . "images/"; // Images folder inside application root

// Templates
$email_templates = 'templates/emails/';

/*****************************
 * SFTP Settings
 ******************************/

$gradschool_path = "/Users/gradd2app/gradschool/"; // Path to the Umaine Graduate School user account on the server to be accessed through sftp
$mainestreet_path = "/Users/grad1mainst/mainestreet/"; // Path to the MaineStreet user account on the server to be accessed through sftp


/*****************************
 * Other Settings
 ******************************/

$mainestreet_group_name 	= "gradmainstreet";
$gradschool_group_name 	= "gradoffice";

