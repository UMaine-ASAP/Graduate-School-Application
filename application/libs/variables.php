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
//Encryption Key
$key = "asp0df8ijapwonlkjs0a7sd092";



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
