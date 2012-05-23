<?php
///////////////////////
// Database Settings
///////////////////////

//Database User
$db_user = "root";
//Database Password
$db_pass = "asap4u";
//Database Host
$db_host = "localhost";
//Database Table
$db_name = "grad5app";
//DES Encryption Key
$key = "asp0df8ijapwonlkjs0a7sd092";



///////////////////////
// Touchnet Settings
///////////////////////

//Production settings flag
$touchnet_production = FALSE;

if($touchnet_production) {
	//Touchnet Site ID
	$touchnet_site_id = "59";
	//Touchnet Application ID
	$touchnet_app_id = "UMGRAD";
	//Touchnet Payment Proxy
	$touchnet_proxy_url = "https://beech.unet.maine.edu/UPayProxy/checkAuth";
	//Posting Key
	$touchnet_posting_key = "6#us8$";

} else {
	//Touchnet Site ID
	$touchnet_site_id = "94";
	//Touchnet Application ID
	$touchnet_app_id = "UMGRAD";
	//Touchnet Payment Proxy
    $touchnet_proxy_url = "https://secure.touchnet.com:8443/C22921test_upay/web/index.jsp";	
	//$touchnet_proxy_url = "https://beech.unet.maine.edu/UPayDev/checkAuth";
    //Posting Key
	$touchnet_posting_key = "+73ht$";
}



///////////////////////
// Website Settings
///////////////////////

$server_name = $_SERVER['SERVER_NAME'];
//Where the drupal site is located
$graduate_homepage = "http://".$_SERVER['SERVER_NAME']."/grad/drupal6/";
$admin_email = "crystal.burgess@maine.edu";

// echo $server_name;
//Session timeout in seconds
$session_timeout = 600; //10 minutes



///////////////////////
// Cron Settings
///////////////////////

//Where Essays are stored on submit
$essays_path = "/Library/WebServer/Documents/grad_application/essays/";
//Where resumés are stored on submit
$resumes_path = "/Library/WebServer/Documents/grad_application/essays/";
//Where pdfs are stored on submit --- 
$completed_pdfs_path = "/Library/WebServer/Documents/grad_application/pdf_export/completed_pdfs/";
//Where recommendations are stored, after the recommender fills out the online form and submits
$recommendations_path = "/Library/WebServer/Documents/grad_application/recommendations/";



///////////////////////
// Ftp Settings
///////////////////////

//Path to the Umaine Graduate School user account on the server
//To be accessed through sftp
$gradschool_path = "/Users/gradd2app/gradschool/";
//Path to the MaineStreet user account on the server
//To be accessed through sftp
$mainestreet_path = "/Users/grad1mainst/mainestreet/";
//Graduate Application document root
$grad_app_root = "http://".$server_name."/grad_application/application/";
// Images folder inside application root
$grad_images = $grad_app_root . "images/";



///////////////////////
// Other Settings
///////////////////////

$mainestreet_group_name = "gradmainstreet";
$gradschool_group_name = "gradoffice";
?>
