<?php
///////////////////////
// Database Settings
///////////////////////

//Database User
$db_user = "root";//"grad3";
//Database Password
$db_pass = "asap4u";//"temple";
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
$touchnet_production = TRUE;

if($touchnet_production) {
	//Touchnet Site ID
	$touchnet_site_id = "59";
	//Touchnet Application ID
	$touchnet_app_id = "UMGRAD";
	//Touchnet Payment Proxy
	$touchnet_proxy_url = "https://beech.unet.maine.edu/UPayProxy/checkAuth";

	//Touchnet Database User
	$touchnet_db_user = "TNUMGRAD";
	//Touchnet Database Password
	$touchnet_db_pass = "B21gkS";
	//Touchnet Database Host
	$touchnet_db_host = "admapps.db.unet.maine.edu";
	//Touchnet Database Port
	$touchnet_db_port = "1521";
	//Touchnet Database Table
	$touchnet_db_name = "UPAY_REQUESTS";

} else {
	//Touchnet Site ID
	$touchnet_site_id = "94";
	//Touchnet Application ID
	$touchnet_app_id = "UMGRAD";
	//Touchnet Payment Proxy
	$touchnet_proxy_url = "https://beech.unet.maine.edu/UPayDev/checkAuth";

	//Touchnet Database User
	$touchnet_db_user = "TNUMGRAD";
	//Touchnet Database Password
	$touchnet_db_pass = "B21gkS";
	//Touchnet Database Host
	$touchnet_db_host = "admdev.db.unet.maine.edu";
	//Touchnet Database Port
	$touchnet_db_port = "1521";
	//Touchnet Database Table
	$touchnet_db_name = "UPAY_REQUESTS";
}



///////////////////////
// Website Settings
///////////////////////

//Where the drupal site is located
$graduate_homepage = "http://hoonah.asap.um.maine.edu/grad/drupal6/";



///////////////////////
// Cron Settings
///////////////////////

//Where Essays are stored on submit
$essays_path = "/Library/WebServer/Documents/grad/essays/";
//Where resumés are stored on submit
$resumes_path = "/Library/WebServer/Documents/grad/essays/";
//Where pdfs are stored on submit --- 
$completed_pdfs_path = "/Library/WebServer/Documents/grad/pdf_export/completed_pdfs/";
//Where recommendations are stored, after the recommender fills out the online form and submits
$recommendations_path = "/Library/WebServer/Documents/grad/forms/recommendations/";



///////////////////////
// Ftp Settings
///////////////////////

//Path to the Umaine Graduate School user account on the server
//To be accessed through sftp
$gradschool_path = "/Users/gradd2app/gradschool/";
//Path to the MaineStreet user account on the server
//To be accessed through sftp
$mainestreet_path = "/Users/grad1mainst/mainestreet/";



///////////////////////
// Other Settings
///////////////////////

$mainestreet_group_name = "gradmainstreet";
$gradschool_group_name = "gradoffice";
?>
